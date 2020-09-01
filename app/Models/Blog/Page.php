<?php

namespace App\Models\Blog;

use Datakrama\Eloquid\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Page extends Model implements HasMedia
{
    use Uuids, InteractsWithMedia, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'blog_pages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'slug', 'content'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['thumbnail'];

    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getThumbnailAttribute()
    {
        if ($this->getFirstMedia('thumbnail')) {
            return $this->getFirstMediaUrl('thumbnail', 'thumb');
        }
        return '';
    }

    /**
     * Register media collections
     *
     * @return void
     */
    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('thumbnail')
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
        $this->addMediaConversion('thumb')
                ->fit(Manipulations::FIT_CROP, 512, 512);
    }
}
