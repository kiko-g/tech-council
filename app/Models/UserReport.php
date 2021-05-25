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

    public function report() {
        return $this->belongsTo('App\Models\Report', 'report_id');
    }

    public function reported_user() {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}
