<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CorsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Handle preflight OPTIONS request
        if ($request->getMethod() === "OPTIONS") {
            return response('', 200)
                ->header('Access-Control-Allow-Origin', $this->getAllowedOrigin($request))
                ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
                ->header('Access-Control-Allow-Headers', 'Accept, Authorization, Content-Type, X-Requested-With, X-CSRF-TOKEN')
                ->header('Access-Control-Allow-Credentials', 'true')
                ->header('Access-Control-Max-Age', '86400');
        }

        $response = $next($request);

        // Add CORS headers to the response
        $response->headers->set('Access-Control-Allow-Origin', $this->getAllowedOrigin($request));
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Accept, Authorization, Content-Type, X-Requested-With, X-CSRF-TOKEN');
        $response->headers->set('Access-Control-Allow-Credentials', 'true');

        return $response;
    }

    /**
     * Get the allowed origin based on the request origin
     */
    private function getAllowedOrigin(Request $request): string
    {
        $origin = $request->headers->get('Origin');
        
        $allowedOrigins = [
            'http://localhost:3000',
            'http://localhost:5173',
            'http://10.60.34.20:3000',
            'http://162.84.221.20',
            'http://162.84.221.20:3000',
            'http://162.84.221.20:5173',
            'http://162.84.221.21',
            'http://162.84.221.21:3000',
            'http://162.84.221.21:5173',
        ];

        if (in_array($origin, $allowedOrigins)) {
            return $origin;
        }

        // For development, you might want to be less strict
        if (app()->environment('local')) {
            return $origin ?: '*';
        }

        return $allowedOrigins[0]; // Default fallback
    }
}
