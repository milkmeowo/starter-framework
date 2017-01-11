<?php

namespace Milkmeowo\Framework\Database\Schema;

use Illuminate\Database\Schema\Blueprint as BaseBlueprint;

class Blueprint extends BaseBlueprint
{
    public function recordStamps()
    {
        $this->timestamps();
        $this->ipStamps();
        $this->userStamps();
    }

    public function ipStamps()
    {
        $this->ipAddress('created_ip')->nullable();
        $this->ipAddress('updated_ip')->nullable();
    }

    public function userStamps()
    {
        $this->unsignedInteger('created_by')->nullable();
        $this->unsignedInteger('updated_by')->nullable();
    }

    public function softDeletesRecordStamps()
    {
        $this->softDeletes();
        $this->softDeletesStamps();
    }

    public function softDeletesStamps()
    {
        $this->ipAddress('deleted_ip')->nullable();
        $this->unsignedInteger('deleted_by')->nullable();
    }

    public function dropRecordStamps()
    {
        $this->dropTimestamps();
        $this->dropIpStamps();
        $this->dropUserStamps();
    }

    public function dropIpStamps()
    {
        $this->dropColumn('created_ip', 'updated_ip');
    }

    public function dropUserStamps()
    {
        $this->dropColumn('created_by', 'updated_by');
    }

    public function dropSoftDeletesRecordStamps()
    {
        $this->dropSoftDeletes();
        $this->dropSoftDeletesStamps();
    }

    public function dropSoftDeletesStamps()
    {
        $this->dropColumn('deleted_ip', 'deleted_by');
    }

    /**
     * Specify a fulltext index for the table.
     *
     * @param  string|array  $columns
     * @param  string  $name
     * @return \Illuminate\Support\Fluent
     */
    public function fulltext($columns, $name = null)
    {
        return $this->indexCommand('fulltext', $columns, $name);
    }

    /**
     * Indicate that the given fulltext key should be dropped.
     *
     * @param  string|array  $index
     * @return \Illuminate\Support\Fluent
     */
    public function dropFulltext($index)
    {
        return $this->dropIndexCommand('dropFulltext', 'fulltext', $index);
    }

    /**
     * Add the index commands fluently specified on columns.
     *
     * @return void
     */
    protected function addFluentIndexes()
    {
        $FluentIndexes = ['primary', 'unique', 'index', 'fulltext'];
        foreach ($this->columns as $column) {
            foreach ($FluentIndexes as $index) {
                // If the index has been specified on the given column, but is simply
                // equal to "true" (boolean), no name has been specified for this
                // index, so we will simply call the index methods without one.
                if ($column->$index === true) {
                    $this->$index($column->name);

                    continue 2;
                }

                // If the index has been specified on the column and it is something
                // other than boolean true, we will assume a name was provided on
                // the index specification, and pass in the name to the method.
                elseif (isset($column->$index)) {
                    $this->$index($column->name, $column->$index);

                    continue 2;
                }
            }
        }
    }
}
