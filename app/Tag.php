<?php

namespace App;

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
    protected $hidden = [
        'id',
    ];

    /**
     * The work that belong to the tag.
     */
    public function works()
    {
        return $this->belongsToMany('App\Work', 'work_tags')->using('App\WorkTag');
    }
}
