<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SiteUnderMaintenance
{
    /**
     * Paths that stay reachable while the storefront is locked.
     */
    protected $except = [
        'assets/*',
        'public/assets/*',
        'uploads/*',
        'storage/*',
        'favicon.ico',
    ];

    public function handle(Request $request, Closure $next)
    {
        if (!config('app.site_under_maintenance', false)) {
            return $next($request);
        }

        if ($this->inExceptArray($request)) {
            return $next($request);
        }

        if ($request->is('/')) {
            return response()->view('coming-soon');
        }

        if ($request->is('api/*')) {
            return response()->json([
                'result' => false,
                'status' => 'maintenance',
                'message' => 'Site is under maintenance',
            ], 503);
        }

        return redirect('/');
    }

    protected function inExceptArray(Request $request): bool
    {
        foreach ($this->except as $except) {
            if ($request->is($except)) {
                return true;
            }
        }

        return false;
    }
}
