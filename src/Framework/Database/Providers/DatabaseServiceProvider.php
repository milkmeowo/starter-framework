<?php

namespace Milkmeowo\Framework\Database\Providers;

use Illuminate\Support\ServiceProvider;
use Milkmeowo\Framework\Database\Connection\MysqlConnection;

class DatabaseServiceProvider extends ServiceProvider
{
    public function register()
    {
        Connection::resolverFor('mysql', function ($connection, $database, $prefix, $config) {
            return new MysqlConnection($connection, $database, $prefix, $config);
        });
    }
}
