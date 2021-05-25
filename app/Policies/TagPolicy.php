<?php

namespace App\Policies;

use App\Models\Tag;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class TagPolicy
{
    use HandlesAuthorization;

    use HandlesAuthorization;

    public function create() {
        //TODO: create tag
        return Auth::check();
    }

    public function edit(User $user, Tag $tag) {
        return $user->id == $tag->id || $user->moderator;
    }

    public function delete(User $user, Tag $tag) {
        return $user->id == $tag->id || $user->moderator;
    }
}
