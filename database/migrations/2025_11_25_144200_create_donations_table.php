<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fundraiser_id')->constrained('fundraisers')->onDelete('cascade');
            $table->string('donor_name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->decimal('amount_pledge', 12, 2);
            $table->longText('notes')->nullable();
            $table->string('receipt_file')->nullable();
            $table->enum('status', ['pending', 'approved'])->default('pending');
            $table->dateTime('donate_time')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
