<?php

namespace App\Models\Blog;

use Datakrama\Eloquid\Traits\Uuids;
use Illuminate\Database\Eloquent\Relations\Pivot;

class PostCategory extends Pivot
{
    use Uuids;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'blog_post_categories';
}
