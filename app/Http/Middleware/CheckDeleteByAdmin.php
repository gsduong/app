<?php

namespace App\Http\Middleware;

use Closure;

class CheckDeleteByAdmin
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
        $slug = $request->route('slug');
        $restaurant = $request->user()->restaurants->where('slug', '=', $slug)->first();
        if (!$restaurant || !$restaurant->pivot->admin) {
            return redirect()->route('restaurant.index')->with('error', 'Not authorized!');
        }
        return $next($request);
    }
}
