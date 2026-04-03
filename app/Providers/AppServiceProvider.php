<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Schema;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\ResetPassword;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // --- SOLUCIÓN PARA LOS ESTILOS EN EL CELULAR ---
// Forzar HTTPS si la app no está en localhost puro o si detecta un túnel
    if (!app()->isLocal() || request()->header('X-Forwarded-Proto') === 'https' || str_contains(request()->getHost(), 'loca.lt') || str_contains(request()->getHost(), 'ngrok')) {
        URL::forceScheme('https');
    }

        // --- PERSONALIZACIÓN DEL CORREO DE VERIFICACIÓN ---
        // Esto modifica lo que viste en Mailtrap
       VerifyEmail::toMailUsing(function ($notifiable, $url) {
    return (new MailMessage)
        ->subject('🌸 ¡Bienvenido a la familia Florentica!') // Asunto más cálido
        ->greeting('¡Es un placer saludarte!') // Saludo con clase
        ->line('Gracias por elegir Florentica.')
        ->line('Para activar tu perfil de cliente y comenzar a explorar nuestra colección exclusiva, solo necesitas confirmar tu cuenta:')
        ->action('Activar mi cuenta', $url) // Botón con más "call to action"
        ->line('Si no has solicitado este registro, puedes ignorar este mensaje con total seguridad.')
        ->salutation('Atentamente, el equipo de Florentica.');
});


ResetPassword::toMailUsing(function ($notifiable, $token) {
    // Generamos la URL manualmente para que apunte a tu ruta de 'password.reset'
    $url = url(route('password.reset', [
        'token' => $token,
        'email' => $notifiable->getEmailForPasswordReset(),
    ], false));

    return (new MailMessage)
        ->subject('🌸 Recupera tu acceso a Florentica')
        ->greeting('¡Hola de nuevo!')
        ->line('Recibimos una solicitud para restablecer la contraseña de tu cuenta.')
        ->line('No te preocupes, estas cosas pasan. Haz clic en el botón de abajo para elegir una nueva clave:')
        ->action('Cambiar mi contraseña', $url)
        ->line('Este enlace de recuperación expirará en ' . config('auth.passwords.'.config('auth.defaults.passwords').'.expire') . ' minutos.')
        ->line('Si tú no solicitaste este cambio, puedes ignorar este correo; tu cuenta seguirá segura.')
        ->salutation('Saludos, el equipo de Florentica.');
});

        // --- VARIABLES COMPARTIDAS GLOBALMENTE ---
        view()->share('appName', 'Florentica');
        view()->share('contactEmail', 'contacto@florentica.com');
        view()->share('currentYear', date('Y'));
        view()->share('tagline', 'Flores frescas de la UPIICSA a tu casa');
        
        // Compatibilidad con bases de datos antiguas o Laragon
        Schema::defaultStringLength(191);
    }
}