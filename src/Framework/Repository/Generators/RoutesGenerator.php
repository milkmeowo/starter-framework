<?php

namespace Milkmeowo\Framework\Repository\Generators;

use Illuminate\Filesystem\Filesystem;

class RoutesGenerator extends Generator
{

    public $routesPlaceholder = '//:end-routes:';

    /**
     * Get stub name.
     *
     * @var string
     */
    protected $stub = 'routes/web';

    protected $webLumenStub = 'routes/web.lumen';

    protected $webLaravelStub = 'routes/web.laravel';

    protected $apiStub = 'routes/api';

    protected $webPathConfigNode = 'routes.web';

    protected $apiPathConfigNode = 'routes.api';

    public function run()
    {
        $filesystem = new Filesystem();

        $webStub = is_lumen() ? $this->webLumenStub : $this->webLaravelStub;

        $webRoute = $filesystem->get($this->getWebPath());
        $filesystem->put($this->getWebPath(),
            str_replace($this->routesPlaceholder, $this->getStub($webStub), $webRoute));

        $apiRoute = $filesystem->get($this->getApiPath());
        $filesystem->put($this->getApiPath(),
            str_replace($this->routesPlaceholder, $this->getStub($this->apiStub), $apiRoute));
    }

    /**
     * Get destination path for generated file.
     *
     * @return string
     */
    public function getWebPath()
    {
        return $this->getBasePath().'/'.parent::getConfigGeneratorClassPath($this->webPathConfigNode, true).'.php';
    }

    /**
     * Get base path of destination file.
     *
     * @return string
     */
    public function getBasePath()
    {
        return app()->basePath().'/routes';
    }

    /**
     * Get stub template for generated file.
     *
     * @return string
     */
    public function getStub($stub = null)
    {
        $stub = isset( $stub ) ? $stub : $this->stub;
        $path = config('repository.generator.stubsOverridePath', __DIR__);

        if ( ! file_exists($path.'/Stubs/'.$stub.'.stub')) {
            $path = __DIR__;
        }

        return ( new Stub($path.'/Stubs/'.$stub.'.stub', $this->getReplacements()) )->render();
    }

    public function getReplacements()
    {
        return array_merge(parent::getReplacements(), [
            'lowerclass'  => str_plural(strtolower($this->getClass())),
            'controller'  => $this->getControllerName(),
            'placeholder' => $this->routesPlaceholder,
        ]);
    }

    public function getControllerName()
    {
        $controllerGenerator = new ControllerGenerator([
            'name' => $this->name,
        ]);

        $controllerName = $controllerGenerator->getControllerName().'Controller';

        return $controllerName;
    }

    public function getApiPath()
    {
        return $this->getBasePath().'/'.parent::getConfigGeneratorClassPath($this->apiPathConfigNode, true).'.php';
    }

    public function getPathConfigNode()
    {
        // TODO: Implement getPathConfigNode() method.
    }
}
