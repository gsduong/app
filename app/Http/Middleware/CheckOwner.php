<?php

namespace App\Http\Middleware;

use Closure;
use App\Restaurant;
class CheckOwner
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
        $restaurant = Restaurant::find($restaurant_id);
        if ($restaurant === null) {
            return redirect()->route('restaurant.index')->with('error', 'Not authorized!');
        }
        else if (!($restaurant->owner->id == $request->user()->id)) {
            return redirect()->route('restaurant.index')->with('error', 'Not authorized!');
        }
        return $next($request);
    }
}
