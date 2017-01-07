<?php
/**
 * BaseModelObserver.php.
 *
 * Description
 *
 * @author Milkmeowo <milkmeowo@gmail.com>
 */
namespace Milkmeowo\Framework\Base\Models\Observers;

use Milkmeowo\Framework\Base\Models\Contracts\BaseModelEventsInterface;

class BaseModelObserver
{
    public function creating(BaseModelEventsInterface $model)
    {
        $model->onCreating();
    }

    public function created(BaseModelEventsInterface $model)
    {
        $model->onCreated();
    }

    public function updating(BaseModelEventsInterface $model)
    {
        $model->onUpdating();
    }

    public function updated(BaseModelEventsInterface $model)
    {
        $model->onUpdated();
    }

    public function saving(BaseModelEventsInterface $model)
    {
        $model->onSaving();
    }

    public function saved(BaseModelEventsInterface $model)
    {
        $model->onSaved();
    }

    public function deleting(BaseModelEventsInterface $model)
    {
        $model->onDeleting();
    }

    public function deleted(BaseModelEventsInterface $model)
    {
        $model->onDeleted();
    }

    public function restoring(BaseModelEventsInterface $model)
    {
        $model->onRestoring();
    }

    public function restored(BaseModelEventsInterface $model)
    {
        $model->onRestored();
    }
}
