<?php

namespace Milkmeowo\Framework\Repository\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands('Milkmeowo\Framework\Repository\Generators\Commands\RepositoryCommand');
        $this->commands('Milkmeowo\Framework\Repository\Generators\Commands\TransformerCommand');
        $this->commands('Milkmeowo\Framework\Repository\Generators\Commands\PresenterCommand');
        $this->commands('Milkmeowo\Framework\Repository\Generators\Commands\EntityCommand');
        $this->commands('Milkmeowo\Framework\Repository\Generators\Commands\ValidatorCommand');
        $this->commands('Milkmeowo\Framework\Repository\Generators\Commands\ControllerCommand');
        $this->commands('Milkmeowo\Framework\Repository\Generators\Commands\BindingsCommand');
        $this->commands('Milkmeowo\Framework\Repository\Generators\Commands\CriteriaCommand');
        $this->app->register('Prettus\Repository\Providers\EventServiceProvider');

        $this->app->bind('Symfony\Component\Translation\TranslatorInterface', function ($app) {
            return $app['translator'];
        });
    }
}
