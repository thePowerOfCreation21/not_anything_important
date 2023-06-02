<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RemoveEmptyDateForUpdate
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
        if ($request->getMethod() == 'PUT' && $request->exists('date') && (empty($request->get('date')) || $request->get('date') == 'null'))
            $request->request->remove('date');
        return $next($request);
    }
}
