<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    /**
     * No 'create' and 'update' timestamps.
     *
     * @var boolean
     */
    public $timestamps = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user';

    /**
     * The attributes that are mass assignable.
     * 
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'bio'
    ];

    public function questions() {
        return $this->hasManyThrough('App\Models\Question', 'App\Models\Content');
    }

    public function contents() {
        return $this->hasMany('App\Models\Content', 'author_id');
    }

    /*
    public function answers() {
        return $this->hasManyThrough('App\Models\Answer');
    }

    public function followTags() {
        return $this->hasMany('App\Models\Tag');
    }
    */
}
