<?php

namespace App\Models\Portfolio;

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
    protected $hidden = [];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['image'];

    /**
     * Get the work category record associated with the work.
     */
    public function workCategory()
    {
        return $this->hasOne('App\Models\Portfolio\WorkCategory');
    }

    /**
     * Get the work tag for the work.
     */
    public function workTags()
    {
        return $this->hasMany('App\Models\Portfolio\WorkTag');
    }

    /**
     * The category that belong to the work.
     */
    public function categories()
    {
        return $this
            ->belongsToMany('App\Models\Portfolio\Category', 'work_categories')
            ->using('App\Models\Portfolio\WorkCategory')
            ->withTimestamps();
    }

    /**
     * The tag that belong to the work.
     */
    public function tags()
    {
        return $this
            ->belongsToMany('App\Models\Portfolio\Tag', 'work_tags')
            ->using('App\Models\Portfolio\WorkTag')
            ->withTimestamps();
    }

    /**
     * Get the work's image.
     *
     * @return string
     */
    public function getImageAttribute()
    {
        return asset('storage/work/' . $this->photo);
    }
}
