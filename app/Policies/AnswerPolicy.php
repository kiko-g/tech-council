<?php

namespace App\Policies;

use App\Models\Answer;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class AnswerPolicy
{
    use HandlesAuthorization;

    public function create()
    {
        return Auth::check();
    }

    public function edit(User $user, Answer $answer) {
        return $user->id == $answer->content->author_id;
    }

    public function delete(User $user, Answer $answer) {
        return $user->id == $answer->content->author_id || $user->moderator;
    }
}
