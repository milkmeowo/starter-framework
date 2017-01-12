<?php

namespace Milkmeowo\Framework\Base\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Milkmeowo\Framework\Base\Models\Contracts\BaseModelEventObserverable;
use Milkmeowo\Framework\Base\Traits\BaseModelEventsTrait;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class BaseModel extends Model implements BaseModelEventObserverable,Transformable
{
    use BaseModelEventsTrait,TransformableTrait;

    /**
     * Indicates if the model should be auto set user_id.
     *
     * @var bool
     */
    protected $autoRelatedUserId = true;

    /**
     * The name of the "related user id" column.
     *
     * @var string
     */
    const RELATED_USER_ID = 'user_id';

    /**
     * Indicates if the model should be recorded users.
     *
     * @var bool
     */
    protected $userstamps = true;

    /**
     * The name of the "created by" column.
     *
     * @var string
     */
    const CREATED_BY = 'created_by';

    /**
     * The name of the "updated by" column.
     *
     * @var string
     */
    const UPDATED_BY = 'updated_by';

    /**
     * Indicates if the model should be recorded ips.
     *
     * @var bool
     */
    protected $ipstamps = true;

    /**
     * The name of the "created ip" column.
     *
     * @var string
     */
    const CREATED_IP = 'created_ip';

    /**
     * The name of the "updated ip" column.
     *
     * @var string
     */
    const UPDATED_IP = 'updated_ip';

    /**
     * The name of the "deleted ip" column.
     *
     * @var string
     */
    const DELETED_IP = 'deleted_ip';

    /**
     * The name of the "deleted by" column.
     *
     * @var string
     */
    const DELETED_BY = 'deleted_by';

    /**
     * @return \Dingo\Api\Auth\Auth
     */
    protected function api_auth()
    {
        return app('Dingo\Api\Auth\Auth');
    }
    /**
     * Update the creation and update ips.
     *
     * @return void
     */
    protected function updateIps()
    {
        $ip = smart_get_client_ip();

        if (! $this->isDirty(static::UPDATED_IP) && $this->hasTableColumn(static::UPDATED_IP)) {
            $this->{static::UPDATED_IP} = $ip;
        }

        if (! $this->exists && ! $this->isDirty(static::CREATED_IP) && $this->hasTableColumn(static::CREATED_IP)) {
            $this->{static::CREATED_IP} = $ip;
        }
    }

    /**
     * Get current model's user_id.
     *
     * @return mixed|null
     */
    public function getRelatedUserId()
    {
        return $this->{static::RELATED_USER_ID};
    }

    /**
     * Update the creation and update by users.
     *
     * @return void
     */
    protected function updateUsers()
    {
        $user_id = $this->getAuthUserId();
        if (! ($user_id > 0)) {
            return;
        }

        if (! $this->isDirty(static::UPDATED_BY) && $this->hasTableColumn(static::UPDATED_BY)) {
            $this->{static::UPDATED_BY} = $user_id;
        }

        if (! $this->exists && ! $this->isDirty(static::CREATED_BY) && $this->hasTableColumn(static::CREATED_BY)) {
            $this->{static::CREATED_BY} = $user_id;
        }
    }

    /**
     * Get current auth user.
     *
     * @return \Illuminate\Auth\GenericUser|Model|null
     */
    public function getAuthUser()
    {
        $user = null;
        if ($this->api_auth()->check()) {
            $user = $this->api_auth()->user();
        } elseif (Auth::check()) {
            $user = Auth::user();
        }

        return $user;
    }

    /**
     * Get current auth user_id.
     *
     * @return mixed|null
     */
    public function getAuthUserId()
    {
        $user_id = null;
        $user = $this->getAuthUser();
        if ($user) {
            $user_id = $user->getKey();
        }

        return $user_id;
    }

    /**
     * check auth user owner the current model.
     *
     * @return bool
     */
    public function isAuthUserOwner()
    {
        return $this->getAuthUserId() == $this->getRelatedUserId();
    }

    /**
     * get all the database table columns listing.
     *
     * @return array
     */
    public function getTableColumns()
    {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }

    /**
     * check column exist in table.
     *
     * @param $column
     *
     * @return bool
     */
    public function hasTableColumn($column)
    {
        return $this->getConnection()->getSchemaBuilder()->hasColumn($this->getTable(), $column);
    }

    /**
     * check columns exist in table.
     *
     * @param array $columns
     *
     * @return bool
     */
    public function hasTableColumns(array $columns)
    {
        return $this->getConnection()->getSchemaBuilder()->hasColumns($this->getTable(), $columns);
    }
}
