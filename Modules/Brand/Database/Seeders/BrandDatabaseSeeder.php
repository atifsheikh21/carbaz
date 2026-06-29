<?php

namespace Modules\Brand\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Brand\Entities\Brand;
use Modules\Brand\Entities\BrandTranslation;

class BrandDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $langCode = (string) (config('app.locale') ?: 'en');
        $defaultImage = 'uploads/brand/default.png';

        $brands = [
            'Abarth',
            'Alfa Romeo',
            'Aston Martin',
            'Audi',
            'Bentley',
            'BMW',
            'BYD',
            'Cadillac',
            'Chery',
            'Chevrolet',
            'Chrysler',
            'Citroen',
            'Cupra',
            'Dacia',
            'Daewoo',
            'Daihatsu',
            'Dodge',
            'DS',
            'Ferrari',
            'Fiat',
            'Ford',
            'GWM',
            'Honda',
            'Hummer',
            'Hyundai',
            'Infiniti',
            'Isuzu',
            'Jaguar',
            'Jeep',
            'Kia',
            'Land Rover',
            'Lexus',
            'Lotus',
            'Maserati',
            'Mazda',
            'McLaren',
            'Mercedes-Benz',
            'MG',
            'MINI',
            'Mitsubishi',
            'Nissan',
            'Opel',
            'Peugeot',
            'Polestar',
            'Porsche',
            'Renault',
            'Rolls-Royce',
            'Rover',
            'Saab',
            'SEAT',
            'Skoda',
            'Smart',
            'Subaru',
            'Suzuki',
            'Tesla',
            'Toyota',
            'Vauxhall',
            'Volkswagen',
            'Volvo',
        ];

        DB::transaction(function () use ($brands, $langCode, $defaultImage) {
            foreach ($brands as $name) {
                $name = trim((string) $name);
                if ($name === '') {
                    continue;
                }

                $slug = Str::slug($name);
                if ($slug === '') {
                    continue;
                }

                $brand = Brand::query()->where('slug', $slug)->first();
                if (!$brand) {
                    $brand = new Brand();
                    $brand->slug = $slug;
                    $brand->image = $defaultImage;
                }

                $brand->status = 'enable';
                $brand->save();

                $translation = BrandTranslation::query()
                    ->where('brand_id', $brand->id)
                    ->where('lang_code', $langCode)
                    ->first();

                if (!$translation) {
                    $translation = new BrandTranslation();
                    $translation->brand_id = $brand->id;
                    $translation->lang_code = $langCode;
                }

                $translation->name = $name;
                $translation->save();
            }
        });

    }
}
