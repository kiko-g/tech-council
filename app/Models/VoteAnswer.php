<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoteAnswer extends Model
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
    protected $table = 'user_vote_answer';
    
    /**
    * The table associated with the model.
    *
    * @var string
    */
    protected $primaryKey = 'id';
}
