<?php

namespace App\Providers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;

class StorageCleanupServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->clearStorageOnMigrateFresh();
    }

    protected function clearStorageOnMigrateFresh(): void
    {
        if ($this->app->runningInConsole()) {
            $command = request()->server('argv')[1] ?? null;

            if ($command === 'migrate:fresh') {
                Storage::disk('s3')->deleteDirectory('');
                // Storage::disk('public')->deleteDirectory(''); // Uncomment for local storage
                $this->app['log']->info('Storage cleanup completed before migrate:fresh.');
            }
        }
    }
}
