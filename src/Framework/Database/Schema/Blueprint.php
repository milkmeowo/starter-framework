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
}
