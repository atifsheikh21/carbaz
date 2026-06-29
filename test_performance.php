<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$kernel->bootstrap();

echo "=== Performance Test Script ===\n\n";

// Test 1: Database Connection
echo "1. Testing Database Connection...\n";
try {
    $start = microtime(true);
    \Illuminate\Support\Facades\DB::connection()->getPdo();
    $dbTime = (microtime(true) - $start) * 1000;
    echo "   ✓ Database connected in " . number_format($dbTime, 2) . "ms\n";
} catch (Exception $e) {
    echo "   ✗ Database connection failed: " . $e->getMessage() . "\n";
}

// Test 2: Cache Connection
echo "\n2. Testing Cache Connection...\n";
try {
    $start = microtime(true);
    \Illuminate\Support\Facades\Cache::put('performance_test', 'test_value', 60);
    $cacheTime = (microtime(true) - $start) * 1000;
    echo "   ✓ Cache write in " . number_format($cacheTime, 2) . "ms\n";
    
    $start = microtime(true);
    $value = \Illuminate\Support\Facades\Cache::get('performance_test');
    $cacheReadTime = (microtime(true) - $start) * 1000;
    echo "   ✓ Cache read in " . number_format($cacheReadTime, 2) . "ms\n";
    
    \Illuminate\Support\Facades\Cache::forget('performance_test');
} catch (Exception $e) {
    echo "   ✗ Cache connection failed: " . $e->getMessage() . "\n";
}

// Test 3: Home Page Performance
echo "\n3. Testing Home Page Performance...\n";
try {
    $start = microtime(true);
    
    // Simulate HomeController index method key queries
    $setting = \Illuminate\Support\Facades\Cache::remember('theme_setting', 300, function () {
        return \Modules\GeneralSetting\Entities\Setting::select('selected_theme')->first();
    });
    
    $brands = \Illuminate\Support\Facades\Cache::remember('home.index.theme_one.en.brands', 3600, function () {
        return \Modules\Brand\Entities\Brand::where('status', 'enable')->select('id', 'name', 'logo', 'status')->get();
    });
    
    $homeTime = (microtime(true) - $start) * 1000;
    echo "   ✓ Home page data loaded in " . number_format($homeTime, 2) . "ms\n";
    echo "   - Brands count: " . $brands->count() . "\n";
    echo "   - Theme: " . ($setting->selected_theme ?? 'default') . "\n";
} catch (Exception $e) {
    echo "   ✗ Home page test failed: " . $e->getMessage() . "\n";
}

// Test 4: Memory Usage
echo "\n4. Memory Usage Analysis...\n";
$memoryUsage = memory_get_usage(true);
$peakMemory = memory_get_peak_usage(true);
echo "   Current memory: " . number_format($memoryUsage / 1024 / 1024, 2) . "MB\n";
echo "   Peak memory: " . number_format($peakMemory / 1024 / 1024, 2) . "MB\n";

// Test 5: Query Performance
echo "\n5. Query Performance Test...\n";
try {
    \Illuminate\Support\Facades\DB::enableQueryLog();
    
    $start = microtime(true);
    $cars = \Modules\Car\Entities\Car::with(['dealer:id,name,username,image', 'brand:id,name,logo'])
        ->where(function ($query) {
            $query->whereNull('expired_date')
                ->orWhere('expired_date', '>=', now()->format('Y-m-d'));
        })
        ->where(['status' => 'enable', 'approved_by_admin' => 'approved'])
        ->select('id', 'title', 'slug', 'price', 'thumbnail_image', 'brand_id', 'dealer_id', 'condition', 'mileage', 'year', 'created_at')
        ->take(8)
        ->latest()
        ->get();
    
    $queryTime = (microtime(true) - $start) * 1000;
    $queries = \Illuminate\Support\Facades\DB::getQueryLog();
    
    echo "   ✓ Car query executed in " . number_format($queryTime, 2) . "ms\n";
    echo "   - Total queries: " . count($queries) . "\n";
    echo "   - Cars found: " . $cars->count() . "\n";
    
    $totalQueryTime = array_sum(array_column($queries, 'time'));
    echo "   - Total DB time: " . number_format($totalQueryTime, 2) . "ms\n";
    
    \Illuminate\Support\Facades\DB::disableQueryLog();
} catch (Exception $e) {
    echo "   ✗ Query test failed: " . $e->getMessage() . "\n";
}

echo "\n=== Performance Test Complete ===\n";
echo "Next steps:\n";
echo "1. Ensure Redis is running for optimal performance\n";
echo "2. Run the database migration for indexes\n";
echo "3. Monitor production performance regularly\n";
