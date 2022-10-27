<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class trailingSlashes
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!preg_match('/.+\/$/', $request->getRequestUri()))
        {            
            return redirect($request->getSchemeAndHttpHost().$request->getRequestUri().'/');
        }         
        return $next($request);
    }
}
