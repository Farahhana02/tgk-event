<?php
// CREATE NEW FILE: database/migrations/2025_12_10_XXXXXX_create_programme_items_table.php
// Run: php artisan make:migration create_programme_items_table

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('programme_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')
                  ->constrained('programs')
                  ->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->json('images')->nullable(); // Store multiple images
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('programme_items');
    }
};