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
        $this->publishes([
            base_path('vendor/milkmeowo/starter-framework/config/repository.php') => base_path('config/repository.php'),
        ]);

        $this->mergeConfigFrom(base_path('vendor/milkmeowo/starter-framework/config/repository.php'), 'repository');

        $this->loadTranslationsFrom(base_path('vendor/prettus/l5-repository/src/resources/lang'), 'repository');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands('Milkmeowo\Framework\Repository\Generators\Commands\BaseInitCommand');
        $this->commands('Milkmeowo\Framework\Repository\Generators\Commands\RepositoryCommand');
        $this->commands('Milkmeowo\Framework\Repository\Generators\Commands\TransformerCommand');
        $this->commands('Milkmeowo\Framework\Repository\Generators\Commands\PresenterCommand');
        $this->commands('Milkmeowo\Framework\Repository\Generators\Commands\EntityCommand');
        $this->commands('Milkmeowo\Framework\Repository\Generators\Commands\ValidatorCommand');
        $this->commands('Milkmeowo\Framework\Repository\Generators\Commands\ControllerCommand');
        $this->commands('Milkmeowo\Framework\Repository\Generators\Commands\BindingsCommand');
        $this->commands('Milkmeowo\Framework\Repository\Generators\Commands\CriteriaCommand');
        $this->app->register('Prettus\Repository\Providers\EventServiceProvider');
    }
}
