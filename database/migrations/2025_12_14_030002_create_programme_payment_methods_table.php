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
Schema::create('programme_payment_methods', function (Blueprint $table) {
    $table->id();

    $table->foreignId('programme_id')
        ->constrained('participation_programmes')
        ->cascadeOnDelete();

    $table->foreignId('payment_method_id')
        ->constrained('payment_methods')
        ->restrictOnDelete();

    $table->boolean('is_active')->default(true);

    $table->timestamps();

    $table->unique(['programme_id', 'payment_method_id']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programme_payment_methods');
    }
};
