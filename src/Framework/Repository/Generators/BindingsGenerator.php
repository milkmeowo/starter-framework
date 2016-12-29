<?php

namespace Milkmeowo\Framework\Repository\Generators;

use Illuminate\Filesystem\Filesystem;

class BindingsGenerator extends Generator
{
    /**
     * The placeholder for repository bindings.
     *
     * @var string
     */
    public $bindPlaceholder = '//:end-bindings:';
    /**
     * Get stub name.
     *
     * @var string
     */
    protected $stub = 'bindings/bindings';

    /**
     * Run the generator.
     *
     * @return int
     */
    public function run()
    {
        $filesystem = new Filesystem();

        // Add entity repository binding to the repository service provider
        $provider = $filesystem->get($this->getPath());
        $repositoryInterface = '\\'.$this->getRepository().'::class';
        $repositoryEloquent = '\\'.$this->getEloquentRepository().'::class';

        return $filesystem->put($this->getPath(), str_replace($this->bindPlaceholder, "\$this->app->bind({$repositoryInterface}, $repositoryEloquent);".PHP_EOL.'        '.$this->bindPlaceholder, $provider));
    }

    /**
     * Get destination path for generated file.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->getBasePath().'/Providers/'.parent::getConfigGeneratorClassPath($this->getPathConfigNode(), true).'.php';
    }

    /**
     * Get base path of destination file.
     *
     * @return string
     */
    public function getBasePath()
    {
        return config('repository.generator.basePath', app_path());
    }

    /**
     * Get generator path config node.
     *
     * @return string
     */
    public function getPathConfigNode()
    {
        return 'provider';
    }

    /**
     * Gets repository full class name.
     *
     * @return string
     */
    public function getRepository()
    {
        $repositoryGenerator = new RepositoryInterfaceGenerator([
            'name' => $this->name,
        ]);

        $repository = $repositoryGenerator->getRootNamespace().'\\'.$repositoryGenerator->getName();

        return str_replace([
            '\\',
            '/',
        ], '\\', $repository).'Repository';
    }

    /**
     * Gets eloquent repository full class name.
     *
     * @return string
     */
    public function getEloquentRepository()
    {
        $repositoryGenerator = new RepositoryEloquentGenerator([
            'name' => $this->name,
        ]);

        $repository = $repositoryGenerator->getRootNamespace().'\\'.$repositoryGenerator->getName();

        return str_replace([
            '\\',
            '/',
        ], '\\', $repository).'RepositoryEloquent';
    }

    /**
     * Get root namespace.
     *
     * @return string
     */
    public function getRootNamespace()
    {
        return parent::getRootNamespace().parent::getConfigGeneratorClassPath($this->getPathConfigNode());
    }

    /**
     * Get array replacements.
     *
     * @return array
     */
    public function getReplacements()
    {
        return array_merge(parent::getReplacements(), [
            'repository' => $this->getRepository(),
            'eloquent' => $this->getEloquentRepository(),
            'placeholder' => $this->bindPlaceholder,
        ]);
    }
}
