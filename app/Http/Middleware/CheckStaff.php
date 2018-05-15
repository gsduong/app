<?php

namespace App\Http\Middleware;

use Closure;

class CheckStaff
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
        if (!$request->user()->restaurants->where('slug', $slug)->first()) {
            return redirect()->route('restaurant.index')->with('error', 'You are not authorized to visit the given restaurant!');
        }
        return $next($request);
    }
}
