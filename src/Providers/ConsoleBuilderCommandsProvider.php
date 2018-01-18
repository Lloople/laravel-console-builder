<?php

namespace Lloople\ConsoleBuilder\Providers;

use Illuminate\Support\ServiceProvider;
use Lloople\ConsoleBuilder\Commands\DeleteCommand;

class ConsoleBuilderCommandsProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                DeleteCommand::class
            ]);
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
