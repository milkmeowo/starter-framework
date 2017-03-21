<?php
/**
 * RouteCommand.php.
 *
 * Description
 *
 * @author Milkmeowo <milkmeowo@gmail.com>
 */

namespace Milkmeowo\Framework\Repository\Generators\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Milkmeowo\Framework\Repository\Generators\RoutesGenerator;

class RouteCommand extends Command
{
    /**
     * The name of command.
     *
     * @var string
     */
    protected $name = 'starter:route';

    /**
     * The description of command.
     *
     * @var string
     */
    protected $description = 'Create a new route.';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Route';

    /**
     * Execute the command.
     *
     * @return void
     */
    public function fire()
    {
        (new RoutesGenerator([
            'name' => $this->argument('name'),
            'force' => $this->option('force'),
        ]))->run();
        $this->info($this->type.' created successfully.');
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
                'The name of route for which the routes is being generated.',
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
