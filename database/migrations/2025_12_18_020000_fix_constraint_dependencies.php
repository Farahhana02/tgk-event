
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Temporarily disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        echo "=== Fixing constraint dependencies ===\n";
        
        // 1. First, check and fix any foreign key constraints
        $this->fixParticipationSubmissionsConstraints();
        
        // 2. Now we can safely drop and recreate constraints
        $this->recreateConstraints();
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        
        echo "=== Constraints fixed successfully ===\n";
    }
    
    private function fixParticipationSubmissionsConstraints(): void
    {
        echo "Checking participation_submissions constraints...\n";
        
        // Check if the table exists and has the constraint
        $tableExists = Schema::hasTable('participation_submissions');
        
        if (!$tableExists) {
            echo "participation_submissions table doesn't exist. Skipping.\n";
            return;
        }
        
        // Check if the foreign key exists
        $constraints = DB::select("
            SELECT CONSTRAINT_NAME 
            FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
            WHERE TABLE_NAME = 'participation_submissions' 
            AND REFERENCED_TABLE_NAME = 'programme_packages'
            AND CONSTRAINT_SCHEMA = DATABASE()
        ");
        
        if (empty($constraints)) {
            echo "No foreign key constraint found on participation_submissions.\n";
            return;
        }
        
        foreach ($constraints as $constraint) {
            echo "Dropping foreign key: {$constraint->CONSTRAINT_NAME}\n";
            
            // Drop the foreign key constraint
            Schema::table('participation_submissions', function (Blueprint $table) use ($constraint) {
                $table->dropForeign([$constraint->CONSTRAINT_NAME]);
            });
        }
    }
    
    private function recreateConstraints(): void
    {
        echo "\nRecreating constraints...\n";
        
        // 1. Drop old unique constraints safely
        $this->dropOldConstraints();
        
        // 2. Add new unique constraints with proper names
        $this->addNewConstraints();
        
        // 3. Re-add foreign key constraints
        $this->addForeignKeys();
    }
    
    private function dropOldConstraints(): void
    {
        echo "Dropping old constraints...\n";
        
        // Try to drop each constraint, catching any errors
        $tablesToUpdate = [
            'packages' => ['uq_packages_name_type'],
            'payment_methods' => ['uq_payment_account_number'],
            'programme_packages' => ['programme_packages_programme_id_package_id_unique'],
            'programme_payment_methods' => ['programme_payment_methods_programme_id_payment_method_id_unique'],
            'participation_programme_packages' => ['participation_programme_packages_programme_id_package_id_unique'],
        ];
        
        foreach ($tablesToUpdate as $table => $indexes) {
            if (!Schema::hasTable($table)) {
                echo "Table {$table} doesn't exist. Skipping.\n";
                continue;
            }
            
            foreach ($indexes as $index) {
                try {
                    DB::statement("ALTER TABLE {$table} DROP INDEX IF EXISTS {$index}");
                    echo "Dropped index {$index} from {$table}\n";
                } catch (\Exception $e) {
                    echo "Could not drop index {$index} from {$table}: " . $e->getMessage() . "\n";
                }
            }
        }
    }
    
    private function addNewConstraints(): void
    {
        echo "\nAdding new unique constraints...\n";
        
        // 1. Packages: name + package_type must be unique
        if (Schema::hasTable('packages')) {
            try {
                Schema::table('packages', function (Blueprint $table) {
                    $table->unique(['name', 'package_type'], 'uq_packages_name_type');
                });
                echo "Added uq_packages_name_type constraint\n";
            } catch (\Exception $e) {
                echo "Failed to add packages constraint: " . $e->getMessage() . "\n";
            }
        }
        
        // 2. Payment methods: account_number must be unique
        if (Schema::hasTable('payment_methods')) {
            try {
                Schema::table('payment_methods', function (Blueprint $table) {
                    $table->unique('account_number', 'uq_payment_account_number');
                });
                echo "Added uq_payment_account_number constraint\n";
            } catch (\Exception $e) {
                echo "Failed to add payment_methods constraint: " . $e->getMessage() . "\n";
            }
        }
        
        // 3. Programme packages: programme_id + package_id must be unique
        if (Schema::hasTable('programme_packages')) {
            try {
                Schema::table('programme_packages', function (Blueprint $table) {
                    $table->unique(['programme_id', 'package_id'], 'uq_programme_package');
                });
                echo "Added uq_programme_package constraint\n";
            } catch (\Exception $e) {
                echo "Failed to add programme_packages constraint: " . $e->getMessage() . "\n";
            }
        }
        
        // 4. Programme payment methods: programme_id + payment_method_id must be unique
        if (Schema::hasTable('programme_payment_methods')) {
            try {
                Schema::table('programme_payment_methods', function (Blueprint $table) {
                    $table->unique(['programme_id', 'payment_method_id'], 'uq_programme_payment');
                });
                echo "Added uq_programme_payment constraint\n";
            } catch (\Exception $e) {
                echo "Failed to add programme_payment_methods constraint: " . $e->getMessage() . "\n";
            }
        }
        
        // 5. Participation programme packages: programme_id + package_id must be unique
        if (Schema::hasTable('participation_programme_packages')) {
            try {
                Schema::table('participation_programme_packages', function (Blueprint $table) {
                    $table->unique(['programme_id', 'package_id'], 'uq_participation_programme_package');
                });
                echo "Added uq_participation_programme_package constraint\n";
            } catch (\Exception $e) {
                echo "Failed to add participation_programme_packages constraint: " . $e->getMessage() . "\n";
            }
        }
    }
    
    private function addForeignKeys(): void
    {
        echo "\nRe-adding foreign keys...\n";
        
        // Re-add foreign key to participation_submissions if it was dropped
        if (Schema::hasTable('participation_submissions') && 
            Schema::hasTable('programme_packages') &&
            Schema::hasColumn('participation_submissions', 'programme_package_id')) {
            
            try {
                Schema::table('participation_submissions', function (Blueprint $table) {
                    $table->foreign('programme_package_id', 'fk_part_sub_prog_pkg')
                          ->references('id')->on('programme_packages')
                          ->restrictOnDelete();
                });
                echo "Re-added foreign key fk_part_sub_prog_pkg\n";
            } catch (\Exception $e) {
                echo "Failed to add foreign key: " . $e->getMessage() . "\n";
            }
        }
        
        // Also re-add other important foreign keys if needed
        if (Schema::hasTable('participation_submissions') && 
            Schema::hasTable('programme_payment_methods') &&
            Schema::hasColumn('participation_submissions', 'programme_payment_method_id')) {
            
            try {
                Schema::table('participation_submissions', function (Blueprint $table) {
                    $table->foreign('programme_payment_method_id', 'fk_part_sub_pay_method')
                          ->references('id')->on('programme_payment_methods')
                          ->restrictOnDelete();
                });
                echo "Re-added foreign key fk_part_sub_pay_method\n";
            } catch (\Exception $e) {
                echo "Failed to add payment method foreign key: " . $e->getMessage() . "\n";
            }
        }
    }
    
    public function down(): void
    {
        // This is a complex migration - rolling back requires careful handling
        echo "WARNING: Rolling back this migration is complex!\n";
        echo "It's recommended to restore from backup instead.\n";
        
        // You could implement a rollback by storing the original state
        // But for safety, we'll just warn
    }
};