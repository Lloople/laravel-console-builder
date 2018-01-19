<?php

namespace Tests;

use App\User;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\TestCase;

class DeleteCommandTest extends TestCase
{

    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->loadLaravelMigrations('testing');

        $this->app->make(Factory::class)->load('tests/factories');

    }

    /** @test */
    public function can_delete_single_record_by_id()
    {
        $user = factory(User::class)->create();

        $command = \Mockery::mock('\Lloople\ConsoleBuilder\Commands\DeleteCommand[confirm]');

        $command->shouldReceive('confirm')
            ->once()
            ->with('Are you sure you want to continue?')
            ->andReturn('yes');

        $this->app['Illuminate\Contracts\Console\Kernel']->registerCommand($command);

        $this->artisan('builder:delete', [
            'model' => 'App\User',
            '--id' => $user->id,
            '--no-interaction' => true
        ]);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}