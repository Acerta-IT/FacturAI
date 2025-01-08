<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\enums\Departments;
use App\enums\Role;
use Illuminate\Support\Facades\Auth;

class FileAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect('/');
        }

        $user = Auth::user();
        if ($user->role === Role::Admin->value || $user->department === Departments::IDi->value) {
            return $next($request);
        }

        abort(403, 'Unauthorized access.');
    }
}
