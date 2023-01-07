<?php

namespace App\Http\Middleware;

use Closure;
use Genocide\Radiocrud\Exceptions\CustomException;
use Illuminate\Http\Request;

class CheckIfStudentShouldChangePasswordMiddleware
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
        throw_if($request->user()->should_change_password, CustomException::class, 'you should change your password before accessing this part of api', '382308', 403);

        return $next($request);
    }
}
