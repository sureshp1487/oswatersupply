<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DatabaseExportService
{
    protected string $backupDir;

    public function __construct()
    {
        $this->backupDir = storage_path('app/backups');
        if (!File::exists($this->backupDir)) {
            File::makeDirectory($this->backupDir, 0755, true);
        }
    }

    /**
     * Export the entire database to a SQL dump file.
     *
     * @return array
     */
    public function export(): array
    {
        $startTime = microtime(true);
        $connection = DB::connection();
        $driver = $connection->getDriverName();
        $databaseName = $connection->getDatabaseName();

        $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
        $filename = "db_backup_{$databaseName}_{$timestamp}.sql";
        $filePath = $this->backupDir . DIRECTORY_SEPARATOR . $filename;

        $handle = fopen($filePath, 'w');
        if (!$handle) {
            throw new \RuntimeException("Unable to open backup file for writing: {$filePath}");
        }

        // Write SQL Header
        $nowFormatted = Carbon::now()->toDateTimeString();
        $appName = config('app.name', 'AquaFlow');
        $header = "-- ========================================================\n"
                . "-- Database Export: {$databaseName}\n"
                . "-- Application: {$appName}\n"
                . "-- Generated at: {$nowFormatted} (UTC/Local)\n"
                . "-- Driver: {$driver}\n"
                . "-- ========================================================\n\n"
                . "SET FOREIGN_KEY_CHECKS=0;\n"
                . "SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';\n"
                . "SET NAMES utf8mb4;\n\n";
        fwrite($handle, $header);

        $tablesExported = 0;
        $totalRows = 0;

        try {
            if ($driver === 'mysql') {
                $tables = DB::select("SHOW FULL TABLES WHERE Table_type = 'BASE TABLE'");
                $keyName = "Tables_in_{$databaseName}";

                foreach ($tables as $tableObj) {
                    $tableVars = get_object_vars($tableObj);
                    $tableName = reset($tableVars);

                    // Skip temporary or non-essential cache locks if needed, or include everything
                    $tablesExported++;

                    // Write Table Structure
                    fwrite($handle, "--\n-- Table structure for table `{$tableName}`\n--\n");
                    fwrite($handle, "DROP TABLE IF EXISTS `{$tableName}`;\n");

                    $createTableResult = DB::select("SHOW CREATE TABLE `{$tableName}`");
                    $createTableArray = (array) $createTableResult[0];
                    $createTableSql = $createTableArray['Create Table'] ?? '';
                    fwrite($handle, $createTableSql . ";\n\n");

                    // Write Table Data
                    $rowCount = DB::table($tableName)->count();
                    if ($rowCount > 0) {
                        fwrite($handle, "--\n-- Dumping data for table `{$tableName}` ({$rowCount} rows)\n--\n");

                        $pdo = DB::getPdo();

                        DB::table($tableName)->orderBy(DB::raw('1'))->chunk(250, function ($rows) use ($handle, $tableName, $pdo, &$totalRows) {
                            $firstRow = true;
                            $insertPrefix = "INSERT INTO `{$tableName}` VALUES ";
                            $valuesBatch = [];

                            foreach ($rows as $row) {
                                $totalRows++;
                                $rowValues = [];
                                foreach ((array) $row as $val) {
                                    if (is_null($val)) {
                                        $rowValues[] = 'NULL';
                                    } elseif (is_numeric($val) && !is_string($val)) {
                                        $rowValues[] = $val;
                                    } else {
                                        $rowValues[] = $pdo->quote((string) $val);
                                    }
                                }
                                $valuesBatch[] = "(" . implode(', ', $rowValues) . ")";
                            }

                            if (!empty($valuesBatch)) {
                                fwrite($handle, $insertPrefix . implode(",\n", $valuesBatch) . ";\n");
                            }
                        });

                        fwrite($handle, "\n");
                    }
                }
            } elseif ($driver === 'sqlite') {
                $tables = DB::select("SELECT name, sql FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");
                $pdo = DB::getPdo();

                foreach ($tables as $table) {
                    $tableName = $table->name;
                    $tablesExported++;

                    fwrite($handle, "--\n-- Table structure for `{$tableName}`\n--\n");
                    fwrite($handle, "DROP TABLE IF EXISTS `{$tableName}`;\n");
                    fwrite($handle, $table->sql . ";\n\n");

                    $rowCount = DB::table($tableName)->count();
                    if ($rowCount > 0) {
                        DB::table($tableName)->chunk(250, function ($rows) use ($handle, $tableName, $pdo, &$totalRows) {
                            $valuesBatch = [];
                            foreach ($rows as $row) {
                                $totalRows++;
                                $rowValues = [];
                                foreach ((array) $row as $val) {
                                    if (is_null($val)) {
                                        $rowValues[] = 'NULL';
                                    } elseif (is_numeric($val) && !is_string($val)) {
                                        $rowValues[] = $val;
                                    } else {
                                        $rowValues[] = $pdo->quote((string) $val);
                                    }
                                }
                                $valuesBatch[] = "(" . implode(', ', $rowValues) . ")";
                            }
                            if (!empty($valuesBatch)) {
                                fwrite($handle, "INSERT INTO `{$tableName}` VALUES " . implode(",\n", $valuesBatch) . ";\n");
                            }
                        });
                        fwrite($handle, "\n");
                    }
                }
            } else {
                throw new \RuntimeException("Unsupported database driver for automatic SQL dump: {$driver}");
            }

            // Write SQL Footer
            fwrite($handle, "SET FOREIGN_KEY_CHECKS=1;\n");
            fwrite($handle, "-- ====== End of Database Export ======\n");
        } finally {
            fclose($handle);
        }

        $duration = round(microtime(true) - $startTime, 3);
        $fileSize = File::exists($filePath) ? File::size($filePath) : 0;

        Log::info("Database export completed successfully: {$filename} ({$this->formatBytes($fileSize)}, {$tablesExported} tables, {$totalRows} rows in {$duration}s)");

        return [
            'success' => true,
            'filename' => $filename,
            'path' => $filePath,
            'size' => $this->formatBytes($fileSize),
            'size_bytes' => $fileSize,
            'tables_count' => $tablesExported,
            'rows_count' => $totalRows,
            'duration' => $duration,
            'created_at' => Carbon::now()->toDateTimeString(),
        ];
    }

    /**
     * Get list of all available backups.
     */
    public function getBackupsList(): array
    {
        if (!File::exists($this->backupDir)) {
            return [];
        }

        $files = File::files($this->backupDir);
        $backups = [];

        foreach ($files as $file) {
            if ($file->getExtension() === 'sql' || $file->getExtension() === 'gz') {
                $backups[] = [
                    'filename' => $file->getFilename(),
                    'path' => $file->getRealPath(),
                    'size' => $this->formatBytes($file->getSize()),
                    'size_bytes' => $file->getSize(),
                    'created_at' => date('Y-m-d H:i:s', $file->getMTime()),
                    'timestamp' => $file->getMTime(),
                ];
            }
        }

        // Sort latest backups first
        usort($backups, fn($a, $b) => $b['timestamp'] <=> $a['timestamp']);

        return $backups;
    }

    /**
     * Delete backups older than specified days.
     */
    public function cleanOldBackups(int $keepDays = 14): int
    {
        $cutoffTime = time() - ($keepDays * 86400);
        $deleted = 0;

        foreach ($this->getBackupsList() as $backup) {
            if ($backup['timestamp'] < $cutoffTime) {
                if (File::exists($backup['path'])) {
                    File::delete($backup['path']);
                    $deleted++;
                }
            }
        }

        return $deleted;
    }

    /**
     * Format bytes to human readable form.
     */
    protected function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
