<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingTranslationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('setting_translations')->insert([
            [
                'setting_id' => 1, // ID of the setting in `settings` table
                'lang_code' => 'en',
                'about_us' => 'There are many variations of passages of Lorem Ipsum a majority have suffered alteration in some form, by injecte randomised words which.',
                'address' => 'Bazar West, Panthapath North, Dhaka 1215',
                'copyright' => 'Copyright 2024 QuomodoSoft. All Rights Reserved.',
            ],
            [
                'setting_id' => 1,
                'lang_code' => 'hi',
                'about_us' => 'There are many variations of passages of Lorem Ipsum a majority have suffered alteration in some form, by injecte randomised words which.',
                'address' => 'Bazar West, Panthapath North, Dhaka 1215',
                'copyright' => 'Copyright 2024 QuomodoSoft. All Rights Reserved.',
            ],
            // Add more if needed
        ]);
    }
}
