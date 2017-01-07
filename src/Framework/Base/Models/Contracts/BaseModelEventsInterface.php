<?php

namespace Milkmeowo\Framework\Base\Models\Contracts;

use Milkmeowo\Framework\Base\Repositories\Interfaces\BaseRepositoryEventsInterface;

interface BaseModelEventsInterface
{
    /**
     * @param BaseRepositoryEventsInterface $repository
     *
     * @return mixed
     */
    public function setRepository(BaseRepositoryEventsInterface $repository);

    /**
     * @return mixed
     */
    public function getRepository();

    public function onCreating();
    public function onCreated();

    public function onUpdating();
    public function onUpdated();

    public function onSaving();
    public function onSaved();

    public function onDeleting();
    public function onDeleted();

    public function onRestoring();
    public function onRestored();
}
