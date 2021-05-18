<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentReport extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'content_report';
    protected $primaryKey = 'content_id';
    const MAX_MAIN_LENGTH = 1000;


    public function report()
    {
        return $this->belongsTo('App\Models\Report', 'report_id');
    }

    public function content()
    {
        return $this->belongsTo('App\Models\Content', 'content_id');
    }


}
