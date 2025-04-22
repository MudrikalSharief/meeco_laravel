<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RefreshConfig extends Command
{
    protected $signature = 'config:refresh';
    protected $description = 'Refresh application configuration and clear all caches';

    public function handle()
    {
        $this->call('config:clear');
        $this->call('cache:clear');
        
        // Recreate the bootstrap/cache/config.php file
        $this->call('config:cache');
        
        // Clear and rebuild route cache
        $this->call('route:clear');
        
        // Clear compiled view files
        $this->call('view:clear');
        
        // Clear application cache
        $this->call('optimize:clear');
        
        $this->info('Configuration has been completely refreshed!');
        
        // Show the API key status (masked for security)
        $apiKey = env('OPENAI_API_KEY');
        if (empty($apiKey)) {
            $this->error('OPENAI_API_KEY is not set in your .env file');
        } else {
            $maskedKey = substr($apiKey, 0, 3) . '...' . substr($apiKey, -3);
            $this->info('OPENAI_API_KEY is properly set: ' . $maskedKey);
        }
    }
}
