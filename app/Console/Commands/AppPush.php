<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AppPush extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:push';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Push commit to remote master and production branch.';

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
        $this->info('Push to master, then merge it with production and push it again to production.');
        exec('./deploy.sh', $output);
        $this->comment(implode(PHP_EOL, $output));
    }
}
