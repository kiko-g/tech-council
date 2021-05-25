<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Moderator extends Model
{
    /**
     * No 'create' and 'update' timestamps.
     *
     * @var boolean
     */
    public $timestamps = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'moderator';

    /**
     * Table's primary key
     * 
     * @var string
     */
    public $primaryKey = '"user_id"';

    /**
     * The attributes that are mass assignable.
     * 
     * @var array
     */
    protected $fillable = [
        'user_id'
    ];

    public function user() {
        return $this->belongsTo('App\Models\User', '"user_id"');
    }
}
