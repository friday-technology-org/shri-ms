<?php

namespace Cms\Core\Http\Controllers;

use Cms\Core\Services\PluginManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;

class PluginController extends Controller
{
    protected PluginManager $pluginManager;

    public function __construct(PluginManager $pluginManager)
    {
        $this->pluginManager = $pluginManager;
    }

    public function index(): View
    {
        $plugins = $this->pluginManager->getInstalledPlugins();
        return view('cms-core::plugins.index', compact('plugins'));
    }

    public function upload(Request $request): RedirectResponse
    {
        $request->validate([
            'plugin_zip' => 'required|file|mimes:zip',
        ]);

        if ($this->pluginManager->installFromZip($request->file('plugin_zip'))) {
            return redirect()->route('cms.plugins.index')->with('success', 'Plugin installed successfully.');
        }

        return redirect()->route('cms.plugins.index')->with('error', 'Failed to install plugin from zip.');
    }

    public function activate(string $slug): RedirectResponse
    {
        if ($this->pluginManager->activate($slug)) {
            return redirect()->route('cms.plugins.index')->with('success', 'Plugin activated successfully.');
        }

        return redirect()->route('cms.plugins.index')->with('error', 'Failed to activate plugin.');
    }

    public function deactivate(string $slug): RedirectResponse
    {
        if ($this->pluginManager->deactivate($slug)) {
            return redirect()->route('cms.plugins.index')->with('success', 'Plugin deactivated successfully.');
        }

        return redirect()->route('cms.plugins.index')->with('error', 'Failed to deactivate plugin.');
    }

    public function destroy(string $slug): RedirectResponse
    {
        if ($this->pluginManager->delete($slug)) {
            return redirect()->route('cms.plugins.index')->with('success', 'Plugin deleted successfully.');
        }

        return redirect()->route('cms.plugins.index')->with('error', 'Failed to delete plugin.');
    }
}
