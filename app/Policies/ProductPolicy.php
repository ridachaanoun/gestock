<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Product $product)
    {
        return $user->id === $product->user_id;
    }

    public function create(User $user)
    {
        return true; // Any authenticated user can create a product
    }

    public function update(User $user, Product $product)
    {
        return $user->id === $product->user_id;
    }

    public function delete(User $user, Product $product)
    {
        return $user->id === $product->user_id;
    }
}
