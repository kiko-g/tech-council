<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserReport extends Model
{
    /**
     * No 'create' and 'update' timestamps.
     *
     * @var boolean
     */
    public $timestamps = false;

    protected $table = 'user_report';
}
