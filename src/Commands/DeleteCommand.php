<?php

namespace Lloople\ConsoleBuilder\Commands;

class DeleteCommand extends BuilderCommand
{

    protected $signature = 'builder:delete 
    {model : The model to query of} 
    {--find= : Search a single record by primary key} 
    {--where= : Filter the records with a where clause} 
    {--like= : Filter by `like`} 
    {--equals= : Filter by exact match} 
    {--delete-all : Delete all records found}';

    protected $description = 'Delete database records matching the query';

    public function executeStatement()
    {
        $records = $this->hasOption('find')
            ? $this->find()
            : $this->search();

        if (! $records->count()) {
            $this->error("No records were found.");

            return;
        }

        $this->info("You're about to delete these records:");

        $this->table(array_keys($records->first()->toArray()), array_values($records->toArray()));

        if ($this->confirm("Are you sure you want to continue?")) {
            $records->each->delete();
            $this->info("Models deleted successfully.");
        }
    }

}