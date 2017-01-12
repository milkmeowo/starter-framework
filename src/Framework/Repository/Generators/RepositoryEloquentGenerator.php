<?php

namespace Milkmeowo\Framework\Repository\Generators;

use Milkmeowo\Framework\Repository\Generators\Migrations\SchemaParser;

class RepositoryEloquentGenerator extends Generator
{
    /**
     * Get stub name.
     *
     * @var string
     */
    protected $stub = 'repository/eloquent';

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
     * Get generator path config node.
     *
     * @return string
     */
    public function getPathConfigNode()
    {
        return 'repositories';
    }

    /**
     * Get destination path for generated file.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->getBasePath().'/'.parent::getConfigGeneratorClassPath($this->getPathConfigNode(),
            true).'/'.$this->getName().'RepositoryEloquent.php';
    }

    /**
     * Get base path of destination file.
     *
     * @return string
     */
    public function getBasePath()
    {
        return config('repository.generator.basePath', app()->path());
    }

    /**
     * Get array replacements.
     *
     * @return array
     */
    public function getReplacements()
    {
        $repository = parent::getRootNamespace().parent::getConfigGeneratorClassPath('interfaces').'\\'.$this->name.'Repository;';
        $repository = str_replace([
            '\\',
            '/',
        ], '\\', $repository);

        return array_merge(parent::getReplacements(), [
            'fillable'      => $this->getFillable(),
            'use_validator' => $this->getValidatorUse(),
            'validator'     => $this->getValidatorMethod(),
            'use_presenter' => $this->getPresenterUse(),
            'presenter'     => $this->getPresenterMethod(),
            'criteria'      => $this->getCriteria(),
            'repository'    => $repository,
            'model'         => isset($this->options['model']) ? $this->options['model'] : '',
        ]);
    }

    /**
     * Get the fillable attributes.
     *
     * @return string
     */
    public function getFillable()
    {
        if (! $this->fillable) {
            return '[]';
        }
        $results = '['.PHP_EOL;

        foreach ($this->getSchemaParser()->toArray() as $column => $value) {
            $results .= "\t\t'{$column}',".PHP_EOL;
        }

        return $results."\t".']';
    }

    /**
     * Get schema parser.
     *
     * @return SchemaParser
     */
    public function getSchemaParser()
    {
        return new SchemaParser($this->fillable);
    }

    /**
     * @return string
     */
    public function getValidatorUse()
    {
        $validator = $this->getValidator();

        return "use {$validator};";
    }

    /**
     * @return string
     */
    public function getValidator()
    {
        $validatorGenerator = new ValidatorGenerator([
            'name'  => $this->name,
            'rules' => $this->rules,
            'force' => $this->force,
        ]);

        $validator = $validatorGenerator->getRootNamespace().'\\'.$validatorGenerator->getName();

        return str_replace([
            '\\',
            '/',
        ], '\\', $validator).'Validator';
    }

    /**
     * @return string
     */
    public function getValidatorMethod()
    {
        if ($this->validator != 'yes') {
            return '';
        }

        $class = $this->getClass();

        return '/**'.PHP_EOL.'     * Specify Validator class name.'.PHP_EOL.'     *'.PHP_EOL.'     * @return mixed'.PHP_EOL.'     */'.PHP_EOL.'    public function validator()'.PHP_EOL.'    {'.PHP_EOL.'        return '.$class.'Validator::class;'.PHP_EOL.'    }';
    }

    /**
     * @return string
     */
    public function getPresenterUse()
    {
        $presenter = $this->getPresenter();

        return "use {$presenter};";
    }

    /**
     * @return string
     */
    public function getPresenter()
    {
        $presenterGenerator = new PresenterGenerator([
            'name'  => $this->name,
            'rules' => $this->rules,
            'force' => $this->force,
        ]);
        $presenter = $presenterGenerator->getRootNamespace().'\\'.$presenterGenerator->getName();

        return str_replace([
            '\\',
            '/',
        ], '\\', $presenter).'Presenter';
    }

    /**
     * @return string
     */
    public function getPresenterMethod()
    {
        if ($this->presenter != 'yes') {
            return '';
        }
        $class = $this->getClass();

        return '/**'.PHP_EOL.'     * Specify Presenter class name.'.PHP_EOL.'     *'.PHP_EOL.'     * @return mixed'.PHP_EOL.'     */'.PHP_EOL.'    public function presenter()'.PHP_EOL.'    {'.PHP_EOL.'        return '.$class.'Presenter::class;'.PHP_EOL.'    }';
    }

    /**
     * @return string
     */
    public function getCriteria()
    {
        $criteriaGenerator = new CriteriaGenerator([
            'name'  => $this->name,
            'force' => $this->force,
        ]);

        $criteria = $criteriaGenerator->getRootNamespace().'\\'.$criteriaGenerator->getName();

        return str_replace([
            '\\',
            '/',
        ], '\\', $criteria).'Criteria';
    }
}
