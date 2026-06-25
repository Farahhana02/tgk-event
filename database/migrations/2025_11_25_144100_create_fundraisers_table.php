<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fundraisers', function (Blueprint $table) {
            $table->id();
            $table->string('programme_name');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->decimal('target_amount', 12, 2)->default(0);
            $table->decimal('progress', 5, 2)->default(0);
            $table->string('image_path')->nullable(); // <-- ADD THIS LINE
            $table->longText('description')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fundraisers');
    }
};