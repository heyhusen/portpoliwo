<?php

namespace App\Http\Controllers\API\Portfolio;

use App\Models\Portfolio\Work;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PermanentDestroyWork extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $data = Work::withTrashed()
            ->whereIn('id', $request->selectedData)
            ->forceDelete();
        return $this->dataDeleted($data);
    }
}
