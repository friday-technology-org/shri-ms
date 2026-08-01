<?php

namespace Cms\Core\Http\Controllers;

use Cms\Core\Services\BackupService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BackupController extends Controller
{
    protected BackupService $backupService;

    public function __construct(BackupService $backupService)
    {
        $this->backupService = $backupService;
    }

    /**
     * Display a listing of created backups.
     */
    public function index(): View
    {
        $backups = $this->backupService->getBackups();
        return view('cms-core::tools.backups', compact('backups'));
    }

    /**
     * Trigger a new manual backup archive.
     */
    public function store(): RedirectResponse
    {
        $filename = $this->backupService->createBackup();

        if ($filename) {
            return redirect()->route('cms.tools.backups.index')->with('success', "Backup archive '{$filename}' created successfully.");
        }

        return redirect()->route('cms.tools.backups.index')->with('error', 'Failed to create backup archive.');
    }

    /**
     * Download a specific backup archive file.
     */
    public function download(string $filename): BinaryFileResponse|RedirectResponse
    {
        $path = storage_path('app/backups/' . basename($filename));

        if (file_exists($path)) {
            return response()->download($path);
        }

        return redirect()->route('cms.tools.backups.index')->with('error', 'Backup archive file not found.');
    }

    /**
     * Delete a backup file.
     */
    public function destroy(string $filename): RedirectResponse
    {
        if ($this->backupService->delete($filename)) {
            return redirect()->route('cms.tools.backups.index')->with('success', 'Backup deleted successfully.');
        }

        return redirect()->route('cms.tools.backups.index')->with('error', 'Failed to delete backup.');
    }
}
