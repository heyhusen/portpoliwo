<?php

namespace App\Models\Blog;

use Datakrama\Eloquid\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use Uuids;

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
    protected $fillable = ['title', 'slug', 'summary', 'content', 'image'];

    /**
     * The category that belong to the post.
     */
    public function categories()
    {
        return $this->belongsToMany('App\Models\Blog\Category', 'blog_post_categories')->using('App\Models\Blog\PostCategory');
    }

    /**
     * The tag that belong to the post.
     */
    public function tags()
    {
        return $this->belongsToMany('App\Models\Blog\Tag', 'blog_post_tags')->using('App\Models\Blog\PostTag');
    }
}
