<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserToken;
use Illuminate\Auth\Access\Response;

class UserTokenPolicy
{


    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        return $user->is_verified ?
            Response::allow() :
            Response::deny('You are not allowed to create tokens');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, UserToken $userToken)
    {
        return $user->id === $userToken->user_id ?
            Response::allow() :
            Response::deny('You are not allowed to delete this token');
    }

}
