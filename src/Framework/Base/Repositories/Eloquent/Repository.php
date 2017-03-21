<?php

namespace Milkmeowo\Framework\Base\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Prettus\Repository\Eloquent\BaseRepository;
use Milkmeowo\Framework\Base\Presenters\Presenter;
use Prettus\Repository\Traits\CacheableRepository;
use Prettus\Validator\Contracts\ValidatorInterface;
use Illuminate\Database\Eloquent\Relations\Relation;
use Prettus\Repository\Contracts\CacheableInterface;
use Prettus\Repository\Events\RepositoryEntityDeleted;
use Prettus\Repository\Events\RepositoryEntityUpdated;
use Prettus\Repository\Exceptions\RepositoryException;
use Milkmeowo\Framework\Base\Traits\BaseRepositoryEventsTrait;
use Milkmeowo\Framework\Base\Models\Contracts\BaseModelEventObserverable;
use Milkmeowo\Framework\Base\Repositories\Interfaces\RepositoryInterface;
use Milkmeowo\Framework\Base\Repositories\Interfaces\BaseRepositoryEventsInterface;

abstract class Repository extends BaseRepository implements RepositoryInterface, CacheableInterface, BaseRepositoryEventsInterface
{
    use CacheableRepository;
    /* Model Observers */
    use BaseRepositoryEventsTrait;

    /**
     * @var Model
     */
    protected $relateModel;

    /**
     * @param array $attributes
     */
    public function validateCreate(array $attributes)
    {
        if (! is_null($this->validator)) {
            $this->validator->with($attributes)->passesOrFail(ValidatorInterface::RULE_CREATE);
        }
    }

    /**
     * @param array $attributes
     */
    public function validateUpdate(array $attributes)
    {
        if (! is_null($this->validator)) {
            $this->validator->with($attributes)->passesOrFail(ValidatorInterface::RULE_UPDATE);
        }
    }

    /**
     * @param $validator
     *
     * @return $this
     */
    public function setValidator($validator)
    {
        $this->validator = $validator;

        return $this;
    }

    /**
     * @param $results
     *
     * @return mixed
     */
    public function present($results)
    {
        return $this->parserResult($results);
    }

    /**
     * @param Model $relateModel
     *
     * @return $this
     */
    public function setRelateModel(Model $relateModel)
    {
        $this->relateModel = $relateModel;
        if ($relateModel) {
            $this->makeModel();
        }

        return $this;
    }

    /**
     * @return Model
     * @throws RepositoryException
     */
    public function makeModel()
    {
        $model = $this->relateModel ? $this->relation() : $this->app->make($this->model());
        if (! ($model instanceof Model || $model instanceof Relation)) {
            throw new RepositoryException('Class '.get_class($model).' must be an instance of Illuminate\\Database\\Eloquent\\Model');
        }

        if ($model instanceof BaseModelEventObserverable) {
            $model->setRepository($this); //set repository
        }

        return $this->model = $model;
    }

    /**
     * @return Relation
     */
    public function relation()
    {
    }

    /**
     * Retrieve data array for populate field select.
     *
     * @param string      $column
     * @param string|null $key
     *
     * @return \Illuminate\Support\Collection|array
     */
    public function lists($column, $key = null)
    {
        $this->applyCriteria();
        $this->applyScope();

        $lists = $this->model->lists($column, $key);

        $this->resetModel();

        return $lists;
    }

    /**
     * Retrieve all data of repository.
     *
     * @param array $columns
     *
     * @return mixed
     */
    public function all($columns = ['*'])
    {
        $this->applyCriteria();
        $this->applyScope();
        if ($this->model instanceof Builder || $this->model instanceof Relation) {
            $results = $this->model->get($columns);
        } else {
            $results = $this->model->all($columns);
        }
        $this->resetModel();

        return $this->parserResult($results);
    }

    /**
     * @return \Prettus\Repository\Contracts\PresenterInterface
     */
    public function getPresenter()
    {
        return $this->presenter;
    }

    /**
     * @param array $meta
     */
    public function setPresenterMeta(array $meta)
    {
        if ($this->presenter instanceof Presenter) {
            $this->presenter->setMeta($meta);
        }
    }

    /**
     * Where first.
     *
     * @param array $where
     * @param array $columns
     *
     * @return mixed
     */
    public function whereFirst(array $where, $columns = ['*'])
    {
        return $this->where($where)->firstOrFail($columns = ['*']);
    }

    /**
     * Retrieve first data of repository with fail if not found.
     *
     * @param string[] $columns
     *
     * @return mixed
     */
    public function firstOrFail($columns = ['*'])
    {
        $this->applyCriteria();
        $this->applyScope();
        $results = $this->model->firstOrFail($columns);
        $this->resetModel();

        return $this->parserResult($results);
    }

    /**
     * Find data by where conditions.
     *
     * @param array $where
     *
     * @return $this
     */
    public function where(array $where)
    {
        $this->applyCriteria();
        $this->applyScope();
        $this->applyConditions($where);

        return $this;
    }

    /**
     * Add a basic where clause to the model.
     *
     * @param  string|array|\Closure $column
     * @param  mixed                 $value
     *
     * @return $this
     */
    protected function modelWhere($column, $value = null)
    {
        $this->model = $this->model->where($column, $value);

        return $this;
    }

    /**
     * withTrashed.
     *
     * @return $this
     */
    public function withTrashed()
    {
        $this->model = $this->model->withTrashed();

        return $this;
    }

    /**
     * without-trashed.
     *
     * @return $this
     */
    public function withoutTrashed()
    {
        $this->model = $this->model->withoutTrashed();

        return $this;
    }

    /**
     * onlyTrashed.
     *
     * @return $this
     */
    public function onlyTrashed()
    {
        $this->model = $this->model->onlyTrashed();

        return $this;
    }

    /**
     * Restore a entity in repository by id.
     *
     * @param $id
     *
     * @return mixed
     */
    public function restore($id)
    {
        $this->applyScope();

        $temporarySkipPresenter = $this->skipPresenter;
        $this->skipPresenter(true);

        $model = $this->withTrashed()->find($id);

        $this->skipPresenter($temporarySkipPresenter);
        $this->resetModel();

        $restored = $model->restore();

        event(new RepositoryEntityUpdated($this, $model));

        if ($restored) {
            $restored = $model;
        }

        return $this->parserResult($restored);
    }

    /**
     * restore multiple entities by given criteria.
     *
     * @param array $where
     *
     * @return int
     */
    public function restoreWhere(array $where)
    {
        $this->applyScope();

        $temporarySkipPresenter = $this->skipPresenter;
        $this->skipPresenter(true);

        $this->applyConditions($where);

        $restored = $this->model->withTrashed()->restore();

        event(new RepositoryEntityUpdated($this, $this->model));

        $this->skipPresenter($temporarySkipPresenter);
        $this->resetModel();

        if ($restored) {
            $restored = $this->model;
        }

        return $this->parserResult($restored);
    }

    /**
     * ForceDelete a entity in repository by id.
     *
     * @param $id
     *
     * @return int
     */
    public function ForceDelete($id)
    {
        $this->applyScope();

        $temporarySkipPresenter = $this->skipPresenter;
        $this->skipPresenter(true);

        $model = $this->find($id);
        $originalModel = clone $model;

        $this->skipPresenter($temporarySkipPresenter);
        $this->resetModel();

        $deleted = $model->ForceDelete();

        event(new RepositoryEntityDeleted($this, $originalModel));

        return $deleted;
    }

    /**
     * ForceDelete multiple entities by given criteria.
     *
     * @param array $where
     *
     * @return null|bool
     */
    public function forceDeleteWhere(array $where)
    {
        $this->applyScope();

        $temporarySkipPresenter = $this->skipPresenter;
        $this->skipPresenter(true);

        $this->applyConditions($where);

        $deleted = $this->model->ForceDelete();

        event(new RepositoryEntityDeleted($this, $this->model));

        $this->skipPresenter($temporarySkipPresenter);
        $this->resetModel();

        return $deleted;
    }

    /**
     * Rewrite delete method to support soft delete return.
     *
     * @param $id
     *
     * @return bool|int
     */
    public function delete($id)
    {
        $deleted = false;

        $deleted = parent::delete($id);
        $isSoftDelete = method_exists($this->model(), 'runSoftDelete');
        if ($isSoftDelete) {
            $deleted = $this->skipPresenter()->withTrashed()->find($id)->trashed();
        }

        return $deleted;
    }
}
