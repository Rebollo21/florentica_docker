<?php



use App\Http\Controllers\EntregaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\InsumoController;
use App\Http\Controllers\LoteController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Models\Comment;
use App\Models\Producto;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RamoController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Client\ProfileController;
use App\Http\Controllers\PremiumController;

/*
|--------------------------------------------------------------------------
| 🔓 RUTAS PÚBLICAS
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    $productos = Producto::all();
    $comments = Comment::where('stars', '>=', 3)->where('approved', 1)->latest()->take(6)->get();
    return view('welcome', compact('productos', 'comments'));
})->name('welcome');

// Autenticación y Registro
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', function () { return view('auth.register'); })->name('register');
Route::get('/join-delivery', function () { return view('auth.delivery-register'); })->name('register.delivery');
Route::post('/register', [AuthController::class, 'register']);

// Recuperación de Contraseña
Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

/*
|--------------------------------------------------------------------------
| 🛡️ RUTAS PROTEGIDAS (Auth + Verified)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

   // --- ZONA ADMINISTRADOR (Rectoría) ---
Route::middleware(['role:admin'])->group(function () {
    
    
    
    // 1. Dashboard Principal 
// 1. Dashboard Principal (AHORA CONECTADO AL CONTROLADOR)
Route::get('/admin/dashboard', [AuthController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::get('/admin/productos', [ProductoController::class, 'index'])->name('productos.index');
    // 2. Gestión del Catálogo (Insumos)
    Route::get('/admin/insumos/nuevo', [InsumoController::class, 'create'])->name('insumos.create');
    Route::post('/admin/insumos/guardar', [InsumoController::class, 'store'])->name('insumos.store');

    // 3. Gestión de Lotes (Entradas de Mercancía)
    Route::get('/admin/lotes/nuevo', [LoteController::class, 'create'])->name('lotes.create');
    Route::post('/admin/lotes', [LoteController::class, 'store'])->name('lotes.store');
    
    // ✨ EL CEREBRO DE GEMINI (Nueva Ruta de IA)
    // Esta ruta es la que llama el JavaScript de tu formulario
    Route::get('/admin/ia/sugerir-precio', [LoteController::class, 'sugerirPrecioIA'])->name('ia.sugerir');

    // 4. Gestión de Productos (Ramos Finales)
    Route::get('/admin/productos/nuevo', [ProductoController::class, 'create'])->name('productos.create');
    Route::post('/admin/productos/guardar', [ProductoController::class, 'store'])->name('productos.store');
    
    // 5. Eliminar usario Soft delete
	Route::delete('/admin/usuarios/{id}', [AuthController::class, 'destroy'])->name('usuarios.destroy');
    
    //6. Restaurar
    Route::patch('/admin/usuarios/{id}/restore', [AuthController::class, 'restore'])->name('usuarios.restore');
    
    //7. Editar
    Route::put('/admin/usuarios/{id}', [AuthController::class, 'update'])->name('usuarios.update');
    

    
    // Ruta para que los clientes vean el catálogo
Route::get('/catalogo', [ProductoController::class, 'catalogoPublico'])->name('catalogo.publico');

    // CRUD de Productos (Ramos)
Route::resource('admin/productos', ProductoController::class);
    
    // Ruta que abre la mesa de diseño (GET)
Route::get('/admin/productos/{id}/receta', [ProductoController::class, 'editarReceta'])->name('productos.receta');

// Ruta que guarda el ingrediente (POST)
Route::post('/admin/productos/receta', [ProductoController::class, 'guardarReceta'])->name('productos.receta.guardar');
    
    Route::patch('insumos/{id}/restore', [InsumoController::class, 'restore'])->name('insumos.restore');
    
    Route::resource('insumos', InsumoController::class);
    Route::delete('/admin/lotes/{id}', [LoteController::class, 'destroy'])->name('admin.lotes.destroy');
    
  
Route::patch('/admin/lotes/{id}/restore', [LoteController::class, 'restore'])
    ->name('admin.lotes.restore');
    
    
    Route::patch('/admin/lotes/{id}/restore', [LoteController::class, 'restore'])->name('admin.lotes.restore');
    
    Route::put('/lotes/{id}', [LoteController::class, 'update'])->name('admin.lotes.update');


// Ruta para guardar/actualizar (la que ya tenías)
Route::post('/productos/receta/guardar', [ProductoController::class, 'guardarReceta'])->name('productos.receta.guardar');

// RUTA FALTANTE: El error dice que no existe 'productos.receta.eliminar'
Route::delete('/productos/{producto}/receta/{insumo}', [ProductoController::class, 'eliminarInsumo'])
    ->name('productos.receta.eliminar');

    Route::patch('/productos/{id}/update-precio', [ProductoController::class, 'updatePrecio'])->name('productos.update.precio');

    Route::post('/productos/eliminar-foto', [ProductoController::class, 'eliminarFotoGaleria'])->name('productos.eliminarFoto');
    
    Route::post('/productos/receta/actualizar', [ProductoController::class, 'actualizarCantidadReceta'])->name('productos.actualizarReceta');

    Route::post('/admin/lotes/{id}/merma', [LoteController::class, 'registrarMerma'])->name('lotes.merma');

    // Si usas un grupo con prefijo 'admin'
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // ... tus otras rutas
    Route::post('/lotes/{id}/merma', [App\Http\Controllers\LoteController::class, 'registrarMerma'])->name('lotes.merma');
});


});

    

    // --- ZONA REPARTIDOR ---


// --- ZONA DE LOGÍSTICA (DELIVERY) ---
// --- ZONA DE LOGÍSTICA (DELIVERY) ---
Route::middleware(['auth', 'role:delivery'])->group(function () {
    
    // PÁGINA 1: Listado de pedidos nuevos (Dashboard Principal)
    Route::get('/delivery', [EntregaController::class, 'index'])->name('delivery.index');

    // PÁGINA 2: Listado de pedidos que ya están en calle (En Ruta)
    Route::get('/delivery/en-ruta', [EntregaController::class, 'enRuta'])->name('delivery.enRuta');

    // PÁGINA 3: Vista de navegación/mapa para un pedido específico
    Route::get('/delivery/mapa/{id}', [EntregaController::class, 'mapa'])->name('delivery.mapa');

    // ACCIONES (PATCH): Actualizar estatus (QR o Entrega final)
    // Dejamos una sola ruta oficial para evitar conflictos
    Route::patch('/delivery/update/{id}', [EntregaController::class, 'update'])->name('delivery.update');

    // Detalle opcional
    Route::get('/delivery/pedido/{id}', [EntregaController::class, 'show'])->name('delivery.show');
});
    // --- ZONA CLIENTE (Shop) ---
Route::middleware(['role:buyer'])->group(function () {
    
    // CAMBIO AQUÍ: De AuthController@login a ProductoController@index
    
    Route::get('/shop', [ProductoController::class, 'shop'])->name('shop.index');
    Route::get('/', [ProductoController::class, 'shop'])->name('home'); // Alias para evitar el error anterior 

    Route::get('/shop/producto/{id}', [ProductoController::class, 'show'])->name('shop.show');



Route::get('/profile', [ProfileController::class, 'index'])
    ->middleware(['auth'])
    ->name('client.profile');

Route::post('/comentarios', [CommentController::class, 'store'])->name('comments.store');

    Route::get('/carrito', [CartController::class, 'index'])->name('cart.index');
Route::post('/carrito/agregar/{id}', [CartController::class, 'add'])->name('cart.add');
Route::delete('/carrito/eliminar/{id}', [CartController::class, 'remove'])->name('cart.remove');

Route::patch('/carrito/actualizar/{id}', [CartController::class, 'update'])->name('cart.update');
Route::get('/carrito/vaciar', [CartController::class, 'clear'])->name('cart.clear');

// Si decides usar el CartController:
Route::post('/carrito/confirmar', [App\Http\Controllers\CartController::class, 'procesarVenta'])->name('checkout.procesar');
Route::get('/carrito/seguimiento/{id}', [App\Http\Controllers\CartController::class, 'mostrarSeguimiento'])->name('carrito.seguimiento');


// Cambia 'client.index' por 'index'
Route::get('/shop', [ProductoController::class, 'shop'])->name('shop.index');

// Ruta para ver la landing de Premium
    Route::get('/premium', [PremiumController::class, 'index'])->name('premium.index');
    
    // Ruta POST para procesar la suscripción
    Route::post('/premium/suscribir', [PremiumController::class, 'suscribir'])->name('premium.suscribir');

    Route::post('/premium/cancelar', [PremiumController::class, 'cancelar'])->name('premium.cancelar');

    Route::get('/premium/success', [PremiumController::class, 'success'])->name('premium.success');
    
});



    
});

/*
|--------------------------------------------------------------------------
| 📧 VERIFICACIÓN DE EMAIL
|--------------------------------------------------------------------------
*/
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect()->route('shop.index'); 
})->middleware(['auth', 'signed'])->name('verification.verify');