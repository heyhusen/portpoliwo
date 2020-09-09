<?php

namespace App\Models\Portfolio;

use Datakrama\Eloquid\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Work extends Model
{
    use Uuids, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'portfolio_works';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'url'
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
     * Get the work's image.
     *
     * @return string
     */
    public function getImageAttribute()
    {
        if ($this->photo == 'default.png') {
            return asset('assets/images/undraw_Photo_re_5blb.png');
        }
        return asset('storage/portfolio/' . $this->photo);
    }

    /**
     * The category that belong to the work.
     */
    public function categories()
    {
        return $this
            ->belongsToMany('App\Models\Portfolio\Category', 'portfolio_work_categories', 'portfolio_work_id', 'portfolio_category_id')
            ->using('App\Models\Portfolio\WorkCategory')
            ->withTimestamps();
    }

    /**
     * The tag that belong to the work.
     */
    public function tags()
    {
        return $this
            ->belongsToMany('App\Models\Portfolio\Tag', 'portfolio_work_tags', 'portfolio_work_id', 'portfolio_tag_id')
            ->using('App\Models\Portfolio\WorkTag')
            ->withTimestamps();
    }
}
