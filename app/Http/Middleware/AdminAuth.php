<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = 'admin')
    {
        if (Auth::guard($guard)->guest()) {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest('admin/login');
            }
        } else {
            $blackList = [
                'admin/login',
                'admin/logout'
            ];

            $path = preg_replace('/(\d+)/', '{id}', $request->path());

            if (Auth::guard($guard)->id() != 1) {
                if (!in_array($request->path(), $blackList)) {
                    if (!Auth::guard($guard)->user()->hasRole('超级管理员')) {
                        if (!Auth::guard($guard)->user()->can($path)) {
                            if ($request->ajax()) {
                                return response('Unauthorized.', 403);
                            } else {
                                abort(403);
                            }
                        }
                    }
                }
            }
        }

        return $next($request);
    }
}
