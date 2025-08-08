<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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

    /**
     * Get dashboard statistics
     */
    public function getDashboardStats(Request $request)
    {
        try {
            $period = $request->get('period', 'week');
            
            // Basic stats
            $totalPosts = Post::count();
            $totalComments = \App\Models\Comment::count();
            $totalLikes = \App\Models\Like::count();
            $totalUsers = \App\Models\User::count();
            
            // Today's stats
            $postsToday = Post::whereDate('created_at', today())->count();
            $commentsToday = \App\Models\Comment::whereDate('created_at', today())->count();
            
            // Active users (users who posted in last 30 days)
            $activeUsers = \App\Models\User::whereHas('posts', function($query) {
                $query->where('created_at', '>=', now()->subDays(30));
            })->count();

            // Chart data based on period
            $postsData = [];
            $commentsData = [];
            
            switch($period) {
                case 'week':
                    for ($i = 6; $i >= 0; $i--) {
                        $date = now()->subDays($i);
                        $postsData[] = Post::whereDate('created_at', $date)->count();
                        $commentsData[] = \App\Models\Comment::whereDate('created_at', $date)->count();
                    }
                    break;
                case 'month':
                    for ($i = 29; $i >= 0; $i--) {
                        $date = now()->subDays($i);
                        $postsData[] = Post::whereDate('created_at', $date)->count();
                        $commentsData[] = \App\Models\Comment::whereDate('created_at', $date)->count();
                    }
                    break;
                case 'year':
                    for ($i = 11; $i >= 0; $i--) {
                        $month = now()->subMonths($i);
                        $postsData[] = Post::whereMonth('created_at', $month->month)->count();
                        $commentsData[] = \App\Models\Comment::whereMonth('created_at', $month->month)->count();
                    }
                    break;
            }

            return response()->json([
                'success' => true,
                'stats' => [
                    'total_posts' => $totalPosts,
                    'total_comments' => $totalComments,
                    'total_likes' => $totalLikes,
                    'total_users' => $totalUsers,
                    'active_users' => $activeUsers,
                    'posts_today' => $postsToday,
                    'comments_today' => $commentsToday,
                    'posts_data' => $postsData,
                    'comments_data' => $commentsData,
                    'avg_session_time' => '5m 30s' // Placeholder
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching dashboard stats: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching dashboard statistics'
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
            Log::error('Error fetching recent posts: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching recent posts'
            ], 500);
        }
    }
}
