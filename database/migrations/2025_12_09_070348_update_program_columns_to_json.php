<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('programs', function (Blueprint $table) {
            $table->json('introduction')->nullable()->change();
            $table->json('background')->nullable()->change();
            $table->json('objectives')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('programs', function (Blueprint $table) {
            $table->text('introduction')->nullable()->change();
            $table->text('background')->nullable()->change();
            $table->text('objectives')->nullable()->change();
        });
    }
};
