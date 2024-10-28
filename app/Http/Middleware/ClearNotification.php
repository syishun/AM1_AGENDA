<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

class ClearNotification
{
    public function handle($request, Closure $next)
    {
        // Hapus notifikasi di sesi sebelum request
        Session::forget('notification');

        return $next($request);
    }
}
