<?php

namespace Milkmeowo\Framework\Repository\Generators;

class SeederGenerator extends Generator
{
    /**
     * Get stub name.
     *
     * @var string
     */
    protected $stub = 'seed';

    /**
     * Get generator path config node.
     *
     * @return string
     */
    public function getPathConfigNode()
    {
    }

    /**
     * Get destination path for generated file.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->getBasePath().'/seeds/'.$this->getName().'Seeder.php';
    }

    /**
     * Get base path of destination file.
     *
     * @return string
     */
    public function getBasePath()
    {
        return app()->databasePath();
    }
}
