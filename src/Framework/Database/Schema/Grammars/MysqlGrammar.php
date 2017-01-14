<?php

namespace Milkmeowo\Framework\Database\Schema\Grammars;

use Illuminate\Support\Fluent;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Grammars\MySqlGrammar as BaseMySqlGrammar;

class MysqlGrammar extends BaseMySqlGrammar
{
    /**
     * Compile a fulltext key command.
     *
     * @param Blueprint $blueprint
     * @param Fluent    $command
     *
     * @return string
     */
    public function compileFulltext(Blueprint $blueprint, Fluent $command)
    {
        return $this->compileKey($blueprint, $command, 'fulltext');
    }

    /**
     * Compile a drop fulltext key command.
     *
     * @param Blueprint $blueprint
     * @param Fluent    $command
     *
     * @return string
     */
    public function compileDropFulltext(Blueprint $blueprint, Fluent $command)
    {
        $table = $this->wrapTable($blueprint);

        $index = $this->wrap($command->index);

        return "alter table {$table} drop index {$index}";
    }
}
