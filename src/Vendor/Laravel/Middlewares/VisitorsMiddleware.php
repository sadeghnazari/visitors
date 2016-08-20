<?php

namespace Blanfordia\Visitors\Vendor\Laravel\Middlewares;

use Closure;

class VisitorsMiddleware
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
        app('visitors')->boot();
        return $next($request);
    }
}
