<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaveQuestion extends Model
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
    protected $table = 'saved_question';

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
    protected $primaryKey = array('user_id', 'question_id');

    public function question()
    {
        return $this->belongsTo('App\Models\Question');
    }
}
