<?php

namespace App\Policies;

use App\Models\Authentication;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FormPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function access($role_id,  $form_id, string $type = 'view')
    {
        $auth = Authentication::where('user_id', $role_id)
            ->where('form_id', $form_id)
            ->first();

        if (!$auth) {
            return false;
        }

        if ($type === 'view') {
            return in_array($auth->access_level, [1, 2]);
        } elseif ($type === 'edit') {
            return $auth->access_level === 1;
        }

        return false;
    }
}
