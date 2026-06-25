// File: database/migrations/2025_12_18_013656_add_unique_constraints_to_master_tables.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Disable foreign key checks to avoid constraint issues
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        echo "=== Adding unique constraints ===\n";
        
        // Use a helper function to safely add constraints
        $this->safeAddConstraint('packages', ['name', 'package_type'], 'uq_packages_name_type');
        $this->safeAddConstraint('payment_methods', ['account_number'], 'uq_payment_account_number');
        $this->safeAddConstraint('programme_packages', ['programme_id', 'package_id'], 'uq_programme_package');
        $this->safeAddConstraint('programme_payment_methods', ['programme_id', 'payment_method_id'], 'uq_programme_payment');
        $this->safeAddConstraint('participation_programme_packages', ['programme_id', 'package_id'], 'uq_participation_programme_package');
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        
        echo "\n=== Constraints check completed ===\n";
    }
    
    private function safeAddConstraint(string $tableName, array $columns, string $indexName): void
    {
        if (!Schema::hasTable($tableName)) {
            echo "Table '{$tableName}' doesn't exist. Skipping.\n";
            return;
        }
        
        // Check if index already exists
        $indexExists = $this->indexExists($tableName, $indexName);
        
        if ($indexExists) {
            echo "✓ Index '{$indexName}' already exists on '{$tableName}'\n";
            return;
        }
        
        // Check for duplicates before adding constraint
        $hasDuplicates = $this->hasDuplicates($tableName, $columns);
        
        if ($hasDuplicates) {
            echo "⚠ Found duplicates in '{$tableName}'. Removing duplicates first...\n";
            $this->removeDuplicates($tableName, $columns);
        }
        
        // Now add the constraint
        try {
            Schema::table($tableName, function (Blueprint $table) use ($columns, $indexName) {
                $table->unique($columns, $indexName);
            });
            echo "✓ Added constraint '{$indexName}' to '{$tableName}'\n";
        } catch (\Exception $e) {
            echo "✗ Failed to add constraint '{$indexName}' to '{$tableName}': " . $e->getMessage() . "\n";
        }
    }
    
    private function indexExists(string $tableName, string $indexName): bool
    {
        try {
            $result = DB::select("
                SHOW INDEX FROM {$tableName} 
                WHERE Key_name = ?
            ", [$indexName]);
            
            return !empty($result);
        } catch (\Exception $e) {
            return false;
        }
    }
    
    private function hasDuplicates(string $tableName, array $columns): bool
    {
        $columnsStr = implode(', ', $columns);
        $groupBy = implode(', ', $columns);
        
        try {
            $result = DB::select("
                SELECT COUNT(*) as duplicate_count
                FROM (
                    SELECT {$columnsStr}, COUNT(*) as cnt
                    FROM {$tableName}
                    GROUP BY {$groupBy}
                    HAVING cnt > 1
                ) as duplicates
            ");
            
            return !empty($result) && $result[0]->duplicate_count > 0;
        } catch (\Exception $e) {
            return false;
        }
    }
    
    private function removeDuplicates(string $tableName, array $columns): void
    {
        $columnsStr = implode(', ', array_map(function($col) {
            return "p1.{$col} = p2.{$col}";
        }, $columns));
        
        $columnsList = implode(', ', $columns);
        
        try {
            // Keep the lowest ID and delete the rest
            $deleted = DB::affectingStatement("
                DELETE p1 FROM {$tableName} p1
                INNER JOIN {$tableName} p2 
                WHERE p1.id > p2.id 
                  AND {$columnsStr}
            ");
            
            echo "  Removed {$deleted} duplicate rows from '{$tableName}'\n";
        } catch (\Exception $e) {
            echo "  Failed to remove duplicates: " . $e->getMessage() . "\n";
        }
    }
    
    public function down(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        echo "=== Removing unique constraints ===\n";
        
        $constraints = [
            'packages' => 'uq_packages_name_type',
            'payment_methods' => 'uq_payment_account_number',
            'programme_packages' => 'uq_programme_package',
            'programme_payment_methods' => 'uq_programme_payment',
            'participation_programme_packages' => 'uq_participation_programme_package',
        ];
        
        foreach ($constraints as $tableName => $indexName) {
            if (!Schema::hasTable($tableName)) {
                continue;
            }
            
            if ($this->indexExists($tableName, $indexName)) {
                try {
                    Schema::table($tableName, function (Blueprint $table) use ($indexName) {
                        $table->dropUnique($indexName);
                    });
                    echo "✓ Removed constraint '{$indexName}' from '{$tableName}'\n";
                } catch (\Exception $e) {
                    echo "✗ Failed to remove constraint '{$indexName}' from '{$tableName}': " . $e->getMessage() . "\n";
                }
            } else {
                echo "⚠ Constraint '{$indexName}' doesn't exist on '{$tableName}'\n";
            }
        }
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};