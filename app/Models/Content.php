<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'content';

    protected $fillable = [
        "main", "creation_date", "modification_date", "author_id", "edited", "search"
    ];

    public function author()
    {
        return $this->belongsTo('App\Models\User', 'author_id');
    }
}
