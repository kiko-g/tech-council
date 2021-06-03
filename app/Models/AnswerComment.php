<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AnswerComment extends Model
{
    protected $table = 'answer_comment';
    protected $primaryKey = 'content_id';

    public function content()
    {
        return $this->belongsTo('App\Models\Content', 'content_id');
    }

    public function isReportedByUser()
    {
        //TODO:
        if (!Auth::check())
            return false;

        $content_report = DB::table('content_report')
        ->join('report', 'content_report.report_id', '=', 'report.id')
        ->where('content_id', $this->content_id)
            ->where('reporter_id', Auth::user()->id)
            ->get();

        return count($content_report) > 0 ? true : false;
    }
}
