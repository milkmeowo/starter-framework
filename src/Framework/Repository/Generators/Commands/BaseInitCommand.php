<?php
/**
 * BaseInitCommand.php
 *
 * Description
 *
 * @author Milkmeowo <milkmeowo@gmail.com>
 */

namespace Milkmeowo\Framework\Repository\Generators\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Milkmeowo\Framework\Repository\Generators\ControllerGenerator;
use Milkmeowo\Framework\Repository\Generators\ModelGenerator;
use Milkmeowo\Framework\Repository\Generators\PresenterGenerator;
use Milkmeowo\Framework\Repository\Generators\RepositoryEloquentGenerator;
use Milkmeowo\Framework\Repository\Generators\RepositoryInterfaceGenerator;
use Milkmeowo\Framework\Repository\Generators\TransformerGenerator;

class BaseInitCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'starter:base';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create starter base files';

    /**
     * The Filesystem instance.
     *
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * Filename of stub-file.
     *
     * @var string
     */
    protected $stubs = [
        'api.controller.laravel'  => 'Api/LaravelBaseController',
        'api.controller.lumen'    => 'Api/LumenBaseController',
        'http.controller.laravel' => 'Http/LaravelBaseController',
        'http.controller.lumen'   => 'Http/LumenBaseController',
        'model'                   => 'Models/BaseModel',
        'presenter'               => 'Presenters/BasePresenter',
        'repositories.eloquent'   => 'Repositories/Eloquent/BasePresenter',
        'repositories.interfaces' => 'Repositories/Interfaces/BaseRepositoryInterface',
        'transformer' => 'Transformers/BaseTransformer',
    ];

    /**
     * BaseInitCommand constructor.
     *
     * @param Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;

        parent::__construct();
    }

    public function handle()
    {
        $this->generateApiController();

        $this->generateHttpController();

        $this->generateModel();

        $this->generateRepositoriesEloquent();

        $this->generateRepositoriesInterfaces();

        $this->generatePresenters();

        $this->generateTransformer();
    }

    public function generateApiController()
    {
        $stub = is_lumen() ? $this->stubs['api.controller.lumen'] : $this->stubs['api.controller.laravel'];
        $stub = $this->getStub($stub);

        $controllerGenerator = new ControllerGenerator([ 'name' => 'Base' ]);
        $namespace = $controllerGenerator->getNamespace();

        $path = $controllerGenerator->getBasePath().'/'.$controllerGenerator->getConfigGeneratorClassPath($controllerGenerator->getPathConfigNode(),
                true).'/BaseController.php';

        $this->generateFile($path, $stub, $namespace);
    }

    public function generateHttpController()
    {
        $stub = is_lumen() ? $this->stubs['http.controller.lumen'] : $this->stubs['http.controller.laravel'];
        $stub = $this->getStub($stub);
        $path = app()->path().'/Http/Controllers/BaseController.php';
        $namespace = 'namespace '.app()->getNamespace().'Http\Controllers;';

        $this->generateFile($path, $stub, $namespace);
    }

    public function generateModel()
    {
        $stub = $this->stubs['model'];
        $generator = new ModelGenerator([ 'name' => 'BaseModel' ]);
        $namespace = $generator->getNamespace();
        $path = $generator->getPath();

        $this->generateFile($path, $stub, $namespace);
    }

    public function generatePresenters()
    {
        $stub = $this->stubs['presenter'];
        $generator = new PresenterGenerator([ 'name' => 'Base' ]);
        $namespace = $generator->getNamespace();
        $path = $generator->getPath();

        $this->generateFile($path, $stub, $namespace);
    }

    public function generateRepositoriesEloquent()
    {
        $stub = $this->stubs['repositories.eloquent'];
        $generator = new RepositoryEloquentGenerator([ 'name' => 'Base' ]);
        $namespace = $generator->getNamespace();
        $path = $generator->getBasePath().'/'.$generator->getConfigGeneratorClassPath($generator->getPathConfigNode(),
                true).'/BaseRepository.php';

        $this->generateFile($path, $stub, $namespace);
    }

    public function generateRepositoriesInterfaces()
    {
        $stub = $this->stubs['repositories.interfaces'];
        $generator = new RepositoryInterfaceGenerator([ 'name' => 'Base' ]);
        $namespace = $generator->getNamespace();
        $path = $generator->getBasePath().'/'.$generator->getConfigGeneratorClassPath($generator->getPathConfigNode(),
                true).'/BaseRepositoryInterface.php';

        $this->generateFile($path, $stub, $namespace);
    }

    public function generateTransformer()
    {
        $stub = $this->stubs['transformer'];
        $generator = new TransformerGenerator([ 'name' => 'Base' ]);
        $namespace = $generator->getNamespace();
        $path = $generator->getPath();

        $this->generateFile($path, $stub, $namespace);
    }

    public function generateFile($path, $stub, $namespace)
    {
        if ( ! $this->filesystem->exists($path) || $this->option('force') || $this->confirm($path.'already exists! Do you wish to continue?')) {
            $content = str_replace('$NAMESPACE$', $namespace, $stub);

            if (! $this->filesystem->isDirectory($dir = dirname($path))) {
                $this->filesystem->makeDirectory($dir, 0777, true, true);
            }

            $this->filesystem->put($path, $content);
            $this->line('---------------');
            $this->info($path ." generated");
            $this->line('---------------');
        }
    }

    public function getPath($stub)
    {
        $defaultPath = dirname(__DIR__);
        $path = config('repository.generator.stubsOverridePath', $defaultPath);

        // rollback
        if ( ! file_exists($path.'/Stubs/base/'.$stub.'.stub')) {
            $path = $defaultPath;
        }

        return $path.'/Stubs/base/'.$stub.'.stub';
    }

    public function getStub($stub)
    {
        return $this->filesystem->get($this->getPath($stub));
    }

    /**
     * Get command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['force', 'f', InputOption::VALUE_NONE, 'Overwrite existing base files'],
        ];
    }

}