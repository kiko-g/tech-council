<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDOException;

class TagController extends Controller
{
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create(Request $request)
	{
		$tag = new Tag();

		$this->authorize('create', $tag);

		$tag->name = $request->input('name');
		$tag->description = $request->input('description');
		$tag->author_id = Auth::user()->id;
		$tag->save();

		return $tag;
	}

	/**
	 * Display the tag header.
	 *
	 * @param  \App\Models\Tag  $tag
	 * @return \Illuminate\Http\Response
	 */
	public function showHeader($id)
	{
		$tag = Tag::find($id);
		return view('partials.tag.card', ['tag' => $tag]);
	}

	/**
	 * Display the tag header.
	 *
	 * @param  \App\Models\Tag  $tag
	 * @return \Illuminate\Http\Response
	 */
	public function showPage($id)
	{
		$tag = Tag::find($id);
		return view('pages.tag', ['tag' => $tag, 'user' => Auth::user()]);
	}

	/**
	 * Display the tag header.
	 *
	 * @param  \App\Models\Tag  $tag
	 * @return \Illuminate\Http\Response
	 */
	public function showTable()
	{
		return view('partials.tag.table', [
			'tags' => DB::table('tags')->paginate(15)
		]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Request $request, $id)
	{
		$tag = Tag::findOrFail($id);

		$this->authorize('edit', $tag);

		$tag->name = $request->name;
		$tag->description = $request->description;

		try {
				$tag->save();
		} catch (PDOException $e) {
				abort('403', $e->getMessage());
		}

		return response()->json($tag);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Models\Tag  $tag
	 * @return \Illuminate\Http\Response
	 */
	public function delete($id)
	{
		$tag = Tag::find($id);

		$this->authorize('delete', $tag);

		try {
			$tag->delete();
		} catch (PDOException $e) {
				abort('403', $e->getMessage());
		}

		return response()->json($tag);
	}

	/**
	 * Search for a tag (full text search)
	 * 
	 * @param String $tag
	 * @return |Illuminate\Http\Response
	 */
	public function search($request)
	{
		$request->validate([
			'query' => ['required'. 'max:' . 100], #TODO: Make this a constant
			'rpp' => ['required', 'integer'],
			'page' => ['required', 'integer'],
			'type' =>[ function ($attribute, $value, $fail) {
				if($value != '' && $value != 'best' && $value != 'new' && $value != 'trending') {
                    $fail('The '.$attribute.' must be "best", "new" or "trending"');
				}
            }]
		]);

		$results = DB::select("
			SELECT t.name, t.description, rank
			FROM tag t,
			ts_rank_cd(to_tsvector(search), plainto_tsquery('english', :query)) AS rank
			WHERE search @@ plainto_tsquery('english', :query)
			ORDER BY rank DESC
			OFFSET :offset
			LIMIT :limit
		", [
			'query' => $request->query,
			'offset' => $request->rpp*($request->page - 1),
			'limit' => $request->page
		]);

		error_log(print_r($results));
	}
}
