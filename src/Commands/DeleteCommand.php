<?php

namespace Lloople\ConsoleBuilder\Commands;

use Illuminate\Console\Command;

class DeleteCommand extends Command
{

    protected $signature = 'builder:delete {model} {--where=} {--like=} {--equals=} {--delete-all}';

    protected $description = 'Delete database records matching the query';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info("Hello World! 👋");
    }
}