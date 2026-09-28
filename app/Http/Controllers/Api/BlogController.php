<?php

namespace App\Http\Controllers\Api;

use App\Models\Blog;
use Illuminate\Http\Request;
use App\Http\Resources\BlogResource;
use App\Http\Controllers\Api\Controller;

class BlogController extends Controller
{
    public function all_blogs()
    {
        return BlogResource::collection(Blog::latest()->active()->get())->additional([
                'result' => true
            ]);
    }

    public function blog_details(Request $request)
    {
        $blog = Blog::where('slug', $request->slug)->first();
        return (new BlogResource($blog))->additional([
                'result' => true
            ]);
    }
}
