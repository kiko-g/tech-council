<?php

namespace App\Http\Controllers;

use App\Models\FollowTag;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDOException;

class FollowTagController extends Controller
{
    /**
     * Follow or unfollow a tag
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function follow(Request $request)
    {
        $user_id = Auth::user()->id;
        $tag_id = $request->id;

        //$this->authorize('delete', $tag); //TODO: follow policy
        $follow_tag = new FollowTag();
        $follow_tag->user_id = $user_id;
        $follow_tag->tag_id = $tag_id;

        try {
            $follow_tag->save();
        } catch (PDOException $e) {
            abort('403', $e->getMessage());
        }

        return response()->json(Tag::find($tag_id));
    }

    /**
     * Unfollow a tag
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function unfollow(Request $request)
    {
        $user_id = Auth::user()->id;
        $tag_id = $request->id;

        $follow_tag = FollowTag::find(['tag_id' => $tag_id, 'user_id' => $user_id]);

        //$this->authorize('delete', $follow_tag); //TODO: unfollow policy
        try {
            $follow_tag->delete();
        } catch (PDOException $e) {
            abort('403', $e->getMessage());
        }

        return response()->json(Tag::find($tag_id));
    }
}
