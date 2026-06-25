<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('introduction')->nullable();
            $table->text('background')->nullable();
            $table->text('objectives')->nullable();
            
            // Program Tentative
            $table->date('event_date')->nullable();
            $table->time('event_time')->nullable();
            $table->string('location')->nullable();
            $table->string('theme')->nullable();
            $table->json('schedules')->nullable(); // [{time, description}]
            
            // VIP Section
            $table->json('vip_list')->nullable(); // [{name, position, image}]
            
            // Participation
            $table->text('participation_description')->nullable();
            $table->json('participation_prices')->nullable(); // [{description, price}]
            $table->enum('participation_form_type', ['file', 'link'])->default('file');
            $table->string('participation_form')->nullable(); // file path or link
            
            // Sponsorship
            $table->text('sponsorship_description')->nullable();
            $table->json('sponsorship_packages')->nullable(); // [{description, price}]
            $table->string('sponsorship_additional_files')->nullable();
            $table->enum('sponsorship_form_type', ['file', 'link'])->default('file');
            $table->string('sponsorship_form')->nullable();
            
            // Programme Images
            $table->json('programme_images')->nullable(); // up to 4 images
            $table->string('programme_name')->nullable();
            $table->text('programme_description')->nullable();
            
            // Display control
            $table->boolean('is_visible')->default(true);
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('programs');
    }
};