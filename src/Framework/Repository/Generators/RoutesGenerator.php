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

    protected $webStub = 'routes/web';

    protected $apiStub = 'routes/api';

    protected $webPathConfigNode = 'routes.web';

    protected $apiPathConfigNode = 'routes.api';

    public function run()
    {
        $filesystem = new Filesystem();

        $webRoute = $filesystem->get($this->getWebPath());
        $filesystem->put($this->getWebPath(), str_replace($this->routesPlaceholder, $this->getStub($this->webStub), $webRoute));

        $apiRoute = $filesystem->get($this->getApiPath());
        $filesystem->put($this->getApiPath(), str_replace($this->routesPlaceholder, $this->getStub($this->apiStub), $apiRoute));
    }

    /**
     * Get stub template for generated file.
     *
     * @return string
     */
    public function getStub($stub = null)
    {
        $stub = isset($stub) ? $stub : $this->stub;
        $path = config('repository.generator.stubsOverridePath', __DIR__);

        if (! file_exists($path.'/Stubs/'.$stub.'.stub')) {
            $path = __DIR__;
        }

        return (new Stub($path.'/Stubs/'.$stub.'.stub', $this->getReplacements()))->render();
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

    public function getApiPath()
    {
        return $this->getBasePath().'/'.parent::getConfigGeneratorClassPath($this->apiPathConfigNode, true).'.php';
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

    public function getControllerName()
    {
        $controllerGenerator = new ControllerGenerator([
            'name' => $this->name,
        ]);

        $controllerName = $controllerGenerator->getControllerName().'Controller';

        return $controllerName;
    }

    public function getReplacements()
    {
        return array_merge(parent::getReplacements(), [
            'lowerclass' => strtolower($this->getClass()).'s',
            'controller' => $this->getControllerName(),
            'placeholder' => $this->routesPlaceholder,
        ]);
    }

    public function getPathConfigNode()
    {
        // TODO: Implement getPathConfigNode() method.
    }
}
