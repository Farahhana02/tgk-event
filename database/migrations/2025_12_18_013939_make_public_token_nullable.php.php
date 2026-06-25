
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('participation_programmes', function (Blueprint $table) {
            $table->string('public_token', 64)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('participation_programmes', function (Blueprint $table) {
            // You might want to generate tokens for existing records before making it non-nullable
            $table->string('public_token', 64)->nullable(false)->change();
        });
    }
};