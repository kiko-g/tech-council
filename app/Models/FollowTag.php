<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FollowTag extends Model
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
    protected $table = 'follow_tag';

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
    protected $primaryKey = array('tag_id', '"user_id"');

    public function tags() {
        return $this->belongsTo('App\Models\FollowTag');
    }
}
