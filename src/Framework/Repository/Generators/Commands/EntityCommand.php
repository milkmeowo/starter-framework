<?php

namespace Milkmeowo\Framework\Repository\Generators\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class EntityCommand extends Command
{
    /**
     * The name of command.
     *
     * @var string
     */
    protected $name = 'starter:entity';

    /**
     * The description of command.
     *
     * @var string
     */
    protected $description = 'Create a new entity.';

    /**
     * @var Collection
     */
    protected $generators = null;

    /**
     * Execute the command.
     *
     * @return void
     */
    public function fire()
    {
        $this->call('starter:presenter', [
            'name'    => $this->argument('name'),
            '--force' => $this->option('force'),
        ]);

        $this->call('starter:validator', [
            'name'    => $this->argument('name'),
            '--rules' => $this->option('rules'),
            '--force' => $this->option('force'),
        ]);

        // Generate a controller resource
        $this->call('starter:resource', [
            'name'    => $this->argument('name'),
            '--force' => $this->option('force'),
        ]);

        $this->call('starter:repository', [
            'name'        => $this->argument('name'),
            '--fillable'  => $this->option('fillable'),
            '--rules'     => $this->option('rules'),
            '--validator' => 'yes',
            '--presenter' => 'yes',
            '--force'     => $this->option('force'),
        ]);

        $this->call('starter:bindings', [
            'name'    => $this->argument('name'),
            '--force' => $this->option('force'),
        ]);
    }

    /**
     * The array of command arguments.
     *
     * @return array
     */
    public function getArguments()
    {
        return [
            [
                'name',
                InputArgument::REQUIRED,
                'The name of class being generated.',
                null,
            ],
        ];
    }

    /**
     * The array of command options.
     *
     * @return array
     */
    public function getOptions()
    {
        return [
            [
                'fillable',
                null,
                InputOption::VALUE_OPTIONAL,
                'The fillable attributes.',
                null,
            ],
            [
                'rules',
                null,
                InputOption::VALUE_OPTIONAL,
                'The rules of validation attributes.',
                null,
            ],
            [
                'validator',
                null,
                InputOption::VALUE_OPTIONAL,
                'Adds validator reference to the repository.',
                null,
            ],
            [
                'presenter',
                null,
                InputOption::VALUE_OPTIONAL,
                'Adds presenter reference to the repository.',
                null,
            ],
            [
                'force',
                'f',
                InputOption::VALUE_NONE,
                'Force the creation if file already exists.',
                null,
            ],
        ];
    }
}
