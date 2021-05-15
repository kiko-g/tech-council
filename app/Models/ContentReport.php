<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentReport extends Model
{
    /**
     * No 'create' and 'update' timestamps.
     *
     * @var boolean
     */
    public $timestamps = false;

    protected $table = 'content_report';
}
