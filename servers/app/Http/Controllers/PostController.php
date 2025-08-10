<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\like;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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

            // Generate slug from title
            $slug = Str::slug($validated['title']);

            // Ensure unique slug
            $originalSlug = $slug;
            $counter = 1;
            while (Post::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }

            $postData = array_merge($validated, [
                'slug' => $slug,
                'user_id' => Auth::id()
            ]);

            $post = Post::create($postData);

            return response()->json([
                'success' => true,
                'data' => $post,
                'message' => 'Post created successfully'
            ], 201);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Error creating post'  . $e->getMessage()
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
            return response()->json([
                'success' => false,
                'message' => 'Post not found'  . $e->getMessage()
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
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating post'  . $e->getMessage()
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
            return response()->json([
                'success' => false,
                'message' => 'Error deleting post'  . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get dashboard statistics
     */
    public function getDashboardStats(Request $request)
    {
        try {
            $user = $request->user();

            // Use withCount for fast stats
            $totalPosts = $user->posts()->count();
            $published = $user->posts()->where('is_published', true)->count();
            $drafts = $user->posts()->where('is_published', false)->count();
            $totalComments = $user->comments()->count();
            
            // Get likes count for user's posts
            $totalLikes = \App\Models\Like::whereIn('post_id', $user->posts()->pluck('id'))->count();

            // Paginate posts for dashboard (first 10)
            $posts = $user->posts()
                ->withCount(['comments', 'likes'])
                ->latest()
                ->limit(10)
                ->get();

            return response()->json([
                'success' => true,
                'stats' => [
                    'totalPosts' => $totalPosts,
                    'published' => $published,
                    'drafts' => $drafts,
                    'comments' => $totalComments,
                    'likes' => $totalLikes,
                ],
                'posts' => $posts,
                'tags' => [],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching dashboard statistics: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get recent posts for dashboard
     */
    public function getRecentPosts()
    {
        try {
            $posts = Post::with(['user'])
                ->latest()
                ->limit(5)
                ->get()
                ->map(function ($post) {
                    return [
                        'id' => $post->id,
                        'title' => $post->title,
                        'content' => $post->content,
                        'status' => $post->is_published ? 'published' : 'draft',
                        'created_at' => $post->created_at,
                        'user' => [
                            'name' => $post->user->name
                        ],
                        'comment_count' => $post->comments->count(),
                        'like_count' => $post->likes->count()
                    ];
                });

            return response()->json([
                'success' => true,
                'posts' => $posts
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching recent posts'
            ], 500);
        }
    }
}
