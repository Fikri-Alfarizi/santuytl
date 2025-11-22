<?php

namespace App\Http\Controllers;

use App\Models\ForumCategory;
use App\Models\ForumThread;
use App\Models\ForumPost;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class ForumController extends Controller
{
    public function index()
    {
        $categories = ForumCategory::orderBy('order')->get();
        return view('forum.index', compact('categories'));
    }

    public function showCategory($slug)
    {
        $category = ForumCategory::where('slug', $slug)->firstOrFail();
        $threads = $category->threads()
            ->with('user')
            ->withCount('posts')
            ->orderBy('is_pinned', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        return view('forum.category', compact('category', 'threads'));
    }

    public function showThread($categorySlug, $threadSlug)
    {
        $category = ForumCategory::where('slug', $categorySlug)->firstOrFail();
        $thread = ForumThread::where('slug', $threadSlug)
            ->where('category_id', $category->id)
            ->with(['user', 'posts.user', 'posts.likes'])
            ->firstOrFail();
        
        $thread->increment('views_count');
        
        return view('forum.thread', compact('category', 'thread'));
    }

    public function createThread($categorySlug)
    {
        $category = ForumCategory::where('slug', $categorySlug)->firstOrFail();
        return view('forum.create', compact('category'));
    }

    public function storeThread(Request $request, $categorySlug)
    {
        $category = ForumCategory::where('slug', $categorySlug)->firstOrFail();
        
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
        ]);

        $thread = ForumThread::create([
            'category_id' => $category->id,
            'user_id' => Auth::id(),
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . uniqid(),
            'content' => $request->content,
        ]);

        return redirect()->route('forum.thread.show', [$category->slug, $thread->slug])->with('success', 'Thread created!');
    }

    public function storePost(Request $request, $threadId)
    {
        $request->validate(['content' => 'required']);
        
        $thread = ForumThread::findOrFail($threadId);
        
        if ($thread->is_locked) {
            return back()->with('error', 'This thread is locked.');
        }

        ForumPost::create([
            'thread_id' => $thread->id,
            'user_id' => Auth::id(),
            'content' => $request->content,
        ]);

        return back()->with('success', 'Reply posted!');
    }
}
