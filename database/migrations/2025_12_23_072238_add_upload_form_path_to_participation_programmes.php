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
        Schema::table('participation_programmes', function (Blueprint $table) {
            $table->string('upload_form_path')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('participation_programmes', function (Blueprint $table) {
            $table->dropColumn('upload_form_path');
        });
    }
};