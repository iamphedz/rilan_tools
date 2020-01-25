<?php

namespace App\Http\Middleware;

use App\Http\CustomClass\Cart;
use Closure;

class CartSession
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
        session_start();
        Cart::setCart();
        return $next($request);
    }
}
