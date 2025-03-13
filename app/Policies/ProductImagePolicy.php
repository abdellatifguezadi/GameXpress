<?php

namespace App\Policies;

use App\Models\ProductImage;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProductImagePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?User $user): bool
    {
        return $user ? $user->hasPermissionTo('view_products') : true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, ProductImage $productImage): bool
    {
        return $user ? $user->hasPermissionTo('view_products') : true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create_products');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ProductImage $productImage): bool
    {
        return $user->hasPermissionTo('edit_products');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ProductImage $productImage): bool
    {
        return $user->hasPermissionTo('delete_products');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ProductImage $productImage): bool
    {
        return $user->hasPermissionTo('edit_products');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ProductImage $productImage): bool
    {
        return $user->hasPermissionTo('delete_products');
    }
}
