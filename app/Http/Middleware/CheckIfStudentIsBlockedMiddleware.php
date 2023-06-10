<?php

namespace App\Http\Middleware;

use Closure;
use Genocide\Radiocrud\Exceptions\CustomException;
use Illuminate\Http\Request;

class CheckIfStudentIsBlockedMiddleware
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
        $user = $request->user();

        if ($user->is_block)
        {
            throw new CustomException('student is blocked', 556397, 403, ['block_reason' => $user->reason_for_blocking]);
        }

        return $next($request);
    }
}
