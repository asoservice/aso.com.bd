<?php

namespace App\Policies;

use App\Models\FaqCategory;
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
        if ($user->can('backend.faq-category.index')) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the model.
     *
     * @return mixed
     */
    public function view(User $user, FaqCategory $category)
    {
        if ($user->can('backend.faq-category.index')) {
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
        if ($user->can('backend.faq-category.create')) {

            return true;
        }
    }

    public function edit(User $user, FaqCategory $category){
        if ($user->can('backend.faq-category.edit') || $user->id == $category->created_by) {
            return true;
        }
    }

    /**
     * Determine whether the user can update the model.
     *
     * @return mixed
     */
    public function update(User $user, FaqCategory $category)
    {
        if ($user->can('backend.faq-category.edit')) {
            return true;
        }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @return mixed
     */
    public function delete(User $user, FaqCategory $category)
    {
        if ($user->can('backend.faq-category.destroy')) {
            return true;
        }
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @return mixed
     */
    public function restore(User $user, FaqCategory $category)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @return mixed
     */
    public function forceDelete(User $user, FaqCategory $category)
    {
        //
    }
}
