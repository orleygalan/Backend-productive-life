<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeadersMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Evita que el navegador adivine el tipo de contenido
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Evita que el API se cargue en un iframe
        $response->headers->set('X-Frame-Options', 'DENY');

        // Fuerza HTTPS, nunca HTTP
        $response->headers->set(
            'Strict-Transport-Security',
            'max-age=31536000; includeSubDomains'
        );

        // Proteccion XSS para navegadores antiguos
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // No envia la URL completa como referrer
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Desactiva funciones del navegador que no necesitas
        $response->headers->set(
            'Permissions-Policy',
            'camera=(), microphone=(), geolocation=(), payment=()'
        );

        // Elimina el header que revela que usas PHP
        $response->headers->remove('X-Powered-By');

        return $response;
    }
}