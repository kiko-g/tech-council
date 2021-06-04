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

        $user_results = User::search('', 6, 1);
        $tag_results = Tag::search('', 10, 1);

        return view('pages.moderator', [
            'user' => Auth::user(),
            'displayed_users' => User::hydrate($user_results["data"]),
            'user_count' => $user_results['count'],
            'displayed_tags' => Tag::hydrate($tag_results["data"]),
            'tag_count' => $tag_results['count'],
            'user_reports' => UserReport::paginate(10),
            'content_reports' => ContentReport::paginate(10)
        ]);
    }
}
