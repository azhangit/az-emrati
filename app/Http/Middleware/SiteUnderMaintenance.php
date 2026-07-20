<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SiteUnderMaintenance
{
    public function handle(Request $request, Closure $next)
    {
        if (!config('app.site_under_maintenance', false)) {
            return $next($request);
        }

        $redirectUrl = config('app.site_maintenance_redirect_url');

        if ($request->is('api/*')) {
            return response()->json([
                'result' => false,
                'status' => 'maintenance',
                'message' => 'Site is under maintenance',
                'redirect_url' => $redirectUrl,
            ], 503);
        }

        return redirect()->away($redirectUrl, 302);
    }
}
