<?php
/**
 * BaseServiceProvider.php.
 *
 * Description
 *
 * @author Milkmeowo <milkmeowo@gmail.com>
 */
namespace Milkmeowo\Framework\Base\Providers;

use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\PassportServiceProvider as LaravelPassportServiceProvider;
use Milkmeowo\Framework\Database\Providers\DatabaseServiceProvider;
use Milkmeowo\Framework\Repository\Providers\RepositoryServiceProvider;

class BaseServiceProvider extends ServiceProvider
{
    public function boot()
    {
    }

    public function register()
    {
        // command
        $this->app->register(CommandServiderProvider::class);

        // l5-repository
        $this->app->register(RepositoryServiceProvider::class);

        // laravel passport
        $this->app->register(LaravelPassportServiceProvider::class);

        // database
        $this->app->register(DatabaseServiceProvider::class);

        // dev provider
        $this->registerDevPackages();
    }

    public function registerDevPackages()
    {
        if ($this->app->environment() !== 'production') {
            $this->app->register(IdeHelperServiceProvider::class);
        }
    }
}
