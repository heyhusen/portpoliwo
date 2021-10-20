<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AppRelease extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:release';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run commands after deploying app.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Clear caches.');
        $this->line(' ');
        $this->line(' ');
        $this->call('optimize:clear');
        $this->info('Update database schema.');
        $this->line(' ');
        $this->line(' ');
        $this->call('migrate', [
            '--force' => 'default'
        ]);
        $this->info('Cache app.');
        $this->line(' ');
        $this->line(' ');
        $this->call('optimize');
    }
}
