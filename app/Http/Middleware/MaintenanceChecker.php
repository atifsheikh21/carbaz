<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Modules\GeneralSetting\Entities\Setting;

class MaintenanceChecker
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $maintenance_mode = Setting::first();
        if($maintenance_mode && $maintenance_mode->maintenance_status == 1){
            return response()->view('maintenance_mode');
        }
        return $next($request);
    }
}
