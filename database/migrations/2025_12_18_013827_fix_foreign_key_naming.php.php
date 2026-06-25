
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Fix participation_submissions foreign key constraint name
        try {
            DB::statement('ALTER TABLE participation_submissions DROP FOREIGN KEY IF EXISTS participation_submissions_programme_payment_method_id_foreign');
        } catch (\Exception $e) {
            // Ignore if doesn't exist
        }

        // Re-add with proper name
        Schema::table('participation_submissions', function (Blueprint $table) {
            $table->foreign('programme_payment_method_id', 'fk_part_sub_prog_pay_method')
                  ->references('id')->on('programme_payment_methods')
                  ->restrictOnDelete();
        });

        // 2. Fix participation_participants foreign key
        try {
            DB::statement('ALTER TABLE participation_participants DROP FOREIGN KEY IF EXISTS participation_participants_submission_id_foreign');
        } catch (\Exception $e) {
            // Ignore if doesn't exist
        }

        Schema::table('participation_participants', function (Blueprint $table) {
            $table->foreign('submission_id', 'fk_part_part_submission')
                  ->references('id')->on('participation_submissions')
                  ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        // Restore original foreign key names (optional)
        try {
            DB::statement('ALTER TABLE participation_submissions DROP FOREIGN KEY fk_part_sub_prog_pay_method');
        } catch (\Exception $e) {
            // Ignore
        }

        try {
            DB::statement('ALTER TABLE participation_participants DROP FOREIGN KEY fk_part_part_submission');
        } catch (\Exception $e) {
            // Ignore
        }

        // You may want to restore original constraints here if needed
    }
};