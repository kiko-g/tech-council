<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionTag extends Model
{
    use Traits\HasCompositePrimaryKey;

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
    protected $table = 'question_tag';

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
    protected $primaryKey = array('tag_id', 'question_id');

    public function tag() {
        return $this->belongsTo('App\Models\Tag');
    }
}
