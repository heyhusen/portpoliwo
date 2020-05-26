<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Datakrama\Eloquid\Traits\Uuids;

class Category extends Model
{
    use Uuids;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['created_at', 'updated_at'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id',
    ];

    /**
     * The work that belong to the category.
     */
    public function works()
    {
        return $this->belongsToMany('App\Work', 'work_categories')->using('App\WorkCategory');
    }
}
