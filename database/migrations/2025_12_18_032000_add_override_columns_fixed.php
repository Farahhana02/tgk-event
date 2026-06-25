// File: database/migrations/2025_12_18_032000_add_override_columns_fixed.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        echo "=== Adding override columns (Fixed Version) ===\n";
        
        // Disable foreign key checks for safety
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // 1. Add override columns to programme_packages (old system)
        echo "\n1. Checking programme_packages table...\n";
        if ($this->tableExists('programme_packages')) {
            echo "   Table exists. Checking columns...\n";
            
            // Add description column if not exists
            if (!$this->columnExists('programme_packages', 'description')) {
                DB::statement("ALTER TABLE programme_packages ADD COLUMN description TEXT NULL AFTER people_per_package");
                echo "   ✓ Added 'description' column\n";
            } else {
                echo "   ✓ 'description' column already exists\n";
            }
            
            // Add is_active column if not exists
            if (!$this->columnExists('programme_packages', 'is_active')) {
                DB::statement("ALTER TABLE programme_packages ADD COLUMN is_active TINYINT(1) NOT NULL DEFAULT 1 AFTER sort_order");
                echo "   ✓ Added 'is_active' column\n";
            } else {
                echo "   ✓ 'is_active' column already exists\n";
            }
        } else {
            echo "   ✗ Table 'programme_packages' doesn't exist\n";
        }
        
        // 2. Add override columns to programme_payment_methods
        echo "\n2. Checking programme_payment_methods table...\n";
        if ($this->tableExists('programme_payment_methods')) {
            echo "   Table exists. Checking columns...\n";
            
            // Add account_name column if not exists
            if (!$this->columnExists('programme_payment_methods', 'account_name')) {
                DB::statement("ALTER TABLE programme_payment_methods ADD COLUMN account_name VARCHAR(255) NULL AFTER payment_method_id");
                echo "   ✓ Added 'account_name' column\n";
            } else {
                echo "   ✓ 'account_name' column already exists\n";
            }
            
            // Add account_number column if not exists
            if (!$this->columnExists('programme_payment_methods', 'account_number')) {
                DB::statement("ALTER TABLE programme_payment_methods ADD COLUMN account_number VARCHAR(255) NULL AFTER account_name");
                echo "   ✓ Added 'account_number' column\n";
            } else {
                echo "   ✓ 'account_number' column already exists\n";
            }
            
            // Add is_active column if not exists
            if (!$this->columnExists('programme_payment_methods', 'is_active')) {
                DB::statement("ALTER TABLE programme_payment_methods ADD COLUMN is_active TINYINT(1) NOT NULL DEFAULT 1 AFTER account_number");
                echo "   ✓ Added 'is_active' column\n";
            } else {
                echo "   ✓ 'is_active' column already exists\n";
            }
        } else {
            echo "   ✗ Table 'programme_payment_methods' doesn't exist\n";
        }
        
        // 3. Add description column to participation_programme_packages (new system)
        echo "\n3. Checking participation_programme_packages table...\n";
        if ($this->tableExists('participation_programme_packages')) {
            echo "   Table exists. Checking columns...\n";
            
            // Add description column if not exists
            if (!$this->columnExists('participation_programme_packages', 'description')) {
                DB::statement("ALTER TABLE participation_programme_packages ADD COLUMN description TEXT NULL AFTER people_per_package");
                echo "   ✓ Added 'description' column\n";
            } else {
                echo "   ✓ 'description' column already exists\n";
            }
        } else {
            echo "   ✗ Table 'participation_programme_packages' doesn't exist\n";
        }
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        
        echo "\n=== Override columns added successfully ===\n";
    }
    
    private function tableExists(string $tableName): bool
    {
        try {
            $result = DB::select("SHOW TABLES LIKE ?", [$tableName]);
            return !empty($result);
        } catch (\Exception $e) {
            return false;
        }
    }
    
    private function columnExists(string $tableName, string $columnName): bool
    {
        try {
            $result = DB::select("
                SHOW COLUMNS FROM {$tableName} LIKE ?
            ", [$columnName]);
            return !empty($result);
        } catch (\Exception $e) {
            return false;
        }
    }
    
    public function down(): void
    {
        echo "=== Removing override columns ===\n";
        
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // Remove columns if they exist
        $columnsToRemove = [
            'programme_packages' => ['description', 'is_active'],
            'programme_payment_methods' => ['account_name', 'account_number', 'is_active'],
            'participation_programme_packages' => ['description'],
        ];
        
        foreach ($columnsToRemove as $table => $columns) {
            if ($this->tableExists($table)) {
                foreach ($columns as $column) {
                    if ($this->columnExists($table, $column)) {
                        try {
                            DB::statement("ALTER TABLE {$table} DROP COLUMN {$column}");
                            echo "✓ Removed column '{$column}' from '{$table}'\n";
                        } catch (\Exception $e) {
                            echo "✗ Failed to remove column '{$column}' from '{$table}': " . $e->getMessage() . "\n";
                        }
                    }
                }
            }
        }
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};