<?php

namespace App\Http\Middleware;

use Closure;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $restaurant_id = $request->route('restaurant_id');
        if (!$request->user()->restaurants->find($restaurant_id) || !$request->user()->restaurants->find($restaurant_id)->pivot->admin) {
            return redirect()->route('restaurant.index')->with('error', 'You are not authorized to delete the given restaurant!');
        }
        return $next($request);
    }
}
