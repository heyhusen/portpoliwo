<?php

namespace App\Http\Controllers\API\Portfolio;

use App\Http\Controllers\Controller;
use App\Models\Portfolio\Work;
use Illuminate\Http\Request;

class UploadWorkPhoto extends Controller
{
    public function __invoke(Request $request, Work $model)
    {
        if ($request->hasFile('photo')) {
            $model
                ->addMedia($request->file('photo'))
                ->toMediaCollection('image');
        }
    }
}
