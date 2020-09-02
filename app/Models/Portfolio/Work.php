<?php

namespace App\Models\Portfolio;

use Datakrama\Eloquid\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Work extends Model implements HasMedia
{
    use Uuids, InteractsWithMedia;

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
        return $this->getFirstMediaUrl('photo', 'thumb');
    }

    /**
     * The category that belong to the work.
     */
    public function categories()
    {
        return $this
            ->belongsToMany('App\Models\Portfolio\Category', 'portpoliwo_work_categories', 'portfolio_work_id', 'portfolio_category_id')
            ->using('App\Models\Portfolio\WorkCategory')
            ->withTimestamps();
    }

    /**
     * The tag that belong to the work.
     */
    public function tags()
    {
        return $this
            ->belongsToMany('App\Models\Portfolio\Tag', 'portpoliwo_work_tags', 'portfolio_work_id', 'portfolio_tag_id')
            ->using('App\Models\Portfolio\WorkTag')
            ->withTimestamps();
    }

    /**
     * Register media collections
     *
     * @return void
     */
    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('photo')
            ->useFallbackUrl('/assets/images/undraw_Portfolio_update_re_jqnp.png')
            ->useFallbackPath(public_path('/assets/images/undraw_Portfolio_update_re_jqnp.png'))
            ->singleFile();
    }

    /**
     * Register media conversions
     *
     * @param Media $media
     * @return void
     */
    public function registerMediaConversions(Media $media = null): void
    {
        $this
            ->addMediaConversion('thumb')
            ->fit(Manipulations::FIT_CROP, 1280, 720);
    }
}
