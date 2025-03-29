<?php

namespace App\Policies;

use App\Models\Queue;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class QueuePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Queue $queue): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isShop();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Queue $queue): bool
    {
        return $user->id == $queue->shop->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Queue $queue): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Queue $queue): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Queue $queue): bool
    {
        return false;
    }

    public function nextQueue(User $user, Queue $queue): bool{
        return $user->id == $queue->shop->user_id;
    }
}
