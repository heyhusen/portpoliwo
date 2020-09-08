<?php

namespace App\Http\Controllers\API\Blog;

use App\Models\Blog\Page;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RestorePage extends Controller
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
            ->restore();
        return $this->dataRestored($data);
    }
}
