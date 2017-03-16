<?php

namespace Milkmeowo\Framework\Database\Connection;

use Illuminate\Database\MySqlConnection as BaseMySqlConnection;
use Milkmeowo\Framework\Database\Schema\Blueprint;
use Milkmeowo\Framework\Database\Schema\Grammars\MysqlGrammar;

class MysqlConnection extends BaseMySqlConnection
{

    /**
     * Get a schema builder instance for the connection.
     *
     * @return \Illuminate\Database\Schema\MySqlBuilder
     */
    public function getSchemaBuilder()
    {
        $builder = parent::getSchemaBuilder();

        // add a blueprint resolver closure that returns the custom blueprint
        $builder->blueprintResolver(function ($table, $callback) {
            return new Blueprint($table, $callback);
        });

        return $builder;
    }

    /**
     * Get the custom schema grammar instance.
     *
     * @return \Milkmeowo\Framework\Database\Schema\Grammars\MysqlGrammar
     */
    protected function getDefaultSchemaGrammar()
    {
        return $this->withTablePrefix(new MysqlGrammar);
    }
}
