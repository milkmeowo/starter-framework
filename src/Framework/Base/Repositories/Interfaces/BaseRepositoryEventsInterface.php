<?php
/**
 * BaseRepositoryEventsInterface.php.
 *
 * Description
 *
 * @author Milkmeowo <milkmeowo@gmail.com>
 */

namespace Milkmeowo\Framework\Base\Repositories\Interfaces;

interface BaseRepositoryEventsInterface
{
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
