<?php

namespace Tests;

use \Lloople\ConsoleBuilder\Providers\ConsoleBuilderCommandsProvider;

use Orchestra\Testbench\TestCase;

class DeleteCommandTest extends TestCase
{

    protected function getPackageProviders($app)
    {
        return [
            ConsoleBuilderCommandsProvider::class,
        ];
    }

    /** @test */
    public function can_run()
    {

        $this->artisan('builder:delete', ['model' => 'App\User']);

        $this->assertTrue(true);
    }
}