<?php

namespace Lloople\ConsoleBuilder\Commands;

use Illuminate\Console\Command;

abstract class BuilderCommand extends Command
{

    public $query;

    abstract public function executeStatement();

    /**
     * Validate that the correct options are setted
     * @return bool
     */
    public function validateOptions()
    {
        return $this->hasOption('find')
            || ($this->hasOption('where')
                && ($this->hasOption('like') || $this->hasOption('equals')
                )
            );
    }

    /**
     * Check if the given option was provided.
     *
     * @param string $option
     * @return bool
     */
    public function hasOption($option)
    {
        return $this->option($option, null) !== null;
    }

    /**
     * Find for an specific record but return it like a Collection.
     *
     * @return \Illuminate\Support\Collection
     */
    public function find()
    {
        return collect([$this->query->find($this->option('find'))]);
    }

    /**
     * Search for the records using sql statements.
     *
     * @return mixed
     */
    public function search()
    {
        if ($this->hasOption('like')) {
            $this->query->where($this->option('where'), 'like', "%{$this->option('like')}%");
        }

        if ($this->hasOption('equals')) {
            $this->query->where($this->option('where'), $this->option('equals'));
        }

        return $this->query->get();
    }

    /**
     * Validate the options and perform the query.
     */
    public function handle()
    {
        if (! $this->validateOptions()) {
            $this->error('Invalid input, you need to provide an --find or --where and --like or --equals options');

            return;
        }

        $this->query = ($this->argument('model'))::query();

        $this->executeStatement();
    }
}