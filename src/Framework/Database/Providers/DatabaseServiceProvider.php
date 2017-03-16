<?php

namespace Milkmeowo\Framework\Database\Providers;

use LogicException;
use Illuminate\Database\Connection;
use Illuminate\Support\ServiceProvider;

class DatabaseServiceProvider extends ServiceProvider
{
    /**
     * Resolve the connection via bindings.
     *
     * @var string
     */
    const CONNECTION_RESOLVER_BINDINGS = 'bindings';

    /**
     * Resolve the connection via the connection resolver method.
     *
     * @var string
     */
    const CONNECTION_RESOLVER_METHOD = 'connection-method';

    /**
     * The connection classes to use for the drivers.
     *
     * @var array
     */
    protected $classes = [
        'mysql' => 'Milkmeowo\Framework\Database\Connection\MysqlConnection',
    ];

    public function register()
    {
        $this->registerConnections();
    }

    /**
     * Register the database connection extensions.
     *
     * @return void
     */
    public function registerConnections()
    {
        $driver = $this->connectionResolverDriver();

        $method = 'registerVia'.studly_case($driver);

        if (method_exists($this, $method)) {
            return $this->{$method}();
        }
        throw new LogicException(sprintf('Connection registration method [%s] does not exist.', $method));
    }

    /**
     * Determine the driver for resolving the connection.
     *
     * @return string
     */
    public function connectionResolverDriver()
    {
        if (method_exists(Connection::class, 'resolverFor')) {
            return self::CONNECTION_RESOLVER_METHOD;
        }

        return self::CONNECTION_RESOLVER_BINDINGS;
    }

    /**
     * Register the database connection extensions through app bindings.
     *
     * @return void
     */
    public function registerViaBindings()
    {
        foreach ($this->classes as $driver => $class) {
            $this->app->bind('db.connection.'.$driver, $class);
        }
    }

    /**
     * Register the database connection extensions through the `Connection` method.
     *
     * @return void
     */
    public function registerViaConnectionMethod()
    {
        foreach ($this->classes as $driver => $class) {
            Connection::resolverFor($driver, function ($connection, $database, $prefix, $config) use ($class) {
                return new $class($connection, $database, $prefix, $config);
            });
        }
    }

    /**
     * Check if the connection resolver method is of the given type.
     *
     * @param  string $driver
     *
     * @return bool
     */
    public function isConnectionResolver($driver)
    {
        return $this->connectionResolverDriver() == $driver;
    }
}
