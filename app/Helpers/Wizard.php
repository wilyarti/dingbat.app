<?php

namespace App\Helpers;

use App\Models\wizards;

class Wizard
{
    public function isWizard($user) {
        $wizard = wizards::where('user_id',$user)->latest('updated_at')->first();
        if (!isset($wizard)) {
            return false;
        }
        if ($wizard->is_wizard) {
            return true;
        }
        return false;
    }
}
