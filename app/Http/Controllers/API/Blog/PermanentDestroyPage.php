<?php

namespace App\Http\Controllers\API\Blog;

use App\Models\Blog\Page;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PermanentDestroyPage extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $data = Page::withTrashed()
            ->whereIn('id', $request->selectedData)
            ->forceDelete();
        return $this->dataDeleted($data);
    }
}
