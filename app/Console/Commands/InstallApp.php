<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class InstallApp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'site:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install Allikasi';

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
        $bar = $this->output->createProgressBar(2);

        $this->line('Migrate and Seeding Database please wait...');

        Artisan::call('migrate:fresh', ['--force' => true]);

        $bar->advance();
        
        $this->newLine();
        Artisan::call('db:seed', ['--force' => true]);
        
        $bar->finish();
        
        $this->newLine();
        $this->info('Berhasil menginstall aplikasi');
    }
}
