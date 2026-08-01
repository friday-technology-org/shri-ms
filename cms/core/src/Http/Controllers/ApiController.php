<?php

namespace Cms\Core\Http\Controllers;

use Cms\Core\Models\Post;
use Cms\Core\Models\PostType;
use Cms\Core\Models\Term;
use Cms\Core\Models\Media;
use Cms\Core\Models\Comment;
use Cms\Core\Models\ApiToken;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;

class ApiController extends Controller
{
    // Generate an API token for the logged-in user
    public function generateToken(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $plainTextToken = Str::random(40);
        $hashedToken = hash('sha256', $plainTextToken);

        $apiToken = ApiToken::create([
            'user_id' => auth()->id() ?: 1, // Fallback to user ID 1 for testing if guest
            'name' => $request->name,
            'token' => $hashedToken,
            'abilities' => ['*'],
        ]);

        return response()->json([
            'token_id' => $apiToken->id,
            'token_name' => $apiToken->name,
            'plain_token' => $plainTextToken,
            'message' => 'Please store this token safely. It will not be shown again.',
        ], 201);
    }

    public function getTokens()
    {
        $tokens = ApiToken::where('user_id', auth()->id() ?: 1)->get();
        return response()->json($tokens);
    }

    public function revokeToken($id)
    {
        ApiToken::where('user_id', auth()->id() ?: 1)->where('id', $id)->delete();
        return response()->json(['message' => 'Token revoked successfully.']);
    }

    // Public REST Endpoints
    public function posts(Request $request)
    {
        $query = Post::where('status', 'published');

        if ($request->has('post_type')) {
            $query->where('post_type', $request->post_type);
        } else {
            $query->where('post_type', 'post');
        }

        if ($request->has('category')) {
            $query->whereHas('terms', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        return response()->json($query->latest()->paginate(10));
    }

    public function post($id)
    {
        $post = Post::where('status', 'published')
            ->where(function ($q) use ($id) {
                $q->where('id', $id)->orWhere('slug', $id);
            })->firstOrFail();

        return response()->json([
            'post' => $post,
            'meta' => $post->metadata ?: [],
            'featured_image' => $post->featured_image_id ? Media::find($post->featured_image_id) : null,
        ]);
    }

    public function pages()
    {
        $pages = Post::where('post_type', 'page')->where('status', 'published')->latest()->get();
        return response()->json($pages);
    }

    public function terms($taxonomy)
    {
        $terms = Term::whereHas('taxonomy', function ($q) use ($taxonomy) {
            $q->where('name', $taxonomy);
        })->get();

        return response()->json($terms);
    }

    public function media()
    {
        return response()->json(Media::latest()->paginate(20));
    }

    public function comments(Request $request)
    {
        $query = Comment::where('status', 'approved');
        if ($request->has('post_id')) {
            $query->where('post_id', $request->post_id);
        }
        return response()->json($query->latest()->get());
    }

    public function settings()
    {
        return response()->json([
            'site_name' => cms_option('site_name', 'LaraCMS'),
            'site_tagline' => cms_option('site_tagline', 'WordPress-equivalent CMS on Laravel'),
            'site_url' => url('/'),
        ]);
    }
}
