<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('programme_packages', function (Blueprint $table) {
            $table->id();

            $table->foreignId('programme_id')
    ->constrained('programs')
    ->cascadeOnDelete();


            $table->foreignId('package_id')
                ->constrained('packages')
                ->restrictOnDelete();

            // 🔥 PRICE SNAPSHOT (can differ from default)
            $table->decimal('price', 10, 2);

            // Copy of people_per_package (for multi-person)
            $table->unsignedSmallInteger('people_per_package')->nullable();

            // 🔒 LOCK AFTER PROGRAMME CLOSED
            $table->boolean('is_locked')->default(false);

            // ✅ ADDED: Sort order and active status
            $table->unsignedSmallInteger('sort_order')->default(1);
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            // One package only once per programme
            $table->unique(['programme_id', 'package_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('programme_packages');
    }
};