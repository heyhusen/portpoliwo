<?php

namespace App\Models\Blog;

use Datakrama\Eloquid\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use Uuids;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'blog_categories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'slug', 'description'];

    /**
     * The post that belong to the category.
     */
    public function posts()
    {
        return $this->belongsToMany('App\Models\Blog\Post', 'blog_post_categories')->using('App\Models\Blog\PostCategory');
    }
}
