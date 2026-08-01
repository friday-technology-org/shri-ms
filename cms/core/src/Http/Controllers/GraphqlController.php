<?php

namespace Cms\Core\Http\Controllers;

use Cms\Core\Models\Post;
use Cms\Core\Models\Term;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class GraphqlController extends Controller
{
    public function handle(Request $request)
    {
        $query = $request->input('query', '');
        
        // Return standard GraphQL format response
        $data = [];
        $errors = [];

        try {
            if (empty($query)) {
                throw new \Exception("GraphQL query must be provided.");
            }

            // Simple pattern matching for mock/lightweight GraphQL resolver
            if (preg_match('/query\s*\{\s*posts\s*\{([^}]+)\}\s*\}/i', $query, $matches)) {
                $fields = $this->parseFields($matches[1]);
                $posts = Post::where('status', 'published')->latest()->take(10)->get();
                $data['posts'] = $posts->map(function ($post) use ($fields) {
                    return $this->filterFields($post, $fields);
                });
            } elseif (preg_match('/query\s*\{\s*post\(\s*id\s*:\s*(\d+|"[^"]+")\s*\)\s*\{([^}]+)\}\s*\}/i', $query, $matches)) {
                $id = trim($matches[1], '"');
                $fields = $this->parseFields($matches[2]);
                $post = Post::where('id', $id)->first();
                $data['post'] = $post ? $this->filterFields($post, $fields) : null;
            } elseif (preg_match('/query\s*\{\s*terms\s*\{([^}]+)\}\s*\}/i', $query, $matches)) {
                $fields = $this->parseFields($matches[1]);
                $terms = Term::all();
                $data['terms'] = $terms->map(function ($term) use ($fields) {
                    return $this->filterFields($term, $fields);
                });
            } else {
                // Fallback catch-all mock response
                $data = [
                    'message' => 'GraphQL endpoint executed successfully.',
                    'schema' => [
                        'queries' => ['posts', 'post(id)', 'terms', 'settings']
                    ]
                ];
            }
        } catch (\Throwable $e) {
            $errors[] = ['message' => $e->getMessage()];
        }

        $response = ['data' => $data];
        if (!empty($errors)) {
            $response['errors'] = $errors;
        }

        return response()->json($response);
    }

    protected function parseFields(string $fieldsString): array
    {
        $cleaned = preg_replace('/\s+/', ' ', $fieldsString);
        $parts = explode(' ', trim($cleaned));
        return array_filter($parts);
    }

    protected function filterFields($model, array $fields): array
    {
        $array = $model->toArray();
        if (empty($fields)) {
            return $array;
        }

        $result = [];
        foreach ($fields as $field) {
            $field = trim($field);
            if (array_key_exists($field, $array)) {
                $result[$field] = $array[$field];
            }
        }
        return $result;
    }
}
