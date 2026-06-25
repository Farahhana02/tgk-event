<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('participation_submissions', function (Blueprint $table) {
            $table->string('supporting_document_path')->nullable();
            $table->string('supporting_document_original')->nullable();
            $table->integer('supporting_document_size')->nullable();
            $table->string('supporting_document_mime')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('participation_submissions', function (Blueprint $table) {
            $table->dropColumn([
                'supporting_document_path',
                'supporting_document_original',
                'supporting_document_size',
                'supporting_document_mime',
            ]);
        });
    }
};
