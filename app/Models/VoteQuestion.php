<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class VoteQuestion extends Model
{
    /**
    * No timestamps
    *
    * @var string
    */
   public $timestamps = false;

   /**
    * The table associated with the model.
    *
    * @var string
    */
   protected $table = 'user_vote_question';

    /**
    * The table associated with the model.
    *
    * @var string
    */
    protected $primaryKey = 'id';
}
