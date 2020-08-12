<?php

namespace App\Models;

use Datakrama\Eloquid\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
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
    protected $hidden = [];

    /**
     * The work that belong to the tag.
     */
    public function works()
    {
        return $this->belongsToMany('App\Models\Work', 'work_tags')->using('App\Models\WorkTag');
    }
}
