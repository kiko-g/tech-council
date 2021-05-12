<?php

namespace App\Http\Controllers;

use App\Models\Moderator;
use App\Models\User;
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
        return view('pages.moderator', [
            'user' => Auth::user(),
            'displayed_users' => User::paginate(6)
        ]);
    }
}
