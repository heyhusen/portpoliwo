<?php

namespace App;

use Datakrama\Eloquid\Traits\Uuids;
use Illuminate\Database\Eloquent\Relations\Pivot;

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
