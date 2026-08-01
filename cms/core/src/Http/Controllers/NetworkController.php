<?php

namespace Cms\Core\Http\Controllers;

use Cms\Core\Models\CmsSite;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class NetworkController extends Controller
{
    public function index()
    {
        $sites = CmsSite::all();
        return view('cms-core::settings.phase9.network', compact('sites'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'domain' => 'nullable|string|unique:cms_sites,domain',
            'path' => 'nullable|string|alpha_dash',
        ]);

        CmsSite::create([
            'name' => $request->name,
            'domain' => $request->domain,
            'path' => $request->path,
            'is_active' => true,
        ]);

        return redirect()->route('cms.network.index')->with('success', 'Site added to network successfully.');
    }

    public function toggleActive($id)
    {
        $site = CmsSite::findOrFail($id);
        if ($site->id === 1) {
            return redirect()->route('cms.network.index')->with('error', 'Cannot disable the primary site.');
        }

        $site->update(['is_active' => !$site->is_active]);

        return redirect()->route('cms.network.index')->with('success', 'Site status updated successfully.');
    }

    public function destroy($id)
    {
        $site = CmsSite::findOrFail($id);
        if ($site->id === 1) {
            return redirect()->route('cms.network.index')->with('error', 'Cannot delete the primary site.');
        }

        $site->delete();

        return redirect()->route('cms.network.index')->with('success', 'Site deleted from network successfully.');
    }
}
