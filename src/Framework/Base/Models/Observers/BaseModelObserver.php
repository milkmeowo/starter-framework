<?php
/**
 * BaseModelObserver.php.
 *
 * Description
 *
 * @author Milkmeowo <milkmeowo@gmail.com>
 */
namespace Milkmeowo\Framework\Base\Models\Observers;

use Milkmeowo\Framework\Base\Models\Contracts\BaseModelEventObserverable;

class BaseModelObserver
{
    public function creating(BaseModelEventObserverable $model)
    {
        $model->onCreating();
    }

    public function created(BaseModelEventObserverable $model)
    {
        $model->onCreated();
    }

    public function updating(BaseModelEventObserverable $model)
    {
        $model->onUpdating();
    }

    public function updated(BaseModelEventObserverable $model)
    {
        $model->onUpdated();
    }

    public function saving(BaseModelEventObserverable $model)
    {
        $model->onSaving();
    }

    public function saved(BaseModelEventObserverable $model)
    {
        $model->onSaved();
    }

    public function deleting(BaseModelEventObserverable $model)
    {
        $model->onDeleting();
    }

    public function deleted(BaseModelEventObserverable $model)
    {
        $model->onDeleted();
    }

    public function restoring(BaseModelEventObserverable $model)
    {
        $model->onRestoring();
    }

    public function restored(BaseModelEventObserverable $model)
    {
        $model->onRestored();
    }
}
