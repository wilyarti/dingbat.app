<?php

namespace App\Actions\Fortify;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\ResetsUserPasswords;

class ResetUserPassword implements ResetsUserPasswords
{
    use PasswordValidationRules;

    /**
     * Validate and reset the user's forgotten password.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return void
     */
    public function reset($user, array $input)
    {
        Validator::make($input, [
            'password' => $this->passwordRules(),
        ])->validate();

        /*
         * Log Activity
         */
        activity()
            ->inLog("user_reset_password")
            ->withProperties(['name' => $user->name, "id" => $user->id])
            ->log( $user->name . " just reset their password.");

        $user->forceFill([
            'password' => Hash::make($input['password']),
        ])->save();
    }
}
