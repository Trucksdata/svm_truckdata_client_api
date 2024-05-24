<?php

namespace App\Http\Middleware;

use App\Traits\ResponseTrait;
use Closure;
use Illuminate\Http\Request;

class IsSuperAdmin
{
    use ResponseTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponsee
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->user()->hasRole('super_admin')) {
            return $next($request);
        }
        return $this->errorResponse('This action is unauthorized.', 403);
    }
}
