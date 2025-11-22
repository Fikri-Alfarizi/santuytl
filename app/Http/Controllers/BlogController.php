<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Display a listing of blog posts.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $posts = BlogPost::where('status', 'published')
            ->where(function ($query) {
                $query->whereNull('published_at')
                      ->orWhere('published_at', '<=', now());
            })
            ->orderBy('is_featured', 'desc')
            ->orderBy('published_at', 'desc')
            ->paginate(10);
        return view('blog.index', compact('posts'));
    }

    /**
     * Display the specified blog post.
     *
     * @param  string  $slug
     * @return \Illuminate\View\View
     */
    public function show($slug)
    {
        $post = BlogPost::with('author')
            ->where('slug', $slug)
            ->where('status', 'published')
            ->where(function ($query) {
                $query->whereNull('published_at')
                      ->orWhere('published_at', '<=', now());
            })
            ->firstOrFail();
        $relatedPosts = BlogPost::where('status', 'published')
            ->where('id', '!=', $post->id)
            ->where(function ($query) use ($post) {
                $query->whereJsonContains('categories', $post->categories[0] ?? null)
                      ->orWhere('author_id', $post->author_id);
            })
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get();
        return view('blog.show', compact('post', 'relatedPosts'));
    }

    /**
     * Search for blog posts.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function search(Request $request)
    {
        $query = $request->input('q');
        $posts = BlogPost::where('status', 'published')
            ->where(function ($query) {
                $query->whereNull('published_at')
                      ->orWhere('published_at', '<=', now());
            })
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('excerpt', 'like', "%{$query}%")
                  ->orWhere('content', 'like', "%{$query}%");
            })
            ->orderBy('published_at', 'desc')
            ->paginate(10);
        return view('blog.search', compact('posts', 'query'));
    }
}
