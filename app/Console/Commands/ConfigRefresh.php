<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ConfigRefresh extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'config:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear config cache and reload configurations';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->call('config:clear');
        $this->info('Configuration cache cleared!');
        
        $this->call('cache:clear');
        $this->info('Application cache cleared!');
        
        $this->info('Reloading environment variables...');
        
        $this->info('Current OPENAI_API_KEY status: ' . (env('OPENAI_API_KEY') ? 'Available' : 'Not available'));
        
        return 0;
    }
}
