<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class IrelandLocationsSeeder extends Seeder
{
    public function run(): void
    {
        if (!Schema::hasTable('countries') || !Schema::hasTable('cities') || !Schema::hasTable('city_translations')) {
            return;
        }

        $now = now();

        $ireland = DB::table('countries')
            ->where('code', 'IE')
            ->orWhere('name', 'Ireland')
            ->first();

        if (!$ireland) {
            $irelandId = DB::table('countries')->insertGetId([
                'name' => 'Ireland',
                'code' => 'IE',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            $ireland = (object) ['id' => $irelandId, 'name' => 'Ireland', 'code' => 'IE'];
        }

        $langCodes = ['en'];
        if (Schema::hasTable('languages')) {
            $langCodes = DB::table('languages')->pluck('lang_code')->filter()->values()->all();
            if (empty($langCodes)) {
                $langCodes = ['en'];
            }
        }

        $cities = [
            'Dublin',
            'Cork',
            'Galway',
            'Limerick',
            'Waterford',
            'Kilkenny',
            'Wexford',
            'Sligo',
            'Donegal',
            'Kerry',
            'Mayo',
            'Clare',
            'Kildare',
            'Meath',
            'Wicklow',
            'Offaly',
            'Laois',
            'Carlow',
            'Longford',
            'Louth',
            'Monaghan',
            'Roscommon',
            'Tipperary',
            'Westmeath',
            'Cavan',
            'Leitrim',
        ];

        foreach ($cities as $cityName) {
            $existingCityId = DB::table('city_translations')
                ->join('cities', 'cities.id', '=', 'city_translations.city_id')
                ->where('cities.country_id', $ireland->id)
                ->where('city_translations.lang_code', $langCodes[0])
                ->where('city_translations.name', $cityName)
                ->value('cities.id');

            $cityId = $existingCityId;

            if (!$cityId) {
                $cityId = DB::table('cities')->insertGetId([
                    'country_id' => $ireland->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            foreach ($langCodes as $lang) {
                $existsTranslation = DB::table('city_translations')
                    ->where('city_id', $cityId)
                    ->where('lang_code', $lang)
                    ->exists();

                if ($existsTranslation) {
                    continue;
                }

                DB::table('city_translations')->insert([
                    'city_id' => $cityId,
                    'lang_code' => $lang,
                    'name' => $cityName,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }
}
