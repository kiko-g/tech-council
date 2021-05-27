<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    public $timestamps = false;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'report';
    const MAX_DESCRIPTION_LENGTH = 1000;
    
    public function reporter() {
        return $this->belongsTo('App\Models\User', 'reporter_id');
    }
}
