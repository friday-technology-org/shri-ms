<?php

namespace Cms\Core\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;

class TranslationController extends Controller
{
    protected string $langPath;

    public function __construct()
    {
        $this->langPath = base_path('cms-content/languages');
    }

    public function index()
    {
        if (!File::isDirectory($this->langPath)) {
            File::makeDirectory($this->langPath, 0755, true);
        }

        $files = File::files($this->langPath);
        $locales = [];

        foreach ($files as $file) {
            if ($file->getExtension() === 'json') {
                $locales[] = $file->getFilenameWithoutExtension();
            }
        }

        return view('cms-core::settings.phase9.translations', compact('locales'));
    }

    public function edit($locale)
    {
        $filePath = $this->langPath . '/' . basename($locale) . '.json';
        $translations = [];

        if (File::isFile($filePath)) {
            $translations = json_decode(File::get($filePath), true) ?: [];
        }

        return view('cms-core::settings.phase9.translations-edit', compact('locale', 'translations'));
    }

    public function update(Request $request, $locale)
    {
        if (!File::isDirectory($this->langPath)) {
            File::makeDirectory($this->langPath, 0755, true);
        }

        $filePath = $this->langPath . '/' . basename($locale) . '.json';
        $keys = $request->input('keys', []);
        $values = $request->input('values', []);

        $translations = [];
        for ($i = 0; $i < count($keys); $i++) {
            if (!empty($keys[$i])) {
                $translations[$keys[$i]] = $values[$i] ?? '';
            }
        }

        File::put($filePath, json_encode($translations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return redirect()->route('cms.translations.edit', $locale)->with('success', 'Translations updated successfully.');
    }

    public function create(Request $request)
    {
        $request->validate([
            'locale' => 'required|string|alpha|max:10',
        ]);

        $locale = strtolower($request->locale);
        $filePath = $this->langPath . '/' . $locale . '.json';

        if (!File::isFile($filePath)) {
            if (!File::isDirectory($this->langPath)) {
                File::makeDirectory($this->langPath, 0755, true);
            }
            File::put($filePath, json_encode([], JSON_PRETTY_PRINT));
        }

        return redirect()->route('cms.translations.edit', $locale)->with('success', "Locale '{$locale}' created successfully.");
    }
}
