<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Http\Requests\StoreBlogRequest;
use App\Http\Requests\UpdateBlogRequest;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $blogs = null;

        if ($request->has('type')) {
            if ($request->type == 'latest') {
                $blogs = Blog::latest()->limit(9)->get();
            } else if ($request->type == 'popular') {
                $blogs = Blog::where('is_popular', true)->simplePaginate(6);
            }
        } else {
            $blogs = Blog::simplePaginate(20);
        }

        return response()->json($blogs);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreBlogRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBlogRequest $request)
    {
        $blog = Blog::create($request->validated());
        $blog->addMediaFromRequest("photo")->toMediaCollection("photo");

        return response()->json($blog);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Blog $blog
     * @return \Illuminate\Http\Response
     */
    public function show(Blog $blog)
    {
        return response()->json($blog);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateBlogRequest $request
     * @param \App\Models\Blog $blog
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBlogRequest $request, Blog $blog)
    {
        $blog->update($request->validated());

        if ($request->has('photo')) {
            $blog->clearMediaCollection("photo");
            $blog->addMediaFromRequest("photo")->toMediaCollection("photo");
        }

        return response()->json($blog);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Blog $blog
     * @return \Illuminate\Http\Response
     */
    public function destroy(Blog $blog)
    {
        $blog->delete();

        return response()->json(['message' => 'Blog deleted!']);
    }
}
