<?php

namespace App\Models\Blog;

use Datakrama\Eloquid\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    use HasFactory;
    use Uuids;
    use SoftDeletes;

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
        if ($this->image == 'default.png') {
            return asset('assets/images/undraw_Photo_re_5blb.png');
        }
        return asset('storage/blog/page/' . $this->image);
    }
}
