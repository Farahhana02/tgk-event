<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('programs', function (Blueprint $table) {
            // Add the column after 'id'
            $table->unsignedBigInteger('participation_programme_id')
                  ->nullable()
                  ->after('id');
            
            // Add foreign key constraint
            $table->foreign('participation_programme_id')
                  ->references('id')
                  ->on('participation_programmes')
                  ->onDelete('set null'); // If participation programme is deleted, just set this to NULL
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('programs', function (Blueprint $table) {
            $table->dropForeign(['participation_programme_id']);
            $table->dropColumn('participation_programme_id');
        });
    }
};