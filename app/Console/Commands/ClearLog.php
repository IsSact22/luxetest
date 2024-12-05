<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class ClearLog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'log:clear {file=laravel.log : The log file to clear}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear a specified log file in the storage/logs directory';

    /**
     * Execute the console command.
     */
    public function handle(Filesystem $filesystem): int
    {
        // Obtener el nombre del archivo de log
        $fileName = $this->argument('file');
        $logFile = storage_path('logs/'.$fileName);

        if ($filesystem->exists($logFile)) {
            $filesystem->put($logFile, '');
            $this->info(sprintf("Log file '%s' has been cleared.", $fileName));
        } else {
            $this->error(sprintf("Log file '%s' not found.", $fileName));
        }

        return 0;
    }
}
