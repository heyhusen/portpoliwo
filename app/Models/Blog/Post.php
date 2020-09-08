<?php

namespace App\Models\Blog;

use Datakrama\Eloquid\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use Uuids, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'blog_posts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'slug', 'summary', 'content'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['thumbnail'];

    /**
     * Get the post's thumbnail.
     *
     * @return string
     */
    public function getThumbnailAttribute()
    {
        if ($this->image == 'default.png') {
            return asset('assets/images/undraw_Photo_re_5blb.png');
        }
        return asset('storage/blog/' . $this->image);
    }

    /**
     * The category that belong to the post.
     */
    public function categories()
    {
        return $this
                    ->belongsToMany('App\Models\Blog\Category', 'blog_post_categories', 'blog_post_id', 'blog_category_id')
                    ->using('App\Models\Blog\PostCategory');
    }

    /**
     * The tag that belong to the post.
     */
    public function tags()
    {
        return $this
                    ->belongsToMany('App\Models\Blog\Tag', 'blog_post_tags', 'blog_post_id', 'blog_tag_id')
                    ->using('App\Models\Blog\PostTag');
    }
}
