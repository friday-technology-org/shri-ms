<?php

namespace Cms\Core\Http\Controllers;

use Cms\Core\Models\CmsActivityLog;
use Cms\Core\Models\Comment;
use Cms\Core\Models\PostRevision;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class DiagnosticsController extends Controller
{
    /**
     * Display Site Health checklist and database maintenance status.
     */
    public function siteHealth(): View
    {
        $checks = [
            'php_version' => [
                'label' => 'PHP Version',
                'value' => PHP_VERSION,
                'status' => version_compare(PHP_VERSION, '8.1.0', '>=') ? 'good' : 'warning',
                'message' => 'PHP 8.1+ is recommended for security and speed.',
            ],
            'pdo_mysql' => [
                'label' => 'PDO MySQL Driver',
                'value' => extension_loaded('pdo_mysql') ? 'Enabled' : 'Disabled',
                'status' => extension_loaded('pdo_mysql') ? 'good' : 'bad',
                'message' => 'LaraCMS requires pdo_mysql extension to talk to the MySQL database.',
            ],
            'upload_directory' => [
                'label' => 'Uploads Directory Writable',
                'value' => File::isWritable(public_path('uploads')) ? 'Writable' : 'Not Writable',
                'status' => File::isWritable(public_path('uploads')) ? 'good' : 'bad',
                'message' => 'The public/uploads folder must be writable for media uploads.',
            ],
            'cache_directory' => [
                'label' => 'Storage Directory Writable',
                'value' => File::isWritable(storage_path()) ? 'Writable' : 'Not Writable',
                'status' => File::isWritable(storage_path()) ? 'good' : 'bad',
                'message' => 'The storage/ folder must be writable for caching, logs, and sessions.',
            ],
            'database_connection' => [
                'label' => 'Database Connection',
                'value' => 'Connected',
                'status' => 'good',
                'message' => 'Database connection established successfully.',
            ],
        ];

        // Test database connection
        try {
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            $checks['database_connection'] = [
                'label' => 'Database Connection',
                'value' => 'Failed',
                'status' => 'bad',
                'message' => 'Database connection error: ' . $e->getMessage(),
            ];
        }

        // Get DB details
        $spamCount = Comment::where('status', 'spam')->count();
        $revisionCount = PostRevision::count();
        
        // Count expired transients in options table
        $expiredTransientsCount = DB::table('cms_options')
            ->where('option_name', 'like', '_transient_timeout_%')
            ->where('option_value', '>', '0')
            ->where('option_value', '<', time())
            ->count();

        return view('cms-core::tools.site-health', compact('checks', 'spamCount', 'revisionCount', 'expiredTransientsCount'));
    }

    /**
     * Run database maintenance cleanups.
     */
    public function dbMaintenance(Request $request): RedirectResponse
    {
        $action = $request->input('action');

        switch ($action) {
            case 'clean_transients':
                // Get keys of expired timeouts
                $timeouts = DB::table('cms_options')
                    ->where('option_name', 'like', '_transient_timeout_%')
                    ->where('option_value', '>', '0')
                    ->where('option_value', '<', time())
                    ->get();

                foreach ($timeouts as $t) {
                    $baseKey = str_replace('_transient_timeout_', '', $t->option_name);
                    delete_transient($baseKey);
                }
                $msg = 'Expired database transients cleaned successfully.';
                break;

            case 'clean_revisions':
                PostRevision::truncate();
                $msg = 'All post revision history cleaned successfully.';
                break;

            case 'clean_spam':
                Comment::where('status', 'spam')->delete();
                $msg = 'All spam comments deleted successfully.';
                break;

            case 'optimize_tables':
                // Run OPTIMIZE TABLE on core tables
                $tables = ['posts', 'comments', 'cms_options', 'post_meta'];
                foreach ($tables as $table) {
                    try {
                        DB::statement("OPTIMIZE TABLE `{$table}`");
                    } catch (\Throwable $e) {
                        // Some DB drivers might not support optimize, ignore
                    }
                }
                $msg = 'Database tables optimized successfully.';
                break;

            default:
                return redirect()->back()->with('error', 'Invalid maintenance action.');
        }

        // Log this maintenance action
        CmsActivityLog::create([
            'user_id' => auth()->id(),
            'event' => 'db_maintenance',
            'description' => "Triggered DB maintenance action: {$action}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->back()->with('success', $msg);
    }

    /**
     * List user Activity log audit trail.
     */
    public function activityLogs(): View
    {
        $logs = CmsActivityLog::with('user')->latest()->paginate(20);
        return view('cms-core::tools.activity-logs', compact('logs'));
    }
}
