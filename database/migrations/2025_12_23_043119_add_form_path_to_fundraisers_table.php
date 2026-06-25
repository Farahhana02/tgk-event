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
// database/migrations/xxxx_add_form_path_to_fundraisers_table.php
Schema::table('fundraisers', function (Blueprint $table) {
    $table->string('form_path')->nullable()->after('status');
});

    }
};
