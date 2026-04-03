<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Insumo;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\Rules\Password;
use App\Enums\UserRole;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    /**
     * Muestra el formulario de inicio de sesión.
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Procesa el intento de inicio de sesión.
     */
    public function login(Request $request)
    {
        // 1. Validamos los datos
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $userCheck = User::withTrashed()->where('email', $request->email)->first();

        if ($userCheck && $userCheck->trashed()) {
            return back()->withErrors([
                'error' => 'ACCESO DENEGADO: Tu cuenta ha sido desactivada.',
            ])->onlyInput('email');
        }

        // 2. Intento de autenticación
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();

            $roleLabel = strtoupper($user->role->value ?? $user->role);
            $welcomeMsg = "ACCESO CONCEDIDO: BIENVENIDO, $roleLabel " . strtoupper($user->name);

            $role = $user->role->value ?? $user->role;

            return match($role) {
                'admin'    => redirect()->intended('/admin/dashboard')->with('success', $welcomeMsg),
                'delivery' => redirect()->intended('/delivery/')->with('success', $welcomeMsg),
                'buyer'    => redirect()->intended('/shop')->with('success', $welcomeMsg),
                default    => redirect('/')->with('success', "BIENVENIDO A FLORENTICA"),
            };
        }

        // 3. Fallo de autenticación
        return back()->withErrors([
            'email' => 'FL102: Las credenciales no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }



    /**
     * Procesa el registro de nuevos usuarios.
     */
    public function register(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ];

        $messages = [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.unique' => 'FL104: Este correo ya forma parte de la familia Florentica.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ];

        $request->validate($rules, $messages);

        $role = str_contains(url()->previous(), 'join-delivery') ? 'delivery' : 'buyer';

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $role,
        ]);

        event(new Registered($user));
        Auth::login($user);

        return redirect()->route('verification.notice');
    }

    /**
     * Dashboard administrativo con lógica de búsqueda e inteligencia de lotes.
     */
public function adminDashboard(Request $request)
{
    // --- 1. Mantenimiento Automático (Limpieza de lotes vencidos) ---
    $lotesParaArchivar = \App\Models\Lote::whereHas('insumo', function($q) {
            $q->where('tipo', 'flor');
        })
        ->where('fecha_vencimiento', '<', now()->startOfDay())
        ->whereNull('deleted_at');

    $cantidadArchivados = $lotesParaArchivar->count();
    $lotesParaArchivar->delete();

    // --- 2. Filtros de Búsqueda ---
    $searchUser = trim($request->input('search_user'));

    // --- 3. Consultas de Usuarios e Insumos ---
    $usuarios = User::withTrashed()
        ->when($searchUser, function ($query) use ($searchUser) {
            $term = "%" . strtolower($searchUser) . "%";
            return $query->where('name', 'LIKE', $term)->orWhere('email', 'LIKE', $term);
        })->get();

    $insumos = Insumo::withTrashed()->with('lotes')->get();

    // --- 4. MÉTRICAS DE VENTAS Y LOGS DE ERRORES ---
    $ventasRecientes = DB::table('ventas')
        ->join('users', 'ventas.user_id', '=', 'users.id')
        ->select('ventas.*', 'users.name as cliente')
        ->orderBy('ventas.created_at', 'desc')
        ->limit(10)
        ->get();

    $pagosFallidos = DB::table('pagos_fallidos')
        ->orderBy('created_at', 'desc')
        ->limit(10)
        ->get();

$movimientosLotes = DB::table('detalle_venta_lotes')
    ->join('ventas', 'detalle_venta_lotes.venta_id', '=', 'ventas.id')
    ->join('lotes', 'detalle_venta_lotes.lote_id', '=', 'lotes.id')
    ->join('insumos', 'lotes.insumo_id', '=', 'insumos.id')
    ->select(
        'detalle_venta_lotes.*', 
        'insumos.nombre_insumo', 
        'lotes.id as ident_lote', // <--- CAMBIO AQUÍ: Usamos el ID
        'ventas.id as folio_venta'
    )
    ->orderBy('detalle_venta_lotes.created_at', 'desc')
    ->limit(10)
    ->get();
    // --- 6. Estadísticas Rápidas (Widgets) ---
    $ingresosTotales = DB::table('ventas')->where('estatus', 'Pagado')->sum('total');
    $ventasHoy = DB::table('ventas')->whereDate('created_at', now())->count();
    $fallosHoy = DB::table('pagos_fallidos')->whereDate('created_at', now())->count();

    // --- 7. Retorno a la Vista con toda la carga de datos ---
    return view('admin.dashboard', [
        'usuarios' => $usuarios,
        'insumos' => $insumos,
        'totalUsuarios' => User::whereNull('deleted_at')->count(),
        'totalInactivos' => User::whereNotNull('deleted_at')->count(),
        'totalInsumos' => Insumo::count(),
        'cantidadArchivados' => $cantidadArchivados,
        
        // Ventas y Fallos
        'ventasRecientes' => $ventasRecientes,
        'pagosFallidos' => $pagosFallidos,
        'ingresosTotales' => $ingresosTotales,
        'ventasHoy' => $ventasHoy,
        'fallosHoy' => $fallosHoy,

        // Inventario FIFO
        'movimientosLotes' => $movimientosLotes
    ]);
}

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Sesión terminada. ¡Vuelve pronto!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if (auth()->id() == $user->id) return redirect()->back()->with('error', 'No puedes desactivarte a ti mismo.');
        $user->delete();
        return redirect()->back()->with('success', 'Usuario desactivado.');
    }

    public function restore($id)
    {
        User::withTrashed()->findOrFail($id)->restore();
        return redirect()->back()->with('success', 'Usuario restaurado.');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update($request->only(['name', 'email', 'role']));
        return redirect()->back()->with('success', 'Perfil actualizado.');
    }
}