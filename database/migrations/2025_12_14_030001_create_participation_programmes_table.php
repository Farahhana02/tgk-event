<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('participation_programmes', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->date('start_date');
            $table->date('end_date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('venue')->nullable();

            // Public form link token: /participation/{token}
            $table->string('public_token', 64)->unique();

            // Optional QR uploaded by admin
            $table->string('qr_path')->nullable();

            // Receipt max size rule (prototype: 20MB)
            $table->unsignedSmallInteger('receipt_max_mb')->default(20);

            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('participation_programmes');
    }
};
