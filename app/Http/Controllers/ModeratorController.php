<?php

namespace App\Http\Controllers;

use App\Models\Moderator;
use App\Models\Tag;
use App\Models\User;
use App\Models\UserReport;
use App\Models\ContentReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ModeratorController extends Controller
{
    /**
     * Display the moderator area.
     *
     * @return \Illuminate\Http\Response
     */
    public function showArea()
    {
        if (!Auth::check() || !Auth::user()->moderator)
            return redirect()->route('home');

        return view('pages.moderator', [
            'user' => Auth::user(),
            'displayed_users' => User::paginate(6),
            'displayed_tags' => Tag::paginate(10),
            'user_reports' => UserReport::paginate(10),
            'content_reports' => ContentReport::paginate(10)
        ]);
    }
}
