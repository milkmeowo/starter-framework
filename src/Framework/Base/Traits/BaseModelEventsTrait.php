<?php

namespace Milkmeowo\Framework\Base\Traits;

use Milkmeowo\Framework\Base\Models\Observers\BaseModelObserver;
use Milkmeowo\Framework\Base\Repositories\Interfaces\BaseRepositoryEventsInterface;

/**
 * Whenever a new model is saved for the first time,
 * the creating and created events will fire.
 * If a model already existed in the database and the save method is called,
 * the updating / updated events will fire.
 * However, in both cases, the saving / saved events will fire.
 *
 * @CREATE: saving > creating > created > saved
 * @UPDATE: saving > updating > updated > saved
 * @DELETE: deleting > deleted
 * @RESTORE: restoring > restored
 */
trait BaseModelEventsTrait
{
    /**
     * @var BaseRepositoryEventsInterface
     */
    public static $repository;

    /**
     * booted. observe the base model event with priority 99.
     *
     * @return mixed
     */
    public static function bootBaseModelEventsTrait()
    {
        // Setup event bindings
        $priority = 99;

        static::observe(new BaseModelObserver(), $priority);
    }

    /**
     * set the related RepositoryEventsInterface.
     *
     * @param BaseRepositoryEventsInterface $repository
     */
    public function setRepository(BaseRepositoryEventsInterface $repository)
    {
        self::$repository = $repository;
    }

    /**
     * get the related RepositoryEventsInterface.
     *
     * @return BaseRepositoryEventsInterface
     */
    public function getRepository()
    {
        return self::$repository;
    }

    /**
     * Listen a creating model event.
     *
     * @return mixed the function result
     */
    public function onCreating()
    {

        // auto set related user id
        if ($this->autoRelatedUserId && empty($this->{static::RELATED_USER_ID}) && $this->hasTableColumn(static::RELATED_USER_ID)) {
            $user_id = $this->getAuthUserId();
            if ($user_id > 0) {
                $this->{static::RELATED_USER_ID} = $user_id;
            }
        }

        $repository = $this->getRepository();
        if ($repository instanceof BaseRepositoryEventsInterface) {
            $repository->onCreating();
        }
    }

    /**
     * Listen a created model event.
     *
     * @return mixed the function result
     */
    public function onCreated()
    {
        $repository = $this->getRepository();
        if ($repository instanceof BaseRepositoryEventsInterface) {
            $repository->onCreated();
        }
    }

    /**
     * Listen a updating model event.
     *
     * @return mixed the function result
     */
    public function onUpdating()
    {
        $repository = $this->getRepository();
        if ($repository instanceof BaseRepositoryEventsInterface) {
            $repository->onUpdating();
        }
    }

    /**
     * Listen a updated model event.
     *
     * @return mixed the function result
     */
    public function onUpdated()
    {
        $repository = $this->getRepository();
        if ($repository instanceof BaseRepositoryEventsInterface) {
            $repository->onUpdated();
        }
    }

    /**
     * Listen a saving model event.
     *
     * @return mixed the function result
     */
    public function onSaving()
    {

        // update ipstamps if true
        if ($this->ipstamps) {
            $this->updateIps();
        }

        // update userstamps if true
        if ($this->userstamps) {
            $this->updateUsers();
        }

        $repository = $this->getRepository();
        if ($repository instanceof BaseRepositoryEventsInterface) {
            $repository->onSaving();
        }
    }

    /**
     * Listen a saved model event.
     *
     * @return mixed the function result
     */
    public function onSaved()
    {
        $repository = $this->getRepository();
        if ($repository instanceof BaseRepositoryEventsInterface) {
            $repository->onSaved();
        }
    }

    /**
     * Listen a deleting model event.
     *
     * @return mixed the function result
     */
    public function onDeleting()
    {
        if (static::usingSoftDeletes()) {
            if ($this->hasTableColumn(static::DELETED_BY)) {
                $this->{static::DELETED_BY} = $this->getAuthUserId();
            }

            if ($this->hasTableColumn(static::DELETED_IP)) {
                $this->{static::DELETED_IP} = smart_get_client_ip();
            }

            $this->flushEventListeners();
            $this->save();
        }

        $repository = $this->getRepository();
        if ($repository instanceof BaseRepositoryEventsInterface) {
            $repository->onDeleting();
        }
    }

    /**
     * Listen a deleted model event.
     *
     * @return mixed the function result
     */
    public function onDeleted()
    {
        $repository = $this->getRepository();
        if ($repository instanceof BaseRepositoryEventsInterface) {
            $repository->onDeleted();
        }
    }

    /**
     * Listen a restoring model event.
     *
     * @return mixed the function result
     */
    public function onRestoring()
    {
        if ($this->hasTableColumn(static::DELETED_BY)) {
            $this->{static::DELETED_BY} = null;
        }
        if ($this->hasTableColumn(static::DELETED_IP)) {
            $this->{static::DELETED_IP} = null;
        }

        $repository = $this->getRepository();
        if ($repository instanceof BaseRepositoryEventsInterface) {
            $repository->onRestoring();
        }
    }

    /**
     * Listen a restored model event.
     *
     * @return mixed the function result
     */
    public function onRestored()
    {
        $repository = $this->getRepository();
        if ($repository instanceof BaseRepositoryEventsInterface) {
            $repository->onRestored();
        }
    }

    /**
     * Has the model loaded the SoftDeletes trait.
     *
     * @return bool
     */
    public static function usingSoftDeletes()
    {
        static $usingSoftDeletes;

        if (is_null($usingSoftDeletes)) {
            return $usingSoftDeletes = in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses(get_called_class()));
        }

        return $usingSoftDeletes;
    }
}
