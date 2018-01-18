<?php

namespace Tests;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use \Lloople\ConsoleBuilder\Providers\ConsoleBuilderCommandsProvider;

use Orchestra\Testbench\TestCase;

class DeleteCommandTest extends TestCase
{

    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->loadLaravelMigrations('testing');

    }

    protected function getPackageProviders($app)
    {
        return [
            ConsoleBuilderCommandsProvider::class,
        ];
    }

    /** @test */
    public function can_delete_single_record_by_id()
    {

        \DB::table('users')->insert([
            'id' => 1,
            'name' => 'Dummy',
            'email' => 'dummy@test.com',
            'password' => bcrypt('dummy')
        ]);
        $this->artisan('builder:delete', ['model' => 'App\User', '--id' => 1]);

        $this->assertDatabaseMissing('users', ['id' => 1]);
    }
}