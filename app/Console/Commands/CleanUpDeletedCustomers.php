<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Customer;

class CleanUpDeletedCustomers extends Command
{
    protected $signature = 'cleanup:deleted-customers';
    protected $description = 'Restore soft-deleted customers within a week or permanently delete those older than a week';

    public function handle()
    {
        // Restore soft-deleted customers that were deleted within the last week
        $restorableCustomers = Customer::onlyTrashed()
            ->where('deleted_at', '>=', now()->subWeek())
            ->get();

        if ($restorableCustomers->isNotEmpty()) {
            foreach ($restorableCustomers as $customer) {
                $customer->restore();
                $this->info('Customer ' . $customer->name . ' restored.');
            }
        } else {
            $this->info('No customers found to restore.');
        }

        // Permanently delete customers that have been deleted for more than a week
        $deletedCustomers = Customer::onlyTrashed()
            ->where('deleted_at', '<', now()->subWeek())
            ->get();

        if ($deletedCustomers->isEmpty()) {
            $this->info('No customers found to permanently delete.');
        } else {
            foreach ($deletedCustomers as $customer) {
                $customer->forceDelete();
                $this->info('Customer ' . $customer->name . ' permanently deleted.');
            }
        }
    }
}