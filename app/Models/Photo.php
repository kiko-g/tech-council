<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
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
    protected $table = 'photo';
}
