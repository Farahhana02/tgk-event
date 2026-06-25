
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Clean up duplicate packages
        $this->cleanupDuplicatePackages();
        
        // 2. Clean up duplicate payment methods
        $this->cleanupDuplicatePaymentMethods();
    }

    private function cleanupDuplicatePackages(): void
    {
        // Find and keep only the first occurrence of each unique package
        $duplicates = DB::table('packages')
            ->select('name', 'package_type', DB::raw('COUNT(*) as count'))
            ->groupBy('name', 'package_type')
            ->having('count', '>', 1)
            ->get();

        foreach ($duplicates as $duplicate) {
            echo "Processing duplicates for: {$duplicate->name} ({$duplicate->package_type})\n";
            
            // Get all IDs for this duplicate
            $ids = DB::table('packages')
                ->where('name', $duplicate->name)
                ->where('package_type', $duplicate->package_type)
                ->orderBy('created_at', 'asc')
                ->pluck('id')
                ->toArray();

            // Keep the first one (oldest)
            $keepId = array_shift($ids);
            
            echo "  Keeping ID: {$keepId}\n";
            
            // Update references in programme_packages
            foreach ($ids as $duplicateId) {
                echo "  Merging ID: {$duplicateId} into {$keepId}\n";
                
                // Check if this duplicate is used in any programme
                $isUsed = DB::table('programme_packages')
                    ->where('package_id', $duplicateId)
                    ->exists();
                    
                if ($isUsed) {
                    // Update programme_packages to use the kept package
                    DB::table('programme_packages')
                        ->where('package_id', $duplicateId)
                        ->update(['package_id' => $keepId]);
                        
                    echo "    Updated programme_packages references\n";
                }
                
                // Check if used in participation_programme_packages
                $isUsedParticipation = DB::table('participation_programme_packages')
                    ->where('package_id', $duplicateId)
                    ->exists();
                    
                if ($isUsedParticipation) {
                    // Update participation_programme_packages
                    DB::table('participation_programme_packages')
                        ->where('package_id', $duplicateId)
                        ->update(['package_id' => $keepId]);
                        
                    echo "    Updated participation_programme_packages references\n";
                }
                
                // Delete the duplicate package
                DB::table('packages')->where('id', $duplicateId)->delete();
                echo "    Deleted duplicate package ID: {$duplicateId}\n";
            }
        }
    }

    private function cleanupDuplicatePaymentMethods(): void
    {
        // Find duplicate account numbers
        $duplicates = DB::table('payment_methods')
            ->select('account_number', DB::raw('COUNT(*) as count'))
            ->groupBy('account_number')
            ->having('count', '>', 1)
            ->get();

        foreach ($duplicates as $duplicate) {
            echo "Processing duplicate account number: {$duplicate->account_number}\n";
            
            // Get all IDs for this account number
            $ids = DB::table('payment_methods')
                ->where('account_number', $duplicate->account_number)
                ->orderBy('created_at', 'asc')
                ->pluck('id')
                ->toArray();

            // Keep the first one (oldest)
            $keepId = array_shift($ids);
            
            echo "  Keeping ID: {$keepId}\n";
            
            // Update references in programme_payment_methods
            foreach ($ids as $duplicateId) {
                echo "  Merging ID: {$duplicateId} into {$keepId}\n";
                
                // Check if used in programme_payment_methods
                $isUsed = DB::table('programme_payment_methods')
                    ->where('payment_method_id', $duplicateId)
                    ->exists();
                    
                if ($isUsed) {
                    // Update programme_payment_methods
                    DB::table('programme_payment_methods')
                        ->where('payment_method_id', $duplicateId)
                        ->update(['payment_method_id' => $keepId]);
                        
                    echo "    Updated programme_payment_methods references\n";
                }
                
                // Delete the duplicate payment method
                DB::table('payment_methods')->where('id', $duplicateId)->delete();
                echo "    Deleted duplicate payment method ID: {$duplicateId}\n";
            }
        }
    }

    public function down(): void
    {
        // This is a one-way cleanup migration
        // We cannot easily restore deleted duplicates
        // Add a backup mechanism if you need to rollback
        
        // You could:
        // 1. Create a backup table before cleanup
        // 2. Store the changes in a log table
        // But for simplicity, we'll leave it as is
        echo "WARNING: This migration cannot be rolled back safely!\n";
        echo "Make sure you have a database backup before running.\n";
    }
};