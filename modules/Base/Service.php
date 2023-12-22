<?php

namespace Modules\Base;

use Illuminate\Container\Container as App;
use Exception;
use Modules\Base\Repository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

abstract class Service
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
        if (!$repository instanceof Repository) {
            throw new Exception("Class {$this->repository()} must be an instance of Modules\Base\Repository");
        }

        $this->repository = $repository;
    }
    /**
     * Thêm mới dữ liệu.
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
     * Danh sách dữ liệu.
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
     * Cập nhật dữ liệu.
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
     * Xóa dữ liệu.
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
     * Thêm mới hoặc cập nhật dữ liệu.
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
     * Tổng số dữ liệu.
     *
     * @return int
     */
    public function count()
    {
        return $this->repository->count();
    }

    /**
     * Đếm dữ liệu.
     *
     * @return int
     */
    public function total($field)
    {
        return $this->repository->total($field);
    }

    /**
     * Thêm mới dữ liệu.
     *
     * @return int
     */
    public function insert($data)
    {
        return $this->repository->insert($data);
    }

    /**
     * Lấy thông tin dữ liệu bằng id .
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
     *  Lấy thông tin dữ liệu bằng id có điều kiện.
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
     * Điều kiện dữ liệu .
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
     * Điều kiện where in  whereIn('name',['LUATNC','NGUYENCONGLUAT'])
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
     * truy vấn dữ liệu .
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
     * Lấy tất cả dữ liệu trong bảng.
     *
     * @return  List of Model
     */
    public function getAll()
    {
        return $this->repository->getAll();
    }

    public function getSql(){
        $builder = $this->repository;
        $bindings = $builder->getBindings();
        $sql = str_replace('?', "'%s'", $builder->toSql());
        $sql = sprintf($sql, ...$bindings);
       return $sql;
    }

}
