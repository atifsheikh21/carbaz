<?php

use Illuminate\Support\Facades\Storage;

function html_decode($text){
    $after_decode =  htmlspecialchars_decode($text, ENT_QUOTES);
    return $after_decode;
}

function admin_lang(){
    return Session::get('admin_lang');
}

function front_lang(){
    return Session::get('front_lang');
}

function amount($amount, $decimals = 2, $decimalSeparator = '.', $thousandsSeparator = ',') {
    $amount = number_format($amount, $decimals, $decimalSeparator, $thousandsSeparator);

    return $amount;
}

function calculate_percentage($regular_price, $offer_price){

    $offer = (($regular_price - $offer_price) / $regular_price) * 100;
    $offer = round($offer, 2);
    return $offer;

}


function currency($price){
    // currency information will be loaded by Session value

    $currency_icon = Session::get('currency_icon');
    $currency_code = Session::get('currency_code');
    $currency_rate = Session::get('currency_rate');
    $currency_position = Session::get('currency_position');

    $price = $price * $currency_rate;
    $price = amount($price, 2, '.', ',');

    if($currency_position == 'before_price'){
        $price = $currency_icon.$price;
    }elseif($currency_position == 'before_price_with_space'){
        $price = $currency_icon.' '.$price;
    }elseif($currency_position == 'after_price'){
        $price = $price.$currency_icon;
    }elseif($currency_position == 'after_price_with_space'){
        $price = $price.' '.$currency_icon;
    }else{
        $price = $currency_icon.$price;
    }

    return $price;
}

function currency_ie_plain($price){
    $currency_rate = Session::get('currency_rate');

    if (!$currency_rate) {
        $currency_rate = 1;
    }

    $price = $price * $currency_rate;
    $price = (float) $price;

    return number_format(round($price), 0, '', '');
}

function currency_without_symbol($price){
    $currency_rate = Session::get('currency_rate');

    if (!$currency_rate) {
        $currency_rate = 1;
    }

    $price = $price * $currency_rate;
    $price = amount($price, 2, '.', ',');

    return $price;
}


function getAllResourceFiles($dir, &$results = array()) {
    $files = scandir($dir);
    foreach ($files as $key => $value) {
        $path = $dir ."/". $value;
        if (!is_dir($path)) {
            $results[] = $path;
        } else if ($value != "." && $value != "..") {
            getAllResourceFiles($path, $results);
        }
    }
    return $results;
}

function getRegexBetween($content) {

    preg_match_all("%\{{ __\(['|\"](.*?)['\"]\) }}%i", $content, $matches1, PREG_PATTERN_ORDER);
    preg_match_all("%\@lang\(['|\"](.*?)['\"]\)%i", $content, $matches2, PREG_PATTERN_ORDER);
    preg_match_all("%trans\(['|\"](.*?)['\"]\)%i", $content, $matches3, PREG_PATTERN_ORDER);
    $Alldata = [$matches1[1], $matches2[1], $matches3[1]];
    $data = [];
    foreach ($Alldata as  $value) {
        if(!empty($value)){
            foreach ($value as $val) {
                $data[$val] = $val;
            }
        }
    }
    return $data;
}

function generateLang($path = ''){

    // user panel
    $paths = getAllResourceFiles(resource_path('views'));

    $paths = array_merge($paths, getAllResourceFiles(app_path()));

    $paths = array_merge($paths, getAllResourceFiles(base_path('Modules')));

    // end user panel

    // user validation
    $paths = getAllResourceFiles(app_path());

    $paths = array_merge($paths, getAllResourceFiles(app_path('Http/Controllers/test')));
    $paths = array_merge($paths, getAllResourceFiles(app_path('Http/Controllers/Auth')));
    // end user validation

    // admin panel
    $paths = getAllResourceFiles(resource_path('views/admin'));
    // end admin panel

    // admin validation
    $paths = getAllResourceFiles(app_path('Http/Controllers/Admin'));
    // end validation
    $AllData= [];
    foreach ($paths as $key => $path) {
    $AllData[] = getRegexBetween(file_get_contents($path));
    }
    $modifiedData = [];
    foreach ($AllData as  $value) {
        if(!empty($value)){
            foreach ($value as $val) {
                $modifiedData[$val] = $val;
            }
        }
    }

    $modifiedData = var_export($modifiedData, true);

    file_put_contents('lang/en/translate.php', "<?php\n return {$modifiedData};\n ?>");

}


if (!function_exists('getImageOrPlaceholder')) {
    function getImageOrPlaceholder(?string $imagePath, string $size = '800x600'): string
    {
        if ($imagePath && env('FILESYSTEM_DISK') == 's3') {
            return Storage::disk('s3')->url($imagePath);
        }

        if ($imagePath && file_exists(public_path($imagePath))) {
            return asset($imagePath);
        }

        return "https://placehold.co/{$size}?text={$size}";
    }
}


function uploadFile($file, $directory, $old_file = null){
    // Generate a unique name for the file
    $extension = $file->getClientOriginalExtension();
    $file_name = 'file-name-' . time() . rand(1000, 9999) . '.' . $extension;
    $file_path = $directory . '/' . $file_name;
    if(env('FILESYSTEM_DISK') == 's3'){
        $result = Storage::disk('s3')->put($directory, $file);
        // $result = Storage::disk('s3')->put($directory . '/' . $file_name, $file);
        Log::info(Storage::disk('s3')->url($result));
        $file_path = $result;
        if ($old_file) {
            Storage::disk('s3')->delete($old_file);
        }
    }else{
        // Local storage
        $destinationPath = public_path($directory);
        if (!is_dir($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        if (!is_dir($destinationPath) || !is_writable($destinationPath)) {
            throw new RuntimeException('Upload directory is not writable: ' . $destinationPath);
        }

        $file->move($destinationPath, $file_name);
        // Update the file path to match the local storage path
        $file_path = $directory . '/' . $file_name;
        // Delete the old file from local storage if it exists
        if ($old_file && file_exists(public_path($old_file))) {
            unlink(public_path($old_file));
        }
    }
    return $file_path;
}

function deleteFile($old_file){
    if(env('FILESYSTEM_DISK') == 's3'){
        if ($old_file) {
            Storage::disk('s3')->delete($old_file);
        }
    }else{
        if ($old_file && file_exists(public_path($old_file))) {
            unlink(public_path($old_file));
        }
    }
}
