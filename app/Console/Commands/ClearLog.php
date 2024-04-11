<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ClearLog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clear-log';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear the laravel.log file';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $logFile = storage_path('logs/laravel.log');
        if (file_exists($logFile)) {
            file_put_contents($logFile, '');
            $this->info('laravel.log file cleared.');
        } else {
            $this->error('laravel.log file not found.');
        }

        return 0;
    }
}
