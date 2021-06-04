<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserPolicy
{
  use HandlesAuthorization;

  public function logged_in(User $user)
  {
    return Auth::check() && (Auth::user()->id == $user->id);
  }
}