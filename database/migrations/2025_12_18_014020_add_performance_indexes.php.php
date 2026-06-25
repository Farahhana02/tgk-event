
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Indexes for packages
        Schema::table('packages', function (Blueprint $table) {
            $table->index('is_active', 'idx_packages_active');
            $table->index(['name', 'package_type'], 'idx_packages_name_type');
        });

        // Indexes for payment_methods
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->index('is_active', 'idx_payment_methods_active');
            $table->index('bank', 'idx_payment_methods_bank');
        });

        // Indexes for programme_packages
        Schema::table('programme_packages', function (Blueprint $table) {
            $table->index(['programme_id', 'is_active'], 'idx_prog_packages_prog_active');
            $table->index('is_locked', 'idx_prog_packages_locked');
            $table->index('sort_order', 'idx_prog_packages_order');
        });

        // Indexes for programme_payment_methods
        Schema::table('programme_payment_methods', function (Blueprint $table) {
            $table->index(['programme_id', 'is_active'], 'idx_prog_payment_methods_prog_active');
        });

        // Indexes for participation_programme_packages
        Schema::table('participation_programme_packages', function (Blueprint $table) {
            $table->index(['programme_id', 'is_active'], 'idx_part_prog_packages_prog_active');
            $table->index('is_locked', 'idx_part_prog_packages_locked');
            $table->index('sort_order', 'idx_part_prog_packages_order');
        });

        // Indexes for participation_submissions
        Schema::table('participation_submissions', function (Blueprint $table) {
            $table->index(['programme_id', 'status', 'created_at'], 'idx_part_submissions_prog_status_date');
            $table->index('company_name', 'idx_part_submissions_company');
            $table->index('created_at', 'idx_part_submissions_created');
        });
    }

    public function down(): void
    {
        // Drop all added indexes
        Schema::table('packages', function (Blueprint $table) {
            $table->dropIndex('idx_packages_active');
            $table->dropIndex('idx_packages_name_type');
        });

        Schema::table('payment_methods', function (Blueprint $table) {
            $table->dropIndex('idx_payment_methods_active');
            $table->dropIndex('idx_payment_methods_bank');
        });

        Schema::table('programme_packages', function (Blueprint $table) {
            $table->dropIndex('idx_prog_packages_prog_active');
            $table->dropIndex('idx_prog_packages_locked');
            $table->dropIndex('idx_prog_packages_order');
        });

        Schema::table('programme_payment_methods', function (Blueprint $table) {
            $table->dropIndex('idx_prog_payment_methods_prog_active');
        });

        Schema::table('participation_programme_packages', function (Blueprint $table) {
            $table->dropIndex('idx_part_prog_packages_prog_active');
            $table->dropIndex('idx_part_prog_packages_locked');
            $table->dropIndex('idx_part_prog_packages_order');
        });

        Schema::table('participation_submissions', function (Blueprint $table) {
            $table->dropIndex('idx_part_submissions_prog_status_date');
            $table->dropIndex('idx_part_submissions_company');
            $table->dropIndex('idx_part_submissions_created');
        });
    }
};