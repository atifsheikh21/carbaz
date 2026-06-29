<?php

use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;

/**
 * Read the EXIF orientation value from a JPEG file.
 * Works even when the PHP exif extension is not installed by falling back
 * to raw JPEG byte parsing.
 *
 * @return int  Orientation value 1-8 (1 = normal)
 */
function readJpegOrientation(string $filepath): int
{
    // Fast path: use the exif extension when available
    if (function_exists('exif_read_data')) {
        $exif = @exif_read_data($filepath);
        if ($exif && isset($exif['Orientation'])) {
            return (int) $exif['Orientation'];
        }
        return 1;
    }

    // Fallback: parse the raw JPEG bytes for the orientation tag
    $f = @fopen($filepath, 'rb');
    if (!$f) return 1;

    $soi = @fread($f, 2);
    if ($soi !== "\xFF\xD8") { fclose($f); return 1; }

    while (!feof($f)) {
        $marker = @fread($f, 2);
        if ($marker === false || strlen($marker) < 2 || $marker[0] !== "\xFF") break;

        $type = ord($marker[1]);

        // APP1 = EXIF
        if ($type === 0xE1) {
            $sizeData = @fread($f, 2);
            if ($sizeData === false || strlen($sizeData) < 2) break;
            $size = unpack('n', $sizeData)[1] - 2;
            $exifData = @fread($f, min($size, 65533));
            fclose($f);

            if (substr($exifData, 0, 6) !== "Exif\x00\x00") return 1;
            $tiff = substr($exifData, 6);
            if (strlen($tiff) < 8) return 1;

            $bo = substr($tiff, 0, 2);
            $le = ($bo === 'II');
            if (!$le && $bo !== 'MM') return 1;

            $ifdOff = unpack($le ? 'V' : 'N', substr($tiff, 4, 4))[1];
            if ($ifdOff + 2 > strlen($tiff)) return 1;

            $cnt = unpack($le ? 'v' : 'n', substr($tiff, $ifdOff, 2))[1];
            for ($i = 0; $i < $cnt; $i++) {
                $off = $ifdOff + 2 + ($i * 12);
                if ($off + 12 > strlen($tiff)) break;
                $tag = unpack($le ? 'v' : 'n', substr($tiff, $off, 2))[1];
                if ($tag === 0x0112) { // Orientation
                    return (int) unpack($le ? 'v' : 'n', substr($tiff, $off + 8, 2))[1];
                }
            }
            return 1;
        }

        // Skip other markers
        if ($type >= 0xE0 && $type <= 0xEF || $type === 0xFE) {
            $sizeData = @fread($f, 2);
            if ($sizeData === false || strlen($sizeData) < 2) break;
            $skip = unpack('n', $sizeData)[1] - 2;
            @fseek($f, $skip, SEEK_CUR);
        } else {
            break;
        }
    }

    fclose($f);
    return 1;
}

/**
 * Fix EXIF orientation on an Intervention Image instance.
 * Pass the original source file path so we can read the EXIF data.
 * Safe to call on any image — does nothing if orientation is already normal.
 * Uses only ONE rotation method to avoid the double-rotation bug.
 */
function fixImageOrientation($img, string $sourcePath)
{
    $orientation = readJpegOrientation($sourcePath);
    switch ($orientation) {
        case 3: $img->rotate(180); break;
        case 6: $img->rotate(-90); break;
        case 8: $img->rotate(90); break;
    }
    return $img;
}

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
        if ($imagePath) {
            $imagePath = trim($imagePath);
        }

        if ($imagePath && preg_match('~^https?://~i', $imagePath)) {
            return $imagePath;
        }

        if ($imagePath && env('FILESYSTEM_DISK') == 's3') {
            return Storage::disk('s3')->url($imagePath);
        }

        if ($imagePath && file_exists(public_path($imagePath))) {
            // Auto-convert existing webp source files to jpg for reliable display
            $sourceAbsPath = public_path($imagePath);
            $sourceExt = strtolower(pathinfo($sourceAbsPath, PATHINFO_EXTENSION));
            if ($sourceExt === 'webp') {
                try {
                    $jpgRelPath = preg_replace('/\.webp$/i', '.jpg', $imagePath);
                    $jpgAbsPath = public_path($jpgRelPath);
                    if (!file_exists($jpgAbsPath)) {
                        $manager = new ImageManager(['driver' => 'gd']);
                        $img = $manager->make($sourceAbsPath);
                        if (method_exists($img, 'orientate')) {
                            $img->orientate();
                        }
                        $img->encode('jpg', 90)->save($jpgAbsPath);
                    }
                    if (file_exists($jpgAbsPath)) {
                        $imagePath = $jpgRelPath;
                        $sourceAbsPath = $jpgAbsPath;
                    }
                } catch (\Throwable $e) {
                    // Fall through — use original webp
                }
            }

            if (preg_match('/^(\d+)x(\d+)$/', $size, $m)) {
                $w = (int) $m[1];
                $h = (int) $m[2];

                if ($w > 0 && $h > 0) {
                    $sourceMTime = @filemtime($sourceAbsPath) ?: '';
                    $hash = md5($imagePath . '|' . $size . '|' . $sourceMTime);

                    $thumbRelDir = 'uploads/thumbs/' . $size;
                    $thumbRelPath = $thumbRelDir . '/' . $hash . '.webp';
                    $thumbAbsDir = public_path($thumbRelDir);
                    $thumbAbsPath = public_path($thumbRelPath);

                    if (!file_exists($thumbAbsPath)) {
                        if (!is_dir($thumbAbsDir)) {
                            @mkdir($thumbAbsDir, 0755, true);
                        }

                        if (is_dir($thumbAbsDir) && is_writable($thumbAbsDir)) {
                            try {
                                $manager = new ImageManager(['driver' => 'gd']);
                                $img = $manager->make($sourceAbsPath);
                                if (method_exists($img, 'orientate')) {
                                    $img->orientate();
                                }
                                $img->fit($w, $h, function ($constraint) {
                                    $constraint->upsize();
                                });
                                $img->encode('webp', 75)->save($thumbAbsPath);
                            } catch (\Throwable $e) {
                                return asset($imagePath);
                            }
                        } else {
                            return asset($imagePath);
                        }
                    }

                    if (file_exists($thumbAbsPath)) {
                        return asset($thumbRelPath);
                    }
                }
            }

            return asset($imagePath);
        }

        // Path given but file not found locally — still return the asset URL
        // (file may be accessible via web server, symlinks, or CDN)
        if ($imagePath) {
            return asset($imagePath);
        }

        return "https://placehold.co/{$size}?text=Image+Coming+Soon";
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

        try {
            $ext = strtolower((string) $extension);

            // Convert webp to jpg so images display everywhere reliably
            if ($ext === 'webp') {
                $abs = public_path($file_path);
                if (file_exists($abs)) {
                    $manager = new ImageManager(['driver' => 'gd']);
                    $img = $manager->make($abs);
                    fixImageOrientation($img, $abs);
                    $img->resize(1905, 1080, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                    $jpg_name = pathinfo($file_name, PATHINFO_FILENAME) . '.jpg';
                    $jpg_path = $directory . '/' . $jpg_name;
                    $jpg_abs = public_path($jpg_path);
                    $img->encode('jpg', 85)->save($jpg_abs);
                    // Remove the original webp file and update path
                    if (file_exists($abs) && $abs !== $jpg_abs) {
                        @unlink($abs);
                    }
                    $file_path = $jpg_path;
                    $file_name = $jpg_name;
                }
            } elseif (in_array($ext, ['jpg', 'jpeg', 'png'], true)) {
                $abs = public_path($file_path);
                if (file_exists($abs)) {
                    $manager = new ImageManager(['driver' => 'gd']);
                    $img = $manager->make($abs);
                    fixImageOrientation($img, $abs);
                    $img->resize(1905, 1080, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                    $img->save($abs, 85);
                }
            }
        } catch (\Throwable $e) {
        }

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
