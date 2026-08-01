<?php

namespace Cms\Core\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use ZipArchive;

class BackupService
{
    protected string $backupDir;

    public function __construct()
    {
        $this->backupDir = storage_path('app/backups');
    }

    /**
     * Get list of created backup ZIPs.
     */
    public function getBackups(): array
    {
        if (!File::isDirectory($this->backupDir)) {
            return [];
        }

        $files = File::files($this->backupDir);
        $backups = [];

        foreach ($files as $file) {
            if ($file->getExtension() === 'zip') {
                $backups[] = [
                    'filename' => $file->getFilename(),
                    'size' => $file->getSize(),
                    'created_at' => $file->getMTime(),
                    'path' => $file->getRealPath(),
                ];
            }
        }

        // Sort by created time descending
        usort($backups, function ($a, $b) {
            return $b['created_at'] <=> $a['created_at'];
        });

        return $backups;
    }

    /**
     * Create a backup archive containing SQL export and uploads.
     */
    public function createBackup(): string|bool
    {
        if (!File::isDirectory($this->backupDir)) {
            File::makeDirectory($this->backupDir, 0755, true);
        }

        $timestamp = date('Y-m-d-H-i-s');
        $zipPath = $this->backupDir . '/backup-' . $timestamp . '.zip';
        $sqlPath = $this->backupDir . '/db-backup-' . $timestamp . '.sql';

        // 1. Export database to SQL file
        if (!$this->exportDatabase($sqlPath)) {
            return false;
        }

        // 2. Archive SQL file and public/uploads into ZIP
        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            // Add SQL dump
            $zip->addFile($sqlPath, 'database.sql');

            // Add Uploads folder recursively
            $uploadsPath = public_path('uploads');
            if (File::isDirectory($uploadsPath)) {
                $files = new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator($uploadsPath),
                    \RecursiveIteratorIterator::LEAVES_ONLY
                );

                foreach ($files as $name => $file) {
                    if (!$file->isDir()) {
                        $filePath = $file->getRealPath();
                        $relativePath = 'uploads/' . substr($filePath, strlen($uploadsPath) + 1);
                        $zip->addFile($filePath, $relativePath);
                    }
                }
            }

            $zip->close();
            
            // Delete temp SQL file
            File::delete($sqlPath);

            return basename($zipPath);
        }

        return false;
    }

    /**
     * Pure-PHP database dump generator.
     */
    protected function exportDatabase(string $outputPath): bool
    {
        try {
            $tables = DB::select('SHOW TABLES');
            $dbName = config('database.connections.mysql.database');
            $tableKey = 'Tables_in_' . $dbName;

            $sql = "-- LaraCMS Database Backup\n";
            $sql .= "-- Generated: " . date('Y-m-d H:i:s') . "\n\n";
            $sql .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

            foreach ($tables as $tableObj) {
                $table = $tableObj->$tableKey;

                // 1. Create table structure
                $createObj = DB::select("SHOW CREATE TABLE `{$table}`")[0];
                $createSql = $createObj->{'Create Table'};
                $sql .= "DROP TABLE IF EXISTS `{$table}`;\n";
                $sql .= $createSql . ";\n\n";

                // 2. Dump data
                $rows = DB::table($table)->get();
                foreach ($rows as $row) {
                    $rowArray = (array) $row;
                    $keys = array_map(function ($k) { return "`{$k}`"; }, array_keys($rowArray));
                    $values = array_map(function ($v) {
                        if ($v === null) return 'NULL';
                        return DB::getPdo()->quote($v);
                    }, array_values($rowArray));

                    $sql .= "INSERT INTO `{$table}` (" . implode(', ', $keys) . ") VALUES (" . implode(', ', $values) . ");\n";
                }
                $sql .= "\n";
            }

            $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";

            File::put($outputPath, $sql);
            return true;
        } catch (\Throwable $e) {
            logger()->error('Database backup failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete a backup file.
     */
    public function delete(string $filename): bool
    {
        $path = $this->backupDir . '/' . basename($filename);
        if (File::isFile($path)) {
            File::delete($path);
            return true;
        }
        return false;
    }
}
