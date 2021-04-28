<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoteAnswer extends Model
{
    use HasCompositePrimaryKey;

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
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The composite primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = array('"user_id"', 'answer_id');
}
