<?php
/**
 * BaseServiceProvider.php
 *
 * Description
 *
 * @author Milkmeowo <milkmeowo@gmail.com>
 */

namespace Milkmeowo\Framework\Foundation\Providers;

use Illuminate\Support\ServiceProvider;

class BaseServiceProvider extends ServiceProvider
{

    public function boot()
    {

    }

    public function register()
    {
        // l5-repository
        $this->app->register(RepositoryServiceProvider::class);
    }
}