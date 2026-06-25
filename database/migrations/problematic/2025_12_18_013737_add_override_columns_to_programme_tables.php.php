// File: database/migrations/2025_12_18_013737_add_override_columns_to_programme_tables.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        echo "=== Adding override columns ===\n";
        
        // 1. Add override columns to programme_packages
        $this->safeAddColumn('programme_packages', 'description', function (Blueprint $table) {
            $table->text('description')->nullable()->after('people_per_package');
        });
        
        $this->safeAddColumn('programme_packages', 'is_active', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('sort_order');
        });
        
        // 2. Add override columns to programme_payment_methods
        $this->safeAddColumn('programme_payment_methods', 'account_name', function (Blueprint $table) {
            $table->string('account_name')->nullable()->after('payment_method_id');
        });
        
        $this->safeAddColumn('programme_payment_methods', 'account_number', function (Blueprint $table) {
            $table->string('account_number')->nullable()->after('account_name');
        });
        
        $this->safeAddColumn('programme_payment_methods', 'is_active', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('account_number');
        });
        
        // 3. Add description column to participation_programme_packages
        $this->safeAddColumn('participation_programme_packages', 'description', function (Blueprint $table) {
            $table->text('description')->nullable()->after('people_per_package');
        });
        
        echo "\n=== Override columns check completed ===\n";
    }
    
    private function safeAddColumn(string $tableName, string $columnName, callable $addColumnCallback): void
    {
        if (!Schema::hasTable($tableName)) {
            echo "Table '{$tableName}' doesn't exist. Skipping.\n";
            return;
        }
        
        // Check if column already exists
        if (Schema::hasColumn($tableName, $columnName)) {
            echo "✓ Column '{$columnName}' already exists in '{$tableName}'\n";
            return;
        }
        
        // Add the column
        try {
            Schema::table($tableName, $addColumnCallback);
            echo "✓ Added column '{$columnName}' to '{$tableName}'\n";
        } catch (\Exception $e) {
            echo "✗ Failed to add column '{$columnName}' to '{$tableName}': " . $e->getMessage() . "\n";
        }
    }
    
    public function down(): void
    {
        echo "=== Removing override columns ===\n";
        
        // Only remove columns if they exist
        $columnsToRemove = [
            'programme_packages' => ['description', 'is_active'],
            'programme_payment_methods' => ['account_name', 'account_number', 'is_active'],
            'participation_programme_packages' => ['description'],
        ];
        
        foreach ($columnsToRemove as $tableName => $columns) {
            if (!Schema::hasTable($tableName)) {
                continue;
            }
            
            foreach ($columns as $columnName) {
                if (Schema::hasColumn($tableName, $columnName)) {
                    try {
                        Schema::table($tableName, function (Blueprint $table) use ($columnName) {
                            $table->dropColumn($columnName);
                        });
                        echo "✓ Removed column '{$columnName}' from '{$tableName}'\n";
                    } catch (\Exception $e) {
                        echo "✗ Failed to remove column '{$columnName}' from '{$tableName}': " . $e->getMessage() . "\n";
                    }
                } else {
                    echo "⚠ Column '{$columnName}' doesn't exist in '{$tableName}'\n";
                }
            }
        }
    }
};