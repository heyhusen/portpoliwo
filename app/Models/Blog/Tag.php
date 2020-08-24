<?php

namespace App\Models\Blog;

use Datakrama\Eloquid\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use Uuids;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'blog_tags';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'slug', 'description'];

    /**
     * The post that belong to the tag.
     */
    public function posts()
    {
        return $this->belongsToMany('App\Models\Blog\Post', 'blog_post_tags')->using('App\Models\Blog\PostTag');
    }
}
