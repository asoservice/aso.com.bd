<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FaqCategoryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @return mixed
     */
    public function viewAny(User $user)
    {
        if ($user->can('backend.faq-categories.index')) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the model.
     *
     * @return mixed
     */
    public function view(User $user, Category $category)
    {
        if ($user->can('backend.faq-categories.index')) {
            return true;
        }
    }

    /**
     * Determine whether the user can create models.
     *
     * @return mixed
     */
    public function create(User $user)
    {
        if ($user->can('backend.faq-categories.create')) {

            return true;
        }
    }

    /**
     * Determine whether the user can update the model.
     *
     * @return mixed
     */
    public function update(User $user, Category $category)
    {
        if ($user->can('backend.faq-categories.edit') || $user->id == $category->created_by) {

            return true;
        }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @return mixed
     */
    public function delete(User $user, Category $category)
    {
        if ($user->can('backend.faq-categories.destroy') && $user->id == $category->created_by) {

            return true;
        }
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @return mixed
     */
    public function restore(User $user, Category $category)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @return mixed
     */
    public function forceDelete(User $user, Category $category)
    {
        //
    }
}
