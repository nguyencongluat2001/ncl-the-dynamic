<?php

namespace Modules\Core\Ncl\Http;

use Illuminate\Container\Container as App;
use Exception;
use Modules\Core\Ncl\Http\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

abstract class BaseService
{

    /**
     * @var App
     */
    private $app;

    /**
     * @var Repository
     */
    protected $repository;

    public function __construct()
    {
        $this->app = new App();
        $this->makeRepository();
    }

    abstract public function repository();

    /**
     * @return Repository
     */
    public function makeRepository()
    {
        $repository = $this->app->make($this->repository());
        if (!$repository instanceof BaseRepository) {
            throw new Exception("Class {$this->repository()} must be an instance of Modules\Core\Ncl\Http\BaseRepository");
        }

        $this->repository = $repository;
    }

    /**
     * Create model.
     *
     * @param  array $data
     *
     * @return  Model
     */
    public function create($data)
    {
        return $this->repository->create($data);
    }

    /**
     * Get list.
     *
     * @param  mixed $conditions
     * @param  array $relations
     * @param  array $relationCounts
     *
     * @return  Collection $entities
     */
    public function filter($conditions, $relations = [], $relationCounts = [])
    {
        $list = $this->repository->filter($conditions, $relations, $relationCounts);
        return $list;
    }

    /**
     * Update model.
     *
     * @param  Model $entity
     * @param  array $data
     *
     * @return  Model
     */
    public function update($entity, $data = [])
    {
        $this->repository->update($entity, $data);

        return $entity;
    }

    /**
     * Delete model.
     *
     * @param  Model $entity
     *
     * @return  void
     */
    public function delete($entity)
    {
        return $this->repository->delete($entity);
    }

    /**
     * Get model detail.
     *
     * @param Model $entity
     *
     * @return Model
     */
    public function detail(Model $entity, $relations = [])
    {
        return $this->repository->detail($entity, $relations);
    }

    /**
     * Update or create model.
     *
     * @param array $condition
     * @param array $data
     *
     * @return Model
     */
    public function updateOrCreate($condition = [], $data = [])
    {
        return $this->repository->updateOrCreate($condition, $data);
    }

    /**
     * Get model count.
     *
     * @return int
     */
    public function count()
    {
        return $this->repository->count();
    }

    /**
     * Get model total.
     *
     * @return int
     */
    public function total($field)
    {
        return $this->repository->total($field);
    }

    /**
     * Insert multiple values.
     *
     * @return int
     */
    public function insert($data)
    {
        return $this->repository->insert($data);
    }

    /**
     * Group model by column.
     *
     * @param string $field
     *
     * @return void
     */
    public function groupBy($field)
    {
        return $this->repository->groupBy($field);
    }

    /**
     * Find model by id.
     *
     * @param mixed $id
     * @param array $relations
     *
     * @return Model
     */
    public function findOrFail($id, $relations = [])
    {
        return $this->repository->findOrFail($id, $relations);
    }

    /**
     * Find model by id.
     *
     * @param mixed $id
     * @param array $relations
     *
     * @return Model
     */
    public function find($id, $relations = [])
    {
        return $this->repository->find($id, $relations);
    }

    /**
     * Find by condition .
     *
     * @param mixed $request
     * @param array $relations
     *
     * @return object $entities
     */
    public function findWhere($condition, $relations = [])
    {
        return $this->repository->findWhere($condition, $relations);
    }

    /**
     * where by condition .
     *
     * @param mixed $column
     *
     * @return object $entities
     */
    public function where($column, $value)
    {
        return $this->repository->where($column, $value);
    }

    /**
     * where column in array values
     *
     * @param mixed $column
     *
     * @return object $entities
     */
    public function whereIn($column, $values = [])
    {
        return $this->repository->whereIn($column, $values);
    }

    /**
     * where by condition .
     *
     * @param mixed $column
     *
     * @return object $entities
     */
    public function select(...$column)
    {
        return $this->repository->select(...$column);
    }

    /**
     * Get all data.
     *
     * @return  List of Model
     */
    public function getAll()
    {
        return $this->repository->getAll();
    }


    /**
     * Get model's fillable attribute.
     *
     * @return array
     */
    public function getFillable()
    {
        return $this->repository->getFillable();
    }

    public function getSql(){
        $builder = $this->repository;
        $bindings = $builder->getBindings();
        $sql = str_replace('?', "'%s'", $builder->toSql());
        $sql = sprintf($sql, ...$bindings);
       return $sql;
    }

}
