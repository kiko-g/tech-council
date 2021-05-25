<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class FollowTagPolicy
{
    use HandlesAuthorization;

    public function follow()
    {
        return Auth::check();
    }

    public function unfollow()
    {
        return Auth::check();
    }
}
