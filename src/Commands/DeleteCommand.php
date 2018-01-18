<?php

namespace Lloople\ConsoleBuilder\Commands;

use Illuminate\Console\Command;

class DeleteCommand extends Command
{
    protected $signature = 'builder:delete {model} {--id=} {--where=} {--like=} {--equals=} {--delete-all} ';

    protected $description = 'Delete database records matching the query';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        if ($this->hasOption('id')) {
            $this->deleteById();
        }
    }

    public function deleteById()
    {
        $model = ($this->argument('model'))::findOrFail($this->option('id'));

        $this->info("You're about to delete the model:");
        $this->table(array_keys($model->toArray()), [array_values($model->toArray())]);

        if ($this->confirm("Are you sure you want to continue?")) {
            $model->delete();
            $this->info("Model deleted successfully.");
        }

    }
}