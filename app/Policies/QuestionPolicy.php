<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Question;
use Illuminate\Auth\Access\HandlesAuthorization;

use Illuminate\Support\Facades\Auth;

class QuestionPolicy
{
    use HandlesAuthorization;

    public function create(User $user) {
        return Auth::check();
    }

    public function edit(User $user, Question $question) {
        return $user->id == $question->content->author_id;
    }

    public function delete(User $user, Question $question) {
        return $user->id == $question->content->author_id || $user->moderator;
    }
}
