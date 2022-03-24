<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check())
        {
            if(Auth::user()->isAdmin())
            {
                return $next($request);
            }
            else
            {
                Auth::logout();
                throw ValidationException::withMessages([
                    'email' => 'Tài khoản không có quyền truy cập',
                ]);
            }
        }
        return;
    }
}
