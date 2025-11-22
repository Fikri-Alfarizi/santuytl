<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ForumPost;

class ForumController extends Controller
{
    public function index()
    {
        $posts = ForumPost::latest()->paginate(20);
        return view('forum.index', compact('posts'));
    }
    public function show($id)
    {
        $post = ForumPost::findOrFail($id);
        return view('forum.show', compact('post'));
    }
    // ...store, update, destroy, like, dislike
}
