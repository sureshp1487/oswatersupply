<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class SystemToolsController extends Controller
{
    protected string $logPath;

    public function __construct()
    {
        $this->logPath = storage_path('logs');
    }

    /**
     * Display the System Tools UI: Caches & Logs
     */
    public function index(Request $request)
    {
        $logFiles = $this->getLogFileList();
        
        $selectedFile = $request->query('file', 'laravel.log');
        // Validate filename to prevent path traversal
        if (!preg_match('/^[a-zA-Z0-9_\-\.]+\.log$/', $selectedFile) || !File::exists($this->logPath . '/' . $selectedFile)) {
            $selectedFile = !empty($logFiles) ? $logFiles[0]['name'] : null;
        }

        $logContent = '';
        $logFileSize = 0;
        $totalLines = 0;

        if ($selectedFile && File::exists($this->logPath . '/' . $selectedFile)) {
            $filePath = $this->logPath . '/' . $selectedFile;
            $logFileSize = File::size($filePath);
            
            // Read up to last 1500 lines or 512KB for performance
            $logContent = $this->readTail($filePath, 1200);
            $totalLines = substr_count($logContent, "\n");
        }

        return view('admin.system.index', compact('logFiles', 'selectedFile', 'logContent', 'logFileSize', 'totalLines'));
    }

    /**
     * Clear application caches (views, cache, config, routes, or all)
     */
    public function clearCache(Request $request)
    {
        $type = $request->input('type', 'all');

        $messages = [];

        try {
            switch ($type) {
                case 'view':
                    Artisan::call('view:clear');
                    $messages[] = 'Blade view cache cleared successfully.';
                    break;
                case 'cache':
                    Artisan::call('cache:clear');
                    $messages[] = 'Application data cache cleared successfully.';
                    break;
                case 'config':
                    Artisan::call('config:clear');
                    $messages[] = 'Configuration cache cleared successfully.';
                    break;
                case 'route':
                    Artisan::call('route:clear');
                    $messages[] = 'Route cache cleared successfully.';
                    break;
                case 'optimize':
                case 'all':
                default:
                    Artisan::call('optimize:clear');
                    $output = Artisan::output();
                    $messages[] = 'All system caches (views, cache, config, routes) cleared successfully.';
                    break;
            }

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => implode(' ', $messages),
                    'output' => Artisan::output() ?? '',
                ]);
            }

            return back()->with('success', implode(' ', $messages));
        } catch (\Throwable $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to clear cache: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Error clearing cache: ' . $e->getMessage());
        }
    }

    /**
     * Read / Fetch log content via AJAX or direct view
     */
    public function readLog(Request $request)
    {
        $file = $request->input('file');
        if (!preg_match('/^[a-zA-Z0-9_\-\.]+\.log$/', $file)) {
            return response()->json(['error' => 'Invalid log filename.'], 400);
        }

        $filePath = $this->logPath . '/' . $file;
        if (!File::exists($filePath)) {
            return response()->json(['error' => 'Log file not found.'], 404);
        }

        $content = $this->readTail($filePath, 1200);

        return response()->json([
            'filename' => $file,
            'size' => $this->formatBytes(File::size($filePath)),
            'content' => $content,
            'updated_at' => date('Y-m-d H:i:s', File::lastModified($filePath)),
        ]);
    }

    /**
     * Rename a log file
     */
    public function renameLog(Request $request)
    {
        $request->validate([
            'current_name' => ['required', 'string', 'regex:/^[a-zA-Z0-9_\-\.]+\.log$/'],
            'new_name' => ['required', 'string', 'regex:/^[a-zA-Z0-9_\-]+$/'],
        ], [
            'current_name.regex' => 'Invalid current log file name.',
            'new_name.required' => 'New log file name is required.',
            'new_name.regex' => 'New name may only contain alphanumeric characters, hyphens, and underscores.',
        ]);

        $currentName = $request->input('current_name');
        $newName = trim($request->input('new_name'));

        // Always append .log if not present
        if (!Str::endsWith($newName, '.log')) {
            $newName .= '.log';
        }

        $currentPath = $this->logPath . '/' . $currentName;
        $newPath = $this->logPath . '/' . $newName;

        if (!File::exists($currentPath)) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => "Log file '{$currentName}' does not exist."], 404);
            }
            return back()->with('error', "Log file '{$currentName}' does not exist.");
        }

        if (File::exists($newPath) && $currentPath !== $newPath) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => "A log file named '{$newName}' already exists."], 422);
            }
            return back()->with('error', "A log file named '{$newName}' already exists.");
        }

        try {
            File::move($currentPath, $newPath);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "Log file '{$currentName}' was successfully renamed to '{$newName}'.",
                    'old_name' => $currentName,
                    'new_name' => $newName,
                    'log_files' => $this->getLogFileList(),
                ]);
            }

            return redirect()->route('admin.system.index', ['file' => $newName])
                ->with('success', "Log file '{$currentName}' was successfully renamed to '{$newName}'.");
        } catch (\Throwable $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to rename log file: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Failed to rename log file: ' . $e->getMessage());
        }
    }

    /**
     * Helper to list log files
     */
    protected function getLogFileList(): array
    {
        if (!File::isDirectory($this->logPath)) {
            return [];
        }

        $files = File::files($this->logPath);
        $logList = [];

        foreach ($files as $file) {
            if ($file->getExtension() === 'log') {
                $logList[] = [
                    'name' => $file->getFilename(),
                    'path' => $file->getPathname(),
                    'size' => $this->formatBytes($file->getSize()),
                    'bytes' => $file->getSize(),
                    'modified' => date('Y-m-d H:i:s', $file->getMTime()),
                ];
            }
        }

        // Sort: laravel.log first, then by last modified desc
        usort($logList, function ($a, $b) {
            if ($a['name'] === 'laravel.log') return -1;
            if ($b['name'] === 'laravel.log') return 1;
            return $b['modified'] <=> $a['modified'];
        });

        return $logList;
    }

    /**
     * Read the last N lines of a file quickly
     */
    protected function readTail(string $filepath, int $lines = 1000): string
    {
        if (!File::exists($filepath) || File::size($filepath) === 0) {
            return '(File is empty)';
        }

        $f = fopen($filepath, 'rb');
        if (!$f) return '';

        $buffer = '';
        $lineCount = 0;
        $chunkSize = 4096;
        $fileSize = filesize($filepath);
        $pos = $fileSize;

        while ($pos > 0 && $lineCount < $lines) {
            $seek = max(0, $pos - $chunkSize);
            $bytesToRead = $pos - $seek;

            fseek($f, $seek);
            $chunk = fread($f, $bytesToRead);
            $buffer = $chunk . $buffer;

            $lineCount = substr_count($buffer, "\n");
            $pos = $seek;
        }

        fclose($f);

        if ($lineCount > $lines) {
            // Trim leading extra lines
            $allLines = explode("\n", $buffer);
            $buffer = implode("\n", array_slice($allLines, -$lines));
        }

        return $buffer;
    }

    protected function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
