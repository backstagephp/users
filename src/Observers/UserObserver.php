<?php

namespace Backstage\UserManagement\Observers;

class UserObserver
{
    /**
     * Handle the User "created" event.
     *
     * @param  \Backstage\UserManagement\Models\User  $user
     * @return void
     */
    public function created($user)
    {
        event(new \Backstage\UserManagement\Events\UserCreated($user));
    }

    /**
     * Handle the User "updated" event.
     *
     * @param  \Backstage\UserManagement\Models\User  $user
     * @return void
     */
    public function updated($user)
    {
        // Logic to handle after a user is updated
    }

    /**
     * Handle the User "deleted" event.
     *
     * @param  \Backstage\UserManagement\Models\User  $user
     * @return void
     */
    public function deleted($user)
    {
        // Logic to handle after a user is deleted
    }
}
