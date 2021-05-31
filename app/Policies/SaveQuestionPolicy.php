<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class SaveQuestionPolicy
{
  use HandlesAuthorization;

  public function save()
  {
    return Auth::check();
  }

  public function unsave()
  {
    return Auth::check();
  }
}
