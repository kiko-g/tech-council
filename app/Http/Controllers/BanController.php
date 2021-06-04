<?php

namespace App\Http\Controllers;

use App\Models\Ban;
use App\Models\User;
use Illuminate\Http\Request;
use PDOException;
use Illuminate\Support\Facades\Auth;

class BanController extends Controller
{
    /**
     * Create a ban instance.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        try {
            User::findOrFail($request->user_id);
        } catch (PDOException $e) {
            abort('404', $e->getMessage());
        }

        try {
            $ban = new Ban();
            $ban->reason = "moderator ban";
            $ban->user_id = $request->user_id;
            $ban->moderator_id = Auth::user()->id;
            $ban->save();
        } catch (PDOException $e) {
            abort('303', $e->getMessage());
        }

        return response()->json(['id' => $request->user_id]);
    }
}
