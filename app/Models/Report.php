<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    /**
     * No 'create' and 'update' timestamps.
     *
     * @var boolean
     */
    public $timestamps = false;

    protected $table = 'report';

    public function reporter() {
        return $this->belongsTo('App\Models\User', 'reporter_id');
    }
}
