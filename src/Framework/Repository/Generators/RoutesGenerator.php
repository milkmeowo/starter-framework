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

    /**
     * Get generator path config node.
     *
     * @return string
     */
    public function getPathConfigNode()
    {
        return 'routes';
    }

    public function run()
    {
        $filesystem = new Filesystem();

        // Add entity repository binding to the repository service provider
        $route = $filesystem->get($this->getPath());
        $filesystem->put($this->getPath(), str_replace($this->routesPlaceholder, $this->getStub(), $route));
    }

    /**
     * Get destination path for generated file.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->getBasePath().'/'.parent::getConfigGeneratorClassPath($this->getPathConfigNode(), true).'.php';
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
}
