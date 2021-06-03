<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Tag extends Model
{
    public $timestamps = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tag';

    public function questions() {
        return $this->belongsToMany('App\Models\Question', 'question_tag', 'tag_id', 'question_id');
    }

    public function followers() {
        return $this->belongsToMany('App\Models\User', 'follow_tag', 'tag_id', '"user_id"');
    }

    public function author() {
        return $this->belongsTo('App\Models\User');
    }

    public static function search($query_string, $rpp, $page) {
        return DB::select(
			"SELECT t.name, t.description, ts_rank_cd(to_tsvector(t.name), plainto_tsquery('simple', :query)) as rank
			FROM tag t
			WHERE t.name @@ plainto_tsquery('english', :query)
			ORDER BY rank DESC
			OFFSET :offset
			LIMIT :limit",
			[
				'query' => $query_string,
				'offset' => $rpp*($page - 1),
				'limit' => $rpp
			]
		);
    }
}
