<?php

namespace Cms\Core\Http\Controllers;

use Cms\Core\Services\UpgradeService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class UpgradeController extends Controller
{
    protected UpgradeService $upgradeService;

    public function __construct(UpgradeService $upgradeService)
    {
        $this->upgradeService = $upgradeService;
    }

    public function index()
    {
        $info = $this->upgradeService->checkVersion();
        return view('cms-core::settings.phase9.updates', compact('info'));
    }

    public function upgrade(Request $request)
    {
        $updateDir = storage_path('cms-updates');
        if (!\Illuminate\Support\Facades\File::isDirectory($updateDir)) {
            \Illuminate\Support\Facades\File::makeDirectory($updateDir, 0755, true);
        }

        $zipPath = $updateDir . '/core-update-' . time() . '.zip';

        if ($request->hasFile('update_zip')) {
            $request->validate([
                'update_zip' => 'required|file|mimes:zip|max:50000',
            ]);
            $request->file('update_zip')->move($updateDir, basename($zipPath));
        } else {
            // Fetch from GitHub
            $info = $this->upgradeService->checkVersion();
            $downloadUrl = $info['download_url'];
            
            $response = \Illuminate\Support\Facades\Http::timeout(120)->get($downloadUrl);
            if ($response->successful()) {
                \Illuminate\Support\Facades\File::put($zipPath, $response->body());
            } else {
                return redirect()->back()->with('error', 'Failed to download update from GitHub.');
            }
        }

        $result = $this->upgradeService->performUpgrade($zipPath);

        if ($result['success']) {
            return redirect()->back()->with('success', $result['message']);
        }

        return redirect()->back()->with('error', $result['message']);
    }
}
