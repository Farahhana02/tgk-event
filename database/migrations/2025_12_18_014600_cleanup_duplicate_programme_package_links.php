// File: database/migrations/2025_12_18_014600_cleanup_duplicate_programme_package_links.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Disable foreign key checks temporarily
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // 1. First, cleanup duplicate packages (with better handling)
        $this->cleanupDuplicatePackages();
        
        // 2. Cleanup duplicate payment methods
        $this->cleanupDuplicatePaymentMethods();
        
        // 3. Cleanup duplicate programme-package links
        $this->cleanupDuplicateProgrammePackageLinks();
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    private function cleanupDuplicatePackages(): void
    {
        echo "=== Cleaning up duplicate packages ===\n";
        
        // Find duplicate packages
        $duplicates = DB::table('packages')
            ->select('name', 'package_type', DB::raw('COUNT(*) as count'))
            ->groupBy('name', 'package_type')
            ->having('count', '>', 1)
            ->get();

        if ($duplicates->isEmpty()) {
            echo "No duplicate packages found.\n";
            return;
        }

        foreach ($duplicates as $duplicate) {
            echo "Processing: '{$duplicate->name}' ({$duplicate->package_type})\n";
            
            // Get all IDs for this duplicate, ordered by creation
            $packageRows = DB::table('packages')
                ->where('name', $duplicate->name)
                ->where('package_type', $duplicate->package_type)
                ->orderBy('created_at', 'asc')
                ->orderBy('id', 'asc')
                ->get();

            $keepPackage = $packageRows->first();
            $duplicatePackages = $packageRows->slice(1);
            
            echo "  Keeping package ID: {$keepPackage->id}\n";
            
            foreach ($duplicatePackages as $duplicatePackage) {
                echo "  Processing duplicate package ID: {$duplicatePackage->id}\n";
                
                // 1. First, check for duplicate programme links and remove them
                $this->removeDuplicateProgrammeLinks($keepPackage->id, $duplicatePackage->id);
                
                // 2. Update programme_packages references
                $updated = DB::table('programme_packages')
                    ->where('package_id', $duplicatePackage->id)
                    ->update(['package_id' => $keepPackage->id]);
                    
                if ($updated > 0) {
                    echo "    Updated {$updated} programme_packages records\n";
                }
                
                // 3. Update participation_programme_packages references
                $updatedParticipation = DB::table('participation_programme_packages')
                    ->where('package_id', $duplicatePackage->id)
                    ->update(['package_id' => $keepPackage->id]);
                    
                if ($updatedParticipation > 0) {
                    echo "    Updated {$updatedParticipation} participation_programme_packages records\n";
                }
                
                // 4. Delete the duplicate package
                DB::table('packages')->where('id', $duplicatePackage->id)->delete();
                echo "    Deleted duplicate package ID: {$duplicatePackage->id}\n";
            }
        }
    }

    private function removeDuplicateProgrammeLinks(int $keepPackageId, int $duplicatePackageId): void
    {
        echo "    Checking for duplicate programme links...\n";
        
        // Get all programmes that use the duplicate package
        $programmeLinks = DB::table('participation_programme_packages')
            ->where('package_id', $duplicatePackageId)
            ->get();
        
        foreach ($programmeLinks as $link) {
            // Check if the keep package is already linked to this programme
            $exists = DB::table('participation_programme_packages')
                ->where('programme_id', $link->programme_id)
                ->where('package_id', $keepPackageId)
                ->exists();
            
            if ($exists) {
                echo "      WARNING: Both packages are linked to programme {$link->programme_id}\n";
                echo "      Keeping the newer link and deleting the older one...\n";
                
                // Delete the duplicate link (keep the one with the keep package)
                DB::table('participation_programme_packages')
                    ->where('programme_id', $link->programme_id)
                    ->where('package_id', $duplicatePackageId)
                    ->delete();
                    
                echo "      Deleted duplicate link for programme {$link->programme_id}\n";
            }
        }
    }

    private function cleanupDuplicatePaymentMethods(): void
    {
        echo "\n=== Cleaning up duplicate payment methods ===\n";
        
        // Find duplicate account numbers
        $duplicates = DB::table('payment_methods')
            ->select('account_number', DB::raw('COUNT(*) as count'))
            ->groupBy('account_number')
            ->having('count', '>', 1)
            ->get();

        if ($duplicates->isEmpty()) {
            echo "No duplicate payment methods found.\n";
            return;
        }

        foreach ($duplicates as $duplicate) {
            echo "Processing duplicate account number: {$duplicate->account_number}\n";
            
            // Get all payment methods with this account number
            $paymentRows = DB::table('payment_methods')
                ->where('account_number', $duplicate->account_number)
                ->orderBy('created_at', 'asc')
                ->orderBy('id', 'asc')
                ->get();

            $keepPayment = $paymentRows->first();
            $duplicatePayments = $paymentRows->slice(1);
            
            echo "  Keeping payment method ID: {$keepPayment->id}\n";
            
            foreach ($duplicatePayments as $duplicatePayment) {
                echo "  Processing duplicate payment method ID: {$duplicatePayment->id}\n";
                
                // Check for duplicate programme links and remove them
                $this->removeDuplicatePaymentMethodLinks($keepPayment->id, $duplicatePayment->id);
                
                // Update programme_payment_methods references
                $updated = DB::table('programme_payment_methods')
                    ->where('payment_method_id', $duplicatePayment->id)
                    ->update(['payment_method_id' => $keepPayment->id]);
                    
                if ($updated > 0) {
                    echo "    Updated {$updated} programme_payment_methods records\n";
                }
                
                // Delete the duplicate payment method
                DB::table('payment_methods')->where('id', $duplicatePayment->id)->delete();
                echo "    Deleted duplicate payment method ID: {$duplicatePayment->id}\n";
            }
        }
    }

    private function removeDuplicatePaymentMethodLinks(int $keepPaymentId, int $duplicatePaymentId): void
    {
        echo "    Checking for duplicate payment method programme links...\n";
        
        // Get all programmes that use the duplicate payment method
        $programmeLinks = DB::table('programme_payment_methods')
            ->where('payment_method_id', $duplicatePaymentId)
            ->get();
        
        foreach ($programmeLinks as $link) {
            // Check if the keep payment method is already linked to this programme
            $exists = DB::table('programme_payment_methods')
                ->where('programme_id', $link->programme_id)
                ->where('payment_method_id', $keepPaymentId)
                ->exists();
            
            if ($exists) {
                echo "      WARNING: Both payment methods are linked to programme {$link->programme_id}\n";
                echo "      Keeping the newer link and deleting the older one...\n";
                
                // Delete the duplicate link
                DB::table('programme_payment_methods')
                    ->where('programme_id', $link->programme_id)
                    ->where('payment_method_id', $duplicatePaymentId)
                    ->delete();
                    
                echo "      Deleted duplicate payment method link for programme {$link->programme_id}\n";
            }
        }
    }

    private function cleanupDuplicateProgrammePackageLinks(): void
    {
        echo "\n=== Cleaning up duplicate programme-package links ===\n";
        
        // Check participation_programme_packages for duplicates
        $duplicates = DB::table('participation_programme_packages as p1')
            ->join('participation_programme_packages as p2', function($join) {
                $join->on('p1.programme_id', '=', 'p2.programme_id')
                     ->on('p1.package_id', '=', 'p2.package_id')
                     ->whereColumn('p1.id', '<', 'p2.id');
            })
            ->select('p1.programme_id', 'p1.package_id', DB::raw('COUNT(*) as count'))
            ->groupBy('p1.programme_id', 'p1.package_id')
            ->get();

        if ($duplicates->isEmpty()) {
            echo "No duplicate programme-package links found.\n";
            return;
        }

        foreach ($duplicates as $duplicate) {
            echo "Processing duplicate link: programme_id={$duplicate->programme_id}, package_id={$duplicate->package_id}\n";
            
            // Get all duplicate links
            $links = DB::table('participation_programme_packages')
                ->where('programme_id', $duplicate->programme_id)
                ->where('package_id', $duplicate->package_id)
                ->orderBy('created_at', 'desc') // Keep the newest
                ->orderBy('id', 'desc')
                ->get();

            $keepLink = $links->first();
            $duplicateLinks = $links->slice(1);
            
            echo "  Keeping link ID: {$keepLink->id} (newest)\n";
            
            foreach ($duplicateLinks as $duplicateLink) {
                echo "  Deleting duplicate link ID: {$duplicateLink->id}\n";
                DB::table('participation_programme_packages')
                    ->where('id', $duplicateLink->id)
                    ->delete();
            }
        }
    }

    public function down(): void
    {
        // This is a data cleanup migration - cannot be rolled back safely
        echo "WARNING: This is a one-way data cleanup migration!\n";
        echo "Make sure you have a database backup before running.\n";
        echo "Rollback is not supported for this migration.\n";
    }
};