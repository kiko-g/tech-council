<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class LoginPolicy
{
  use HandlesAuthorization;

  public function logged_in($user)
  {
    return Auth::check() && (Auth::user()->id == $user->id);
  }
}