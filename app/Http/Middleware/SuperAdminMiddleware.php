<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        \Log::info('Method: ' . $request->method());
        \Log::info('Role: ' . auth()->user()?->role);
        // Kullanıcı giriş yapmış mı VE rolü super_admin mi?
        if (auth()->check() && auth()->user()->role === 'super_admin') {
            return $next($request);
        }

        // Yetkisi yoksa 403 engeli döndür
        abort(403, 'Bu alana erişim yetkiniz yok.');
    }
}