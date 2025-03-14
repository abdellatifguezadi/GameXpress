<?php

namespace App\Observers;

use App\Models\Product;
use App\Notifications\LowStockNotification;
use App\Models\User;

class ProductObserver
{
    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
       
        if ($product->wasChanged('stock') && $product->stock < 5) {
            $admins = User::role(['super_admin', 'product_manager'])->get();
            foreach ($admins as $admin) {
                $admin->notify(new LowStockNotification($product));
            }
        }
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "restored" event.
     */
    public function restored(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "force deleted" event.
     */
    public function forceDeleted(Product $product): void
    {
        //
    }
}
