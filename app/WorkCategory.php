<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Datakrama\Eloquid\Traits\Uuids;

class WorkCategory extends Pivot
{
    use Uuids;

    /**
     * Get the work that owns the work category.
     */
    public function work()
    {
        return $this->belongsTo('App\Work');
    }
}
