<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('contact_messages') || ! Schema::hasColumn('contact_messages', 'id')) {
            return;
        }

        $idColumn = collect(DB::select("SHOW COLUMNS FROM contact_messages LIKE 'id'"))->first();

        if ($idColumn && str_contains(strtolower((string) $idColumn->Extra), 'auto_increment')) {
            return;
        }

        $primaryIndexes = DB::select("SHOW INDEX FROM contact_messages WHERE Key_name = 'PRIMARY'");

        if (! empty($primaryIndexes)) {
            DB::statement('ALTER TABLE contact_messages DROP PRIMARY KEY');
        }

        DB::statement('ALTER TABLE contact_messages ADD COLUMN contact_message_auto_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST');
        DB::statement('ALTER TABLE contact_messages DROP COLUMN id');
        DB::statement('ALTER TABLE contact_messages CHANGE contact_message_auto_id id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
    }

    public function down(): void
    {
        if (! Schema::hasTable('contact_messages') || ! Schema::hasColumn('contact_messages', 'id')) {
            return;
        }

        DB::statement('ALTER TABLE contact_messages MODIFY id BIGINT UNSIGNED NOT NULL');
    }
};
