<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\ContentReport;
use App\Models\Report;
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
        return response()->json($report);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function show(Report $report)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function edit(Report $report)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Report $report)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function destroy(Report $report)
    {
        //
    }

    
}
