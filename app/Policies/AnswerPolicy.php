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
        return Auth::check() && !Auth::user()->banned;
    }

    public function edit(User $user, Answer $answer) {
        return $user->id == $answer->content->author_id;
    }

    public function delete(User $user, Answer $answer) {
        return $user->id == $answer->content->author_id || $user->moderator;
    }

    public function set_best(User $user, Answer $answer) {
        print_r($answer->question->bestAnswer());
        return $user->id == $answer->question->content->author_id && is_null($answer->question->bestAnswer());
    }
}
