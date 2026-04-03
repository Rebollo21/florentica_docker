@extends('layouts.app')

@section('content')
<div id="mapa-container" style="height: 100vh; width: 100%; background: #000; position: relative; overflow: hidden;">
    
    {{-- BARRA DE BÚSQUEDA --}}
    <div class="position-absolute w-100 p-3" style="z-index: 1001; top: 10px;">
        <div class="input-group shadow-lg">
            <span class="input-group-text bg-dark border-secondary text-warning"><i class="fas fa-search"></i></span>
            <input id="pac-input" type="text" class="form-control bg-dark text-white border-secondary" placeholder="Ajustar mi ubicación manual...">
        </div>
    </div>

    {{-- INTERFAZ DE INFORMACIÓN Y BOTONES EXTERNOS --}}
    <div class="position-absolute w-100 p-3" style="z-index: 1000; bottom: 0; pointer-events: none;">
        <div class="container-fluid p-0">
            <div class="row g-2">
                {{-- Botones de Navegación Externa --}}
                <div class="col-12 d-flex gap-2 mb-2" style="pointer-events: auto;">
                    <button onclick="abrirGoogleMaps()" class="btn btn-primary flex-fill shadow-lg border-0 rounded-3 py-2">
                        <i class="fab fa-google"></i> Ir a Google Maps
                    </button>
                    <button onclick="abrirWaze()" class="btn btn-info text-white flex-fill shadow-lg border-0 rounded-3 py-2" style="background-color: #33ccff;">
                        <i class="fab fa-waze"></i> Waze
                    </button>
                </div>

                {{-- Info de la Entrega --}}
                <div class="col-12" style="pointer-events: auto;">
                    <div class="bg-dark text-white p-3 rounded-4 shadow-lg border border-warning d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <a href="{{ route('delivery.enRuta') }}" class="btn btn-outline-warning rounded-circle me-3" style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                            <div>
                                <small class="text-secondary d-block fw-bold" style="font-size: 0.6rem;">DISTANCIA</small>
                                <span id="info-distancia" class="fw-bold">-- km</span>
                            </div>
                        </div>
                        <div class="text-end">
                            <small class="text-warning d-block fw-bold" style="font-size: 0.6rem;">TIEMPO ESTIMADO</small>
                            <span id="info-tiempo" class="fw-bold fs-4 text-warning">-- min</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="map" style="height: 100%; width: 100%;"></div>

    {{-- BOTONES LATERALES --}}
    <div class="position-absolute d-flex flex-column" style="top: 80px; right: 15px; z-index: 1000; gap: 10px;">
        <button onclick="centrarEnMi()" class="btn btn-warning shadow-lg rounded-circle" style="width: 50px; height: 50px;">
            <i class="fas fa-crosshairs"></i>
        </button>
        <button onclick="resetGpsAuto()" class="btn btn-secondary shadow-lg rounded-circle text-white" style="width: 50px; height: 50px;">
            <i class="fas fa-satellite"></i>
        </button>
    </div>
</div>

<script>
    let map, directionsService, directionsRenderer, userMarker, clienteMarker;
    let miPosicionActual = null;
    let destinoCoordenadas = null;
    let modoManual = false;

    async function initMap() {
        const { Map } = await google.maps.importLibrary("maps");
        const { AdvancedMarkerElement, PinElement } = await google.maps.importLibrary("marker");
        const { Autocomplete } = await google.maps.importLibrary("places");

        directionsService = new google.maps.DirectionsService();
        directionsRenderer = new google.maps.DirectionsRenderer({
            polylineOptions: { strokeColor: "#ffc107", strokeWeight: 7 },
            suppressMarkers: true 
        });

        map = new Map(document.getElementById("map"), {
            zoom: 15,
            center: { lat: 19.4326, lng: -99.1332 },
            mapId: "BOLLOTECH_FINAL_MAP",
            disableDefaultUI: true
        });

        directionsRenderer.setMap(map);

        // Autocomplete
        const input = document.getElementById("pac-input");
        const autocomplete = new Autocomplete(input, { fields: ["geometry"], componentRestrictions: { country: "mx" } });
        autocomplete.addListener("place_changed", () => {
            const place = autocomplete.getPlace();
            if (place.geometry) {
                map.panTo(place.geometry.location);
                setUbicacionManual(place.geometry.location);
            }
        });

        // Marcadores
        userMarker = new AdvancedMarkerElement({
            map: map,
            content: new PinElement({ background: "#4285F4", borderColor: "white" }).element,
            title: "Tú",
            gmpDraggable: true
        });

        clienteMarker = new AdvancedMarkerElement({
            map: map,
            content: new PinElement({ background: "#ffc107", borderColor: "#000" }).element,
            title: "Cliente"
        });

        map.addListener("click", (e) => setUbicacionManual(e.latLng));
        userMarker.addListener("dragend", (e) => setUbicacionManual(e.latLng));

        obtenerUbicacionCliente();
    }

    // NAVEGACIÓN EXTERNA
    function abrirGoogleMaps() {
        if (!destinoCoordenadas) return alert("Esperando ubicación del cliente...");
        const lat = destinoCoordenadas.lat();
        const lng = destinoCoordenadas.lng();
        // Esto abre la app de Google Maps en modo navegación
        window.open(`https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}&travelmode=driving`, '_blank');
    }

    function abrirWaze() {
        if (!destinoCoordenadas) return alert("Esperando ubicación del cliente...");
        const lat = destinoCoordenadas.lat();
        const lng = destinoCoordenadas.lng();
        window.open(`https://waze.com/ul?ll=${lat},${lng}&navigate=yes`, '_blank');
    }

    function setUbicacionManual(latLng) {
        modoManual = true;
        miPosicionActual = { lat: latLng.lat(), lng: latLng.lng() };
        userMarker.position = miPosicionActual;
        trazarRuta();
    }

    function resetGpsAuto() {
        modoManual = false;
        iniciarSeguimientoGPS();
    }

    function obtenerUbicacionCliente() {
        const geocoder = new google.maps.Geocoder();
        const direccion = "{{ $item->calle }} {{ $item->num_ext }}, {{ $item->colonia }}, {{ $item->municipio }}, Mexico";
        geocoder.geocode({ address: direccion }, (results, status) => {
            if (status === "OK") {
                destinoCoordenadas = results[0].geometry.location;
                clienteMarker.position = destinoCoordenadas;
                iniciarSeguimientoGPS();
            }
        });
    }

    function iniciarSeguimientoGPS() {
        if (navigator.geolocation) {
            navigator.geolocation.watchPosition(
                (position) => {
                    if (modoManual) return;
                    miPosicionActual = { lat: position.coords.latitude, lng: position.coords.longitude };
                    userMarker.position = miPosicionActual;
                    trazarRuta();
                },
                null, { enableHighAccuracy: true }
            );
        }
    }

    function trazarRuta() {
        if (!miPosicionActual || !destinoCoordenadas) return;
        directionsService.route({
            origin: miPosicionActual,
            destination: destinoCoordenadas,
            travelMode: 'DRIVING'
        }, (result, status) => {
            if (status === 'OK') {
                directionsRenderer.setDirections(result);
                const leg = result.routes[0].legs[0];
                document.getElementById('info-tiempo').innerText = leg.duration.text;
                document.getElementById('info-distancia').innerText = leg.distance.text;
            }
        });
    }

    function centrarEnMi() {
        if (miPosicionActual) map.panTo(miPosicionActual);
    }
</script>

<script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places&callback=initMap" async defer></script>

<style>
    body, html { height: 100%; margin: 0; overflow: hidden; background: #000; }
    #map { position: absolute; top: 0; bottom: 0; width: 100%; }
    .btn-primary { background-color: #4285F4; }
    #pac-input:focus { background-color: #333 !important; color: white; border-color: #ffc107; box-shadow: none; }
</style>
@endsection