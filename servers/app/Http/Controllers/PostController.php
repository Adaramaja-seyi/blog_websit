<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    public function index()
    {
        try {
            $posts = Post::with(['user', 'comments.user'])
                ->latest()
                ->get();

            return response()->json([
                'success' => true,
                'data' => $posts
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching posts: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching posts'
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'is_published' => 'boolean'
            ]);

            $post = Auth::user()->posts()->create($validated);

            return response()->json([
                'success' => true,
                'data' => $post,
                'message' => 'Post created successfully'
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error creating post: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error creating post'
            ], 500);
        }
    }

    public function show(Post $post)
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $post->load(['user', 'comments.user'])
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching post: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Post not found'
            ], 404);
        }
    }

    public function update(Request $request, Post $post)
    {
        try {
            // Check if user owns the post
            if ($post->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to update this post'
                ], 403);
            }

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'is_published' => 'boolean'
            ]);

            $post->update($validated);

            return response()->json([
                'success' => true,
                'data' => $post,
                'message' => 'Post updated successfully'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error updating post: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating post'
            ], 500);
        }
    }

    public function destroy(Post $post)
    {
        try {
            // Check if user owns the post
            if ($post->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to delete this post'
                ], 403);
            }

            $post->delete();

            return response()->json([
                'success' => true,
                'message' => 'Post deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting post: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error deleting post'
            ], 500);
        }
    }
}
