<?php

namespace App\Models\Portfolio;

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
    protected $table = 'portfolio_categories';

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
     * The work that belong to the category.
     */
    public function works()
    {
        return $this
            ->belongsToMany('App\Models\Portfolio\Work', 'portfolio_work_categories', 'portfolio_category_id', 'portfolio_work_id')
            ->using('App\Models\Portfolio\WorkCategory');
    }
}
