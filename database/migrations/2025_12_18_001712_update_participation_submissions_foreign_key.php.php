<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Step 1: Drop old foreign key if it exists
        try {
            DB::statement('ALTER TABLE participation_submissions DROP FOREIGN KEY participation_submissions_programme_package_id_foreign');
        } catch (\Exception $e) {
            // Foreign key doesn't exist, that's okay
        }

        // Step 2: Check if column needs renaming
        if (Schema::hasColumn('participation_submissions', 'programme_package_id')) {
            DB::statement('ALTER TABLE participation_submissions CHANGE programme_package_id participation_programme_package_id BIGINT UNSIGNED NOT NULL');
        } elseif (!Schema::hasColumn('participation_submissions', 'participation_programme_package_id')) {
            // Column doesn't exist at all, create it
            Schema::table('participation_submissions', function (Blueprint $table) {
                $table->unsignedBigInteger('participation_programme_package_id')->after('phone_number');
            });
        }

        // Step 3: Add new foreign key with short name
        DB::statement('ALTER TABLE participation_submissions 
            ADD CONSTRAINT fk_part_sub_prog_pkg 
            FOREIGN KEY (participation_programme_package_id) 
            REFERENCES participation_programme_packages(id) 
            ON DELETE RESTRICT');
    }

    public function down(): void
    {
        // Drop foreign key
        try {
            DB::statement('ALTER TABLE participation_submissions DROP FOREIGN KEY fk_part_sub_prog_pkg');
        } catch (\Exception $e) {
            // Doesn't exist, okay
        }

        // Rename back
        if (Schema::hasColumn('participation_submissions', 'participation_programme_package_id')) {
            DB::statement('ALTER TABLE participation_submissions CHANGE participation_programme_package_id programme_package_id BIGINT UNSIGNED NOT NULL');
        }

        // Restore old foreign key
        try {
            DB::statement('ALTER TABLE participation_submissions 
                ADD CONSTRAINT participation_submissions_programme_package_id_foreign 
                FOREIGN KEY (programme_package_id) 
                REFERENCES programme_packages(id) 
                ON DELETE RESTRICT');
        } catch (\Exception $e) {
            // Can't restore, that's okay
        }
    }
};