<?php

namespace Milkmeowo\Framework\Repository\Generators\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Milkmeowo\Framework\Repository\Generators\ValidatorGenerator;
use Milkmeowo\Framework\Repository\Generators\Exceptions\FileAlreadyExistsException;

class ValidatorCommand extends Command
{
    /**
     * The name of command.
     *
     * @var string
     */
    protected $name = 'starter:validator';

    /**
     * The description of command.
     *
     * @var string
     */
    protected $description = 'Create a new validator.';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Validator';

    /**
     * Execute the command.
     *
     * @return void
     */
    public function fire()
    {
        try {
            (new ValidatorGenerator([
                'name' => $this->argument('name'),
                'rules' => $this->option('rules'),
                'force' => $this->option('force'),
            ]))->run();
            $this->info('Validator created successfully.');
        } catch (FileAlreadyExistsException $e) {
            $this->error($this->type.' already exists!');

            return false;
        }
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
                'The name of model for which the validator is being generated.',
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
                'rules',
                null,
                InputOption::VALUE_OPTIONAL,
                'The rules of validation attributes.',
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
