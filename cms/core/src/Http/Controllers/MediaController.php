<?php

namespace Cms\Core\Http\Controllers;

use Cms\Core\Models\Media;
use Cms\Core\Services\MediaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MediaController extends Controller
{
    public function __construct(protected MediaService $mediaService) {}

    /**
     * Return JSON list of media items (for picker modal AJAX).
     */
    public function items(Request $request): JsonResponse
    {
        $query = Media::latest();

        if ($request->filled('type')) {
            match ($request->type) {
                'images'    => $query->images(),
                'videos'    => $query->videos(),
                'audio'     => $query->audio(),
                'documents' => $query->documents(),
                default     => null,
            };
        }

        if ($request->filled('search')) {
            $query->where('original_filename', 'like', '%' . $request->search . '%');
        }

        $items = $query->limit(100)->get()->map(fn($m) => $this->mediaToArray($m));

        return response()->json(['items' => $items]);
    }

    /**
     * Show the media library index page.
     */
    public function index(Request $request): View
    {
        $query = Media::with('uploader')->latest();

        // Filter by type
        if ($request->filled('type')) {
            match ($request->type) {
                'images'    => $query->images(),
                'videos'    => $query->videos(),
                'audio'     => $query->audio(),
                'documents' => $query->documents(),
                default     => null,
            };
        }

        // Filter by search
        if ($request->filled('search')) {
            $query->where('original_filename', 'like', '%' . $request->search . '%');
        }

        $media = $query->paginate(30)->withQueryString();

        return view('cms-core::media.index', compact('media'));
    }

    /**
     * Handle file upload (AJAX / multipart POST).
     * Accepts single or multiple files.
     */
    public function upload(Request $request): JsonResponse
    {
        $request->validate([
            'files'   => 'required|array|min:1',
            'files.*' => 'file|max:51200', // 50MB
        ]);

        $uploaded = [];
        $errors   = [];

        foreach ($request->file('files') as $file) {
            try {
                $media = $this->mediaService->store($file, Auth::id());
                $uploaded[] = $this->mediaToArray($media);
            } catch (\Exception $e) {
                $errors[] = $file->getClientOriginalName() . ': ' . $e->getMessage();
            }
        }

        return response()->json([
            'success'  => count($uploaded) > 0,
            'uploaded' => $uploaded,
            'errors'   => $errors,
        ]);
    }

    /**
     * Return JSON details of a single media item (for modal/detail panel).
     */
    public function show(Media $media): JsonResponse
    {
        return response()->json($this->mediaToArray($media));
    }

    /**
     * Update media metadata (alt text, caption, description).
     */
    public function update(Request $request, Media $media): JsonResponse
    {
        $validated = $request->validate([
            'alt_text'    => 'nullable|string|max:500',
            'caption'     => 'nullable|string|max:1000',
            'description' => 'nullable|string',
        ]);

        $media->update($validated);

        return response()->json([
            'success' => true,
            'media'   => $this->mediaToArray($media->fresh()),
        ]);
    }

    /**
     * Delete a media item (file + DB record).
     */
    public function destroy(Media $media): JsonResponse
    {
        try {
            $this->mediaService->delete($media);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    // ─── Helper ──────────────────────────────────────────────────────────────

    protected function mediaToArray(Media $media): array
    {
        return [
            'id'                => $media->id,
            'original_filename' => $media->original_filename,
            'mime_type'         => $media->mime_type,
            'extension'         => $media->extension,
            'size'              => $media->size,
            'human_size'        => $media->humanSize(),
            'width'             => $media->width,
            'height'            => $media->height,
            'alt_text'          => $media->alt_text,
            'caption'           => $media->caption,
            'description'       => $media->description,
            'url'               => $media->url(),
            'thumbnail_url'     => $media->thumbnailUrl('thumbnail'),
            'medium_url'        => $media->thumbnailUrl('medium'),
            'is_image'          => $media->isImage(),
            'is_video'          => $media->isVideo(),
            'is_audio'          => $media->isAudio(),
            'uploader'          => $media->uploader?->name,
            'created_at'        => $media->created_at->format('M j, Y'),
        ];
    }
}
