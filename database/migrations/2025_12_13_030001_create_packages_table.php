<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();

            $table->string('name'); // VIP, Student, Corporate
            $table->enum('package_type', ['one_person', 'multi_person']);
            $table->decimal('default_price', 10, 2)->default(0.00); // ✅ ADDED
            $table->unsignedSmallInteger('people_per_package')->nullable(); // ✅ ADDED
            $table->text('description')->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
