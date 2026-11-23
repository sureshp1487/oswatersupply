<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\DatabaseExportService;

class ExportDatabaseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:export {--keep=14 : Number of days of historical backups to retain}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically export the complete database to an SQL dump file and clean old backups';

    /**
     * Execute the console command.
     */
    public function handle(DatabaseExportService $exportService): int
    {
        $this->info('Starting automated database export...');

        try {
            $result = $exportService->export();

            $this->newLine();
            $this->components->info('Database export created successfully!');
            $this->table(
                ['Metric', 'Value'],
                [
                    ['File Name', $result['filename']],
                    ['File Size', $result['size']],
                    ['Tables Exported', $result['tables_count']],
                    ['Rows Exported', $result['rows_count']],
                    ['Duration', $result['duration'] . ' seconds'],
                    ['File Location', $result['path']],
                ]
            );

            // Clean older backups
            $keepDays = (int) $this->option('keep');
            if ($keepDays > 0) {
                $cleanedCount = $exportService->cleanOldBackups($keepDays);
                if ($cleanedCount > 0) {
                    $this->comment("Pruned {$cleanedCount} backup file(s) older than {$keepDays} days.");
                }
            }

            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $this->error('Database export failed: ' . $e->getMessage());
            \Illuminate\Support\Facades\Log::error('Automated DB Export Failed: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            return Command::FAILURE;
        }
    }
}
