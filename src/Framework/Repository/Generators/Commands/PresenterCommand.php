<?php

namespace Milkmeowo\Framework\Repository\Generators\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Milkmeowo\Framework\Repository\Generators\PresenterGenerator;
use Milkmeowo\Framework\Repository\Generators\TransformerGenerator;
use Milkmeowo\Framework\Repository\Generators\Exceptions\FileAlreadyExistsException;

class PresenterCommand extends Command
{
    /**
     * The name of command.
     *
     * @var string
     */
    protected $name = 'starter:presenter';

    /**
     * The description of command.
     *
     * @var string
     */
    protected $description = 'Create a new presenter.';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Presenter';

    /**
     * Execute the command.
     *
     * @return void
     */
    public function fire()
    {
        try {
            (new PresenterGenerator([
                'name'  => $this->argument('name'),
                'force' => $this->option('force'),
            ]))->run();
            $this->info('Presenter created successfully.');

            $filesystem = new Filesystem();

            if (! $filesystem->exists(app()->path().'/Transformers/'.$this->argument('name').'Transformer.php')) {
                (new TransformerGenerator([
                        'name'  => $this->argument('name'),
                        'force' => $this->option('force'),
                    ]))->run();
                $this->info('Transformer created successfully.');
            }
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
                'The name of model for which the presenter is being generated.',
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
                'force',
                'f',
                InputOption::VALUE_NONE,
                'Force the creation if file already exists.',
                null,
            ],
        ];
    }
}
