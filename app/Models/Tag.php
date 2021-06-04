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
        return $this->belongsToMany('App\Models\User', 'follow_tag', 'tag_id', 'user_id');
    }

    public function author() {
        return $this->belongsTo('App\Models\User');
    }

    public function popularity() {
        return -(count($this->questions) + count($this->followers));
    }

    public static function search($query_string, $rpp, $page) {
        $query = Tag::query()
        ->select('t.*', 'rank')
        ->distinct();

        $query->fromRaw(
            "tag t,
            ts_rank_cd(to_tsvector('simple', t.name), 
                plainto_tsquery('simple', ?)) as rank",
            [ $query_string ]
        );

        if($query_string !== '') {
            $query->whereRaw("rank > 0");
            $query->order("rank");
        }

        return self::paginateQuery($query, $rpp, $page);
    }

    public static function searchSimple($query_string, $rpp, $page) {
        return DB::select(
			"SELECT t.id, t.name, t.description, ts_rank_cd(to_tsvector(t.name), plainto_tsquery('simple', :query)) as rank
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

    public static function searchFull($query_string, $rpp, $page, $type='follows') {
        $query = Tag::query();


        if($type === 'follows') {
            $query->select('t.*', DB::raw('count(ft) as cft'))->distinct();

            if($query_string !== '') {
                $query->whereRaw("rank > 0");
            }

            $query->fromRaw(
                "tag t inner join follow_tag ft on t.id = ft.tag_id,
                ts_rank_cd(setweight(to_tsvector('simple', t.name), 'A') || ' ' || 
                    setweight(to_tsvector('simple', t.description), 'C'), 
                    plainto_tsquery('simple', ?)) as rank",
                [ $query_string ]
            );

            $query->groupBy('t.id');
            $query->orderBy('cft', 'desc');

        } else if($type === 'questions') {
            $query->select('t.*', DB::raw('count(qt) as cqt'))->distinct();
            
            if($query_string !== '') {
                $query->whereRaw("rank > 0");
            }

            $query->fromRaw(
                "tag t inner join question_tag qt on t.id = qt.tag_id,
                ts_rank_cd(setweight(to_tsvector('simple', t.name), 'A') || ' ' || 
                    setweight(to_tsvector('simple', t.description), 'C'), 
                    plainto_tsquery('simple', ?)) as rank",
                [ $query_string ]
            );

            $query->groupBy('t.id');
            $query->orderBy('cqt', 'desc');

        } else {
            $query->select('t.*', 'rank')->distinct();

            if($query_string !== '') {
                $query->whereRaw("rank > 0");
                $query->order("rank");
            }

            $query->fromRaw(
                "tag t,
                ts_rank_cd(setweight(to_tsvector('simple', t.name), 'A') || ' ' || 
                    setweight(to_tsvector('simple', t.description), 'C'), 
                    plainto_tsquery('simple', ?)) as rank",
                [ $query_string ]
            );

            $query->orderBy('t.name', 'asc');

        }

        return self::paginateQuery($query, $rpp, $page);
    }

    public static function paginateQuery($query, $rpp, $page) {
        return [
            'count' => count($query->get()),
            'data' => $query
                ->offset($rpp*($page-1))
                ->limit($rpp)
                ->get()
                ->toArray()
        ];
    }

    public function scopeOrder($query, $order) {
        if ($order != null) {
            if (strcmp($order, "alpha") == 0)
                $query->orderBy('t.name', 'asc');
            else if (strcmp($order, "similar") == 0)
                $query->orderBy('rank', 'desc');
        }
    }

    public static function sortPopularity(Tag $tag1, Tag $tag2) {
        $pop1 = $tag1->popularity();
        $pop2 = $tag2->popularity();

        if ($pop1 == $pop2) {
            return 0;
        }

        return ($pop1 < $pop2) ? -1 : 1;
    }
}
