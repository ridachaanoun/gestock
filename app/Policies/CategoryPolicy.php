<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the given user can view the category.
     */
    public function view(User $user, Category $category)
    {
        return $user->id === $category->user_id;
    }

    /**
     * Determine if the given user can update the category.
     */
    public function update(User $user, Category $category)
    {
        return $user->id === $category->user_id;
    }

    /**
     * Determine if the given user can delete the category.
     */
    public function delete(User $user, Category $category)
    {
        return $user->id === $category->user_id;
    }
}
