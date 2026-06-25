<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('participation_submissions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('programme_id')
                ->constrained('participation_programmes')
                ->cascadeOnDelete();

            // Company details (public fill)
            $table->string('company_name');
            $table->string('officer_name');
            $table->string('phone_number');

            // ✅ CHANGED: Link to programme_packages (snapshot layer)
            $table->foreignId('programme_package_id')
                ->constrained('programme_packages')
                ->restrictOnDelete();

            // quantity = number of packages purchased / selected
            $table->unsignedInteger('quantity')->default(1);

            // ✅ ADDED: Store unit price at submission time (frozen snapshot)
            $table->decimal('unit_price', 10, 2);

            // Optional: store expected participants count for validation
            $table->unsignedInteger('expected_participants')->default(1);

            $table->decimal('total_price', 10, 2);

            // ✅ CHANGED: Link to programme_payment_methods
            $table->foreignId('programme_payment_method_id')
                ->constrained('programme_payment_methods')
                ->restrictOnDelete();

            // Receipt upload info
            $table->string('receipt_path');
            $table->string('receipt_original_name')->nullable();
            $table->unsignedBigInteger('receipt_size')->nullable(); // bytes
            $table->string('receipt_mime')->nullable();

            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('admin_note')->nullable();

            $table->timestamps();

            $table->index(['programme_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('participation_submissions');
    }
};
