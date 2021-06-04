<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\ContentReport;
use App\Models\UserReport;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDOException;

class ReportController extends Controller
{    
    public function reportContent(Request $request, $content_id)
    {
        Content::findOrFail($content_id);

        $this->authorize('create', Report::class);
        $request->validate(['description' => 'required|max:' . Report::MAX_DESCRIPTION_LENGTH]);

        $report = new Report();
        $report->description = $request->input('description');
        $report->reporter_id = Auth::user()->id;

        try {
            DB::transaction(function () use ($report, $content_id) {
                $report->save();
                $content_report = new ContentReport();
                $content_report->report_id = $report->id;
                $content_report->content_id = $content_id;
                $content_report->save();
            });
        } catch (PDOException $e) {
            error_log($e->getMessage());
            abort(403, $e->getMessage());
        }
        
        // TODO: Add notification here!
        $response = response()->json(['report' => $report, 'content_id' => $content_id]);
        return $response;
    }

    public function reportUser(Request $request, $user_id)
    {
        User::findOrFail($user_id);

        $this->authorize('create', Report::class);
        
        $request->validate(['description' => 'required|max:' . Report::MAX_DESCRIPTION_LENGTH]);

        $report = new Report();
        $report->description = $request->input('description');
        $report->reporter_id = Auth::user()->id;

        try {
            DB::transaction(function () use ($report, $user_id) {
                $report->save();
                $user_report = new UserReport();
                $user_report->report_id = $report->id;
                $user_report->user_id = $user_id;
                $user_report->save();
            });
        } catch (PDOException $e) {
            error_log($e->getMessage());
            abort(403, $e->getMessage());
        }

        $response = response()->json(['report' => $report, 'user_id' => $user_id]);
        return $response;
    }

    public function solve($id) {
        try {
            $report = Report::findOrFail($id);
            $report->solved = true;
            $report->save();
        } catch (PDOException $e) {
            abort('404', $e->getMessage());
        }
        return response()->json(['id' => $id]);
    }

    
}
