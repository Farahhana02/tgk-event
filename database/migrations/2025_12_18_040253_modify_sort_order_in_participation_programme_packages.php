<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // First, update existing records to have sequential order
        DB::statement('
            UPDATE participation_programme_packages ppp
            JOIN (
                SELECT id, 
                       ROW_NUMBER() OVER (PARTITION BY programme_id ORDER BY created_at, id) as new_sort
                FROM participation_programme_packages
            ) as numbered
            ON ppp.id = numbered.id
            SET ppp.sort_order = numbered.new_sort
        ');
        
        // Then set default to auto-increment-like behavior
        Schema::table('participation_programme_packages', function (Blueprint $table) {
            $table->unsignedSmallInteger('sort_order')
                  ->default(1)
                  ->change();
        });
    }

    public function down(): void
    {
        // Revert if needed
        Schema::table('participation_programme_packages', function (Blueprint $table) {
            $table->unsignedSmallInteger('sort_order')
                  ->default(1)
                  ->change();
        });
    }
};