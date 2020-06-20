<?php

namespace App;

use Datakrama\Eloquid\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;

class Work extends Model
{
    use Uuids;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'works';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'photo', 'url'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id',
    ];

    /**
     * Get the work category record associated with the work.
     */
    public function workCategory()
    {
        return $this->hasOne('App\WorkCategory');
    }

    /**
     * Get the work tag for the work.
     */
    public function workTags()
    {
        return $this->hasMany('App\WorkTag');
    }

    /**
     * The category that belong to the work.
     */
    public function category()
    {
        return $this->belongsToMany('App\Category', 'work_categories')->using('App\WorkCategory')->withTimestamps();
    }

    /**
     * The tag that belong to the work.
     */
    public function tags()
    {
        return $this->belongsToMany('App\Tag', 'work_tags')->using('App\WorkTag')->withTimestamps();
    }
}
