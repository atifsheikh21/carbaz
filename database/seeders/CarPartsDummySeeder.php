<?php

namespace Database\Seeders;

use App\Models\CarPart;
use App\Models\CarPartTranslation;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Brand\Entities\Brand;

class CarPartsDummySeeder extends Seeder
{
    public function run(): void
    {
        if (DB::table('car_parts')->count() > 0) {
            return;
        }

        $userId = User::query()->value('id');
        if (!$userId) {
            return;
        }

        $brandId = Brand::query()->value('id');

        $langCodes = [];
        if (DB::getSchemaBuilder()->hasTable('languages')) {
            $langCodes = DB::table('languages')->pluck('lang_code')->filter()->values()->all();
        }
        if (count($langCodes) === 0) {
            $langCodes = ['en'];
        }

        $items = [
            ['title' => 'Brake Pads (Front Set)', 'condition' => 'New', 'regular_price' => 89, 'offer_price' => 69, 'part_number' => 'BP-001', 'compatibility' => 'Toyota / Honda'],
            ['title' => 'Oil Filter', 'condition' => 'New', 'regular_price' => 15, 'offer_price' => null, 'part_number' => 'OF-110', 'compatibility' => 'Most models'],
            ['title' => 'Headlight Assembly (Left)', 'condition' => 'Used', 'regular_price' => 140, 'offer_price' => 110, 'part_number' => 'HL-L-200', 'compatibility' => 'Nissan Altima'],
            ['title' => 'Air Filter', 'condition' => 'New', 'regular_price' => 18, 'offer_price' => null, 'part_number' => 'AF-220', 'compatibility' => 'Most models'],
            ['title' => 'Spark Plug Set', 'condition' => 'New', 'regular_price' => 45, 'offer_price' => 39, 'part_number' => 'SP-4PK', 'compatibility' => 'Petrol engines'],
            ['title' => 'Side Mirror (Right)', 'condition' => 'Used', 'regular_price' => 75, 'offer_price' => null, 'part_number' => 'SM-R-101', 'compatibility' => 'Ford Focus'],
            ['title' => 'Radiator', 'condition' => 'New', 'regular_price' => 220, 'offer_price' => 199, 'part_number' => 'RD-330', 'compatibility' => 'Hyundai'],
            ['title' => 'Car Battery 12V', 'condition' => 'New', 'regular_price' => 130, 'offer_price' => 115, 'part_number' => 'BT-12V', 'compatibility' => 'Universal'],
        ];

        foreach ($items as $item) {
            $slug = Str::slug($item['title']) . '-' . Str::lower(Str::random(6));

            $carPart = new CarPart();
            $carPart->agent_id = $userId;
            $carPart->brand_id = $brandId;
            $carPart->slug = $slug;
            $carPart->condition = $item['condition'];
            $carPart->regular_price = $item['regular_price'];
            $carPart->offer_price = $item['offer_price'];
            $carPart->part_number = $item['part_number'];
            $carPart->compatibility = $item['compatibility'];
            $carPart->thumb_image = null;
            $carPart->status = 'enable';
            $carPart->approved_by_admin = 'approved';
            $carPart->save();

            foreach ($langCodes as $lang) {
                $t = new CarPartTranslation();
                $t->car_part_id = $carPart->id;
                $t->lang_code = $lang;
                $t->title = $item['title'];
                $t->description = 'Demo listing for ' . $item['title'] . '.';
                $t->seo_title = $item['title'];
                $t->seo_description = $item['title'];
                $t->save();
            }
        }
    }
}
