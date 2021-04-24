<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Card;
use App\Models\Item;

use Illuminate\Auth\Access\HandlesAuthorization;

class ItemPolicy
{
    use HandlesAuthorization;

    public function create(User $user, Item $item)
    {
      // User can only create items in cards they own
      return $user->id == $item->card->user_id;
    }

    public function update(User $user, Item $item)
    {
      // User can only update items in cards they own
      return $user->id == $item->card->user_id;
    }

    public function delete(User $user, Item $item)
    {
      // User can only delete items in cards they own
      return $user->id == $item->card->user_id;
    }
}
