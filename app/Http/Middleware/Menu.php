<?php

namespace App\Http\Middleware;

use App\Models\DefaultMenu;
use Closure;
use Illuminate\Http\Request;

class Menu
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $route)
    {
        $menuItem = DefaultMenu::query()->where('route', $route)->first();
        if (!$menuItem && !auth()->user()->is_admin) {
            abort(403);
        }
        return $next($request);
    }
}
