<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AppInstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install or reinstall aplication';

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
     * @return mixed
     */
    public function handle()
    {
        $this->info('Installing all required components.');
        $this->line(' ');
        $this->line(' ');
        if ( ! file_exists(base_path('.env'))) {
            copy(base_path('.env.example'), base_path('.env'));
        }
        $this->line('Create symbolic link.');
        if ( ! is_dir(public_path('storage'))) {
            $this->call('storage:link');
        }
        $this->line(' ');
        $this->line('Generate application key.');
        $this->call('key:generate');
        $this->line(' ');
        $this->line('Database migration and seeding.');
        $this->call('migrate:fresh', [
            '--seed' => 'default'
        ]);
        $this->line(' ');
        $this->line('Installing Passport.');
        $this->call('passport:install', [
            '--force' => 'default'
        ]);
        $this->line(' ');
        $this->line('Generate Passport encryption keys.');
        $this->call('passport:keys', [
            '--force' => 'default'
        ]);
        $this->line(' ');
        $this->line(' ');
        $this->info('Done.');
    }
}
