<?php

namespace Milkmeowo\Framework\Database\Providers;

use Illuminate\Support\ServiceProvider;
use Milkmeowo\Framework\Database\Connection\MysqlConnection;

class DatabaseServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('db.connection.mysql', MysqlConnection::class);
    }
}
