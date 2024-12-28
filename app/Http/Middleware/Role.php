<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class Role
{
    public function handle($request, Closure $next, ...$role)
    {
        if(!Auth::check() || !in_array(Auth::user()->role->name, $role))
            return Redirect::route('account.index')->withErrors(['Auth' => 'Authorization required!']);
        return $next($request);
    }
}
