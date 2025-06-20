<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Auth\Access\Response;

class ProductPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Product $product): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Vendor $vendor, Product $product): bool
    {
        return $vendor->id !== $product->vendor_id;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Vendor $vendor, Product $product): bool
    {
        return $vendor->id === $product->vendor_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Vendor $vendor, Product $product): bool
    {
        return $vendor->id === $product->vendor_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Product $product): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Product $product): bool
    {
        //
    }
}
