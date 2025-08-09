<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\comment;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    public function getRecentComments()
    {
        // Get the 10 most recent comments with user and post info
        $comments = comment::with(['user', 'post'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'comments' => $comments
        ]);
    }
}
