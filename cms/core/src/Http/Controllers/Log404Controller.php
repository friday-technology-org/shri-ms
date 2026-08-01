<?php

namespace Cms\Core\Http\Controllers;

use Cms\Core\Models\Cms404Log;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;

class Log404Controller extends Controller
{
    /**
     * List tracked 404 logs.
     */
    public function index(): View
    {
        $logs = Cms404Log::latest()->paginate(20);
        return view('cms-core::settings.logs-404', compact('logs'));
    }

    /**
     * Delete all 404 logs.
     */
    public function destroyAll(): RedirectResponse
    {
        Cms404Log::truncate();
        return redirect()->route('cms.settings.logs404')->with('success', '404 logs cleared successfully.');
    }
}
