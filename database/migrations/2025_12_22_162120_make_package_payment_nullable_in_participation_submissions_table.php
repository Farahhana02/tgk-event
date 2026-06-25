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
        Schema::table('participation_submissions', function (Blueprint $table) {
            // Make package-related columns nullable (for participant-only mode)
            $table->unsignedBigInteger('participation_programme_package_id')->nullable()->change();
            $table->unsignedBigInteger('programme_payment_method_id')->nullable()->change();
            
            // Optional: Also make receipt fields nullable since participant-only doesn't need them
            $table->string('receipt_path')->nullable()->change();
            $table->string('receipt_original_name')->nullable()->change();
            $table->unsignedBigInteger('receipt_size')->nullable()->change();
            $table->string('receipt_mime')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('participation_submissions', function (Blueprint $table) {
            // Revert back to NOT NULL (be careful - this will fail if you have NULL data)
            // Only uncomment these if you're sure you want to revert
            
            // $table->unsignedBigInteger('participation_programme_package_id')->nullable(false)->change();
            // $table->unsignedBigInteger('programme_payment_method_id')->nullable(false)->change();
            // $table->string('receipt_path')->nullable(false)->change();
            // $table->string('receipt_original_name')->nullable(false)->change();
            // $table->unsignedBigInteger('receipt_size')->nullable(false)->change();
            // $table->string('receipt_mime')->nullable(false)->change();
        });
    }
};