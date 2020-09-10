<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Blog\Post;
use App\Models\Portfolio\Work;
use App\Models\SocialMedia;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [
            'user' => [
                'count' => User::count(),
            ],
            'blogPost' => [
                'count' => Post::count(),
            ],
            'portfolioWork' => [
                'count' => Work::count(),
            ],
            'socialMedia' => [
                'count' => SocialMedia::count(),
            ]
        ];
        return $this->successResponse($data);
    }
}
