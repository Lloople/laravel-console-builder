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

        $this->registerConfirmCommand('DeleteCommand');

        $this->artisan('builder:delete', [
            'model' => 'App\User',
            '--find' => $user->id,
            '--no-interaction' => true
        ]);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    /** @test */
    public function can_delete_multiple_records_with_like()
    {
        factory(User::class, 2)->create(['name' => 'John '.str_random(4)]);
        factory(User::class, 2)->create(['name' => 'Doe '.str_random(4)]);

        $this->registerConfirmCommand('DeleteCommand');

        $this->artisan('builder:delete', [
            'model' => 'App\User',
            '--where' => 'name',
            '--like' => 'John',
            '--no-interaction' => true
        ]);

        $this->assertCount(0, User::where('name', 'like', '%John%')->get());
        $this->assertCount(2, User::all());
    }

    /** @test */
    public function can_delete_multiple_records_with_equals()
    {
        factory(User::class, 2)->create(['name' => 'John']);
        factory(User::class, 2)->create(['name' => 'Doe']);

        $this->registerConfirmCommand('DeleteCommand');

        $this->artisan('builder:delete', [
            'model' => 'App\User',
            '--where' => 'name',
            '--like' => 'John',
            '--no-interaction' => true
        ]);

        $this->assertCount(0, User::where('name', 'John')->get());
        $this->assertCount(2, User::all());
    }

    /**
     * Register the mock for the command to interact with confirmation.
     *
     * @param string $commandName
     */
    private function registerConfirmCommand(string $commandName)
    {
        $command = \Mockery::mock("\Lloople\ConsoleBuilder\Commands\\{$commandName}[confirm]");

        $command->shouldReceive('confirm')
            ->once()
            ->with('Are you sure you want to continue?')
            ->andReturn('yes');

        $this->app['Illuminate\Contracts\Console\Kernel']->registerCommand($command);
    }
}