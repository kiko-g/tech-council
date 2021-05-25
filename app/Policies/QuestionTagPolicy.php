<?php

namespace App\Policies;

use App\Models\Question;
use App\Models\QuestionTag;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class QuestionTagPolicy
{
    use HandlesAuthorization;

    /**
     * Add tag to question
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Question  $question
     * @return mixed
     */
    public function add(User $user, QuestionTag $questionTag)
    {
        return $user->id == Question::find($questionTag->question_id)->content->author_id;
    }

    /**
     * Remove tag from question
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Question  $question
     * @return mixed
     */
    public function remove(User $user, QuestionTag $questionTag)
    {
        return $user->id == Question::find($questionTag->question_id)->content->author_id || $user->moderator;
    }
}
