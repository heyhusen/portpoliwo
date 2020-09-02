<?php

namespace App\Models\Portfolio;

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
    protected $table = 'portfolio_tags';

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
        return $this
            ->belongsToMany('App\Models\Portfolio\Work', 'portfolio_work_tags', 'portfolio_tag_id', 'portfolio_work_id')
            ->using('App\Models\Portfolio\WorkTag');
    }
}
