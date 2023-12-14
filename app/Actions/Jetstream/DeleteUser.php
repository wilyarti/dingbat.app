<?php

namespace App\Actions\Jetstream;

use Laravel\Jetstream\Contracts\DeletesUsers;

class DeleteUser implements DeletesUsers
{
    /**
     * Delete the given user.
     *
     * @param  mixed  $user
     * @return void
     */
    public function delete($user)
    {

        /*
         * Log Activity
         */
        activity()
            ->inLog("user_delete")
            ->withProperties(['name' => $user->name, "id" => $user->id])
            ->log( $user->name . " just deleted their account.");

        $user->deleteProfilePhoto();
        $user->tokens->each->delete();
        $user->delete();
    }
}
