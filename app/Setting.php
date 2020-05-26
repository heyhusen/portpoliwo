<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Datakrama\Eloquid\Traits\Uuids;

class Setting extends Model
{
    use Uuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'value',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id',
    ];
}
