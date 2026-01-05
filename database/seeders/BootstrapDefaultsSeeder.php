<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class BootstrapDefaultsSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedSettings();
        $this->seedSeoSettings();
        $this->seedLanguages();
        $this->seedAboutUs();
        $this->seedAdmin();
        $this->seedAdsBanners();
        $this->seedOptionalTrackingTables();
    }

    private function seedAdmin(): void
    {
        if (!Schema::hasTable('admins')) {
            return;
        }

        // Create one super admin if no admin exists
        if (DB::table('admins')->exists()) {
            return;
        }

        $columns = Schema::getColumnListing('admins');
        $now = now();

        $row = [];
        if (in_array('name', $columns, true)) {
            $row['name'] = 'Admin';
        }
        if (in_array('email', $columns, true)) {
            $row['email'] = 'admin@local.test';
        }
        if (in_array('password', $columns, true)) {
            $row['password'] = Hash::make('admin1234');
        }
        if (in_array('status', $columns, true)) {
            $row['status'] = 'active';
        }
        if (in_array('admin_type', $columns, true)) {
            $row['admin_type'] = 1;
        }
        if (in_array('email_verified_at', $columns, true)) {
            $row['email_verified_at'] = $now;
        }
        if (in_array('created_at', $columns, true)) {
            $row['created_at'] = $now;
        }
        if (in_array('updated_at', $columns, true)) {
            $row['updated_at'] = $now;
        }

        DB::table('admins')->insert($row);
    }

    private function seedAboutUs(): void
    {
        if (!Schema::hasTable('about_us')) {
            return;
        }

        // Ensure a base about_us row exists
        if (!DB::table('about_us')->where('id', 1)->exists()) {
            $columns = Schema::getColumnListing('about_us');
            $now = now();

            $row = ['id' => 1];
            foreach (['about_image', 'ceo_avatar', 'ceo_signeture'] as $col) {
                if (in_array($col, $columns, true)) {
                    $row[$col] = null;
                }
            }
            if (in_array('created_at', $columns, true)) {
                $row['created_at'] = $now;
            }
            if (in_array('updated_at', $columns, true)) {
                $row['updated_at'] = $now;
            }

            DB::table('about_us')->insert($row);
        }

        // Ensure translations exist for at least en (and hi because SettingTranslationsSeeder uses it)
        if (!Schema::hasTable('about_us_translations')) {
            return;
        }

        $tColumns = Schema::getColumnListing('about_us_translations');
        $now = now();

        $translations = [
            'en' => [
                'header' => 'About Us',
                'title' => 'Learn more about Carbaz',
                'description' => 'Carbaz is a marketplace for buying and selling cars and parts.',
                'ceo_name' => 'Admin',
                'ceo_designation' => 'Founder',
            ],
            'hi' => [
                'header' => 'About Us',
                'title' => 'Learn more about Carbaz',
                'description' => 'Carbaz is a marketplace for buying and selling cars and parts.',
                'ceo_name' => 'Admin',
                'ceo_designation' => 'Founder',
            ],
        ];

        foreach ($translations as $langCode => $data) {
            $exists = DB::table('about_us_translations')
                ->where('about_us_id', 1)
                ->where('lang_code', $langCode)
                ->exists();

            if ($exists) {
                continue;
            }

            $row = ['about_us_id' => 1, 'lang_code' => $langCode];
            foreach ($data as $k => $v) {
                if (in_array($k, $tColumns, true)) {
                    $row[$k] = $v;
                }
            }
            // Optional columns that may exist in other versions
            foreach (['total_car', 'total_car_title', 'total_review', 'total_review_title'] as $col) {
                if (in_array($col, $tColumns, true) && !isset($row[$col])) {
                    $row[$col] = null;
                }
            }
            if (in_array('created_at', $tColumns, true)) {
                $row['created_at'] = $now;
            }
            if (in_array('updated_at', $tColumns, true)) {
                $row['updated_at'] = $now;
            }

            DB::table('about_us_translations')->insert($row);
        }
    }

    private function seedSettings(): void
    {
        if (!Schema::hasTable('settings')) {
            return;
        }

        $exists = DB::table('settings')->where('id', 1)->exists();
        if ($exists) {
            return;
        }

        $columns = Schema::getColumnListing('settings');
        $row = ['id' => 1];

        // Core columns (some are non-null in migrations)
        if (in_array('app_name', $columns, true)) {
            $row['app_name'] = 'Carbaz';
        }
        if (in_array('logo', $columns, true)) {
            $row['logo'] = '';
        }
        if (in_array('favicon', $columns, true)) {
            $row['favicon'] = '';
        }
        if (in_array('footer_logo', $columns, true)) {
            $row['footer_logo'] = '';
        }
        if (in_array('text_direction', $columns, true)) {
            $row['text_direction'] = 'LTR';
        }
        if (in_array('timezone', $columns, true)) {
            $row['timezone'] = 'UTC';
        }
        if (in_array('default_avatar', $columns, true)) {
            $row['default_avatar'] = '';
        }
        if (in_array('selected_theme', $columns, true)) {
            $row['selected_theme'] = 'theme_one';
        }
        if (in_array('app_version', $columns, true)) {
            $row['app_version'] = 'Version - 1.1';
        }

        // Common optional columns (safe defaults)
        foreach (['email' => 'info@carbaz.com', 'phone' => '+1234567890', 'address' => '123 Main Street', 'copyright' => 'Copyright ' . date('Y') . ' Carbaz. All Rights Reserved.'] as $col => $val) {
            if (in_array($col, $columns, true)) {
                $row[$col] = $val;
            }
        }

        foreach (['twitter', 'instagram', 'linkedin', 'facebook', 'behance'] as $col) {
            if (in_array($col, $columns, true)) {
                $row[$col] = '#';
            }
        }

        if (in_array('add_listing', $columns, true)) {
            $row['add_listing'] = 'enable';
        }

        $now = now();
        if (in_array('created_at', $columns, true)) {
            $row['created_at'] = $now;
        }
        if (in_array('updated_at', $columns, true)) {
            $row['updated_at'] = $now;
        }

        DB::table('settings')->insert($row);
    }

    private function seedSeoSettings(): void
    {
        if (!Schema::hasTable('seo_settings')) {
            return;
        }

        $columns = Schema::getColumnListing('seo_settings');
        $now = now();

        // The code uses hard-coded IDs (HomeController)
        $defaults = [
            1 => ['page_name' => 'home', 'seo_title' => 'Carbaz', 'seo_description' => 'Car marketplace'],
            2 => ['page_name' => 'blogs', 'seo_title' => 'Blogs', 'seo_description' => 'Latest news and articles'],
            3 => ['page_name' => 'about-us', 'seo_title' => 'About Us', 'seo_description' => 'Learn more about us'],
            4 => ['page_name' => 'contact-us', 'seo_title' => 'Contact Us', 'seo_description' => 'Get in touch'],
            5 => ['page_name' => 'faq', 'seo_title' => 'FAQ', 'seo_description' => 'Frequently asked questions'],
            6 => ['page_name' => 'terms-conditions', 'seo_title' => 'Terms & Conditions', 'seo_description' => 'Terms and conditions'],
            7 => ['page_name' => 'privacy-policy', 'seo_title' => 'Privacy Policy', 'seo_description' => 'Privacy policy'],
            10 => ['page_name' => 'listing', 'seo_title' => 'Car Listing', 'seo_description' => 'Browse cars for sale'],
            11 => ['page_name' => 'dealers', 'seo_title' => 'Dealers', 'seo_description' => 'Browse dealers'],
        ];

        foreach ($defaults as $id => $data) {
            $exists = DB::table('seo_settings')->where('id', $id)->exists();
            if ($exists) {
                continue;
            }

            $row = array_merge(['id' => $id], $data);
            if (in_array('seo_keyword', $columns, true) && !isset($row['seo_keyword'])) {
                $row['seo_keyword'] = null;
            }
            if (in_array('created_at', $columns, true)) {
                $row['created_at'] = $now;
            }
            if (in_array('updated_at', $columns, true)) {
                $row['updated_at'] = $now;
            }

            DB::table('seo_settings')->insert($row);
        }
    }

    private function seedLanguages(): void
    {
        if (!Schema::hasTable('languages')) {
            return;
        }

        $hasDefault = DB::table('languages')->where('is_default', 'Yes')->exists();
        if ($hasDefault) {
            return;
        }

        $columns = Schema::getColumnListing('languages');
        $now = now();

        $row = [];
        if (in_array('lang_name', $columns, true)) {
            $row['lang_name'] = 'English';
        }
        if (in_array('lang_code', $columns, true)) {
            $row['lang_code'] = 'en';
        }
        if (in_array('is_default', $columns, true)) {
            $row['is_default'] = 'Yes';
        }
        if (in_array('status', $columns, true)) {
            $row['status'] = 1;
        }
        if (in_array('lang_direction', $columns, true)) {
            $row['lang_direction'] = 'left_to_right';
        }
        if (in_array('created_at', $columns, true)) {
            $row['created_at'] = $now;
        }
        if (in_array('updated_at', $columns, true)) {
            $row['updated_at'] = $now;
        }

        DB::table('languages')->insert($row);
    }

    private function seedAdsBanners(): void
    {
        if (!Schema::hasTable('ads_banners')) {
            return;
        }

        $needed = [
            ['position_key' => 'listing_page_sidebar', 'position' => 'Listing Page Sidebar'],
            ['position_key' => 'home_page_ads', 'position' => 'Home Page Ads'],
        ];

        $columns = Schema::getColumnListing('ads_banners');
        $now = now();

        foreach ($needed as $item) {
            $exists = DB::table('ads_banners')->where('position_key', $item['position_key'])->exists();
            if ($exists) {
                continue;
            }

            $row = [
                'position' => $item['position'],
                'position_key' => $item['position_key'],
                'image' => '',
                'link' => '#',
                'status' => 'disable',
            ];

            if (in_array('created_at', $columns, true)) {
                $row['created_at'] = $now;
            }
            if (in_array('updated_at', $columns, true)) {
                $row['updated_at'] = $now;
            }

            DB::table('ads_banners')->insert($row);
        }
    }

    private function seedOptionalTrackingTables(): void
    {
        $now = now();

        // These tables were missing in your DB earlier and were added manually.
        // Seed a default row so ::first() won't return null.
        $singletons = [
            'google_recaptchas' => ['site_key' => null, 'secret_key' => null, 'recaptcha_status' => 0],
            'google_analytics' => ['tracking_id' => null, 'analytics_status' => 0],
            'facebook_pixels' => ['pixel_id' => null, 'pixel_status' => 0],
            'tawk_chats' => ['property_id' => null, 'widget_id' => null, 'tawk_chat_status' => 0],
            'cookie_consents' => ['status' => 0, 'message' => 'We use cookies to improve your experience.'],
        ];

        foreach ($singletons as $table => $data) {
            if (!Schema::hasTable($table)) {
                continue;
            }

            if (DB::table($table)->exists()) {
                continue;
            }

            $columns = Schema::getColumnListing($table);
            $row = [];
            foreach ($data as $k => $v) {
                if (in_array($k, $columns, true)) {
                    $row[$k] = $v;
                }
            }

            if (in_array('created_at', $columns, true)) {
                $row['created_at'] = $now;
            }
            if (in_array('updated_at', $columns, true)) {
                $row['updated_at'] = $now;
            }

            DB::table($table)->insert($row);
        }
    }
}
