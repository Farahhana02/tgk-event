<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('participation_programme_packages', function (Blueprint $table) {
            $table->id();

            // ✅ Links to participation_programmes (not programs!)
            $table->foreignId('programme_id')
                ->constrained('participation_programmes')
                ->cascadeOnDelete();

            $table->foreignId('package_id')
                ->constrained('packages')
                ->restrictOnDelete();

            // Price snapshot (can differ from default)
            $table->decimal('price', 10, 2);

            // Copy of people_per_package (for multi-person)
            $table->unsignedSmallInteger('people_per_package')->nullable();

            // Lock after form closed
            $table->boolean('is_locked')->default(false);

            $table->unsignedSmallInteger('sort_order')->default(1);
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            // One package only once per programme
            $table->unique(['programme_id', 'package_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('participation_programme_packages');
    }
};