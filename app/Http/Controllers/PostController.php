<?php

namespace App\Http\Controllers;

use App\Models\Post;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('user')->orderByDesc('created_at')->orderBy('id')->get();

        return view('posts.index', compact('posts'));
    }

    // Return JSON list for AJAX read
    public function list()
    {
        $posts = Post::with('user')->orderByDesc('created_at')->orderBy('id')->get();
        return response()->json($posts);
    }

    // Store a new post (AJAX)
    public function store(\Illuminate\Http\Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $post = Post::create(array_merge($data, ['user_id' => auth()->id() ?? 1]));

        return response()->json(['success' => true, 'post' => $post], 201);
    }

    // Update an existing post (AJAX)
    public function update(\Illuminate\Http\Request $request, $id)
    {
        $post = Post::findOrFail($id);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $post->update($data);

        return response()->json(['success' => true, 'post' => $post]);
    }

    // Destroy a post (AJAX)
    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();
        return response()->json(['success' => true]);
    }
}
