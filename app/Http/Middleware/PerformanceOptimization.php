<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PerformanceOptimization
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
        // Start timing
        $startTime = microtime(true);
        
        // Enable query logging for debugging in development
        if (config('app.env') === 'local') {
            DB::enableQueryLog();
        }
        
        // Set memory limit
        ini_set('memory_limit', '512M');
        
        // Set execution time limit
        set_time_limit(60);
        
        $response = $next($request);
        
        // Calculate execution time
        $executionTime = microtime(true) - $startTime;
        
        // Log slow queries
        if (config('app.env') === 'local' && $executionTime > 2.0) {
            $queries = DB::getQueryLog();
            Log::warning('Slow request detected', [
                'url' => $request->fullUrl(),
                'execution_time' => $executionTime,
                'query_count' => count($queries),
                'memory_usage' => memory_get_peak_usage(true),
            ]);
            
            // Log slow queries
            foreach ($queries as $query) {
                if ($query['time'] > 100) {
                    Log::warning('Slow query', [
                        'sql' => $query['query'],
                        'time' => $query['time'],
                        'bindings' => $query['bindings'],
                    ]);
                }
            }
        }
        
        // Add performance headers
        $response->headers->set('X-Execution-Time', number_format($executionTime * 1000, 2) . 'ms');
        $response->headers->set('X-Memory-Usage', number_format(memory_get_peak_usage(true) / 1024 / 1024, 2) . 'MB');
        
        return $response;
    }
}
