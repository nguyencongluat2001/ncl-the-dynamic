<?php

namespace Modules\Core\Ncl\Http;

use Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Container\Container as App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Modules\Core\Ncl\Exceptions\ResponseExeption;
use Illuminate\Support\Str;

abstract class BaseRepository
{

    /**
     * @var App
     */
    protected $app;

    /**
     * @var Model
     */
    protected $model;

    public function __construct()
    {
        $this->app = new App();
        $this->makeModel();
    }

    abstract public function model();

    /**
     * @return Model
     */
    public function makeModel()
    {
        $model = $this->app->make($this->model());
        if (!$model instanceof Model) {
            throw new Exception("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }

        return $this->model = $model;
    }

    /**
     * Create model.
     *
     * @param array $data
     *
     * @return Model
     */
    public function create($data = [])
    {
        if (!$this->model->getIncrementing()) {
            $id = $this->model->getKeyName();
            if ($id) {
                $data[$id] = (string)Str::uuid();
            }
        }
        if (!isset($data['created_at'])) {
            $data['created_at'] = Carbon::now()->format('Y-m-d H:i:s');
        }
        if (!isset($data['updated_at'])) {
            $data['updated_at'] = Carbon::now()->format('Y-m-d H:i:s');
        }
        return $this->model->create($data);
    }

    /**
     * Get list model.
     *
     * @param mixed $data
     * @param array $relations
     * @param array $relationCounts
     *
     * @return Collection $entities
     */
    public function filter($data, $relations = [], $relationCounts = [])
    {
        $data = collect($data);

        // select list column
        $entities = $this->model->select($this->model->selectable ?? ['*']);

        $numberPage = $data->has('offset') && (int) $data['offset'] > 1 ? (int) $data['offset'] : 1;
        Paginator::currentPageResolver(function () use ($numberPage) {
            return $numberPage;
        });
        // load realtion counts
        if (count($relationCounts)) {
            $entities = $entities->withCount($relationCounts);
        }
        // load relations
        if (count($relations)) {
            $entities = $entities->with($relations);
        }
        if (count($data) && method_exists($this->model, 'filter')) {
            foreach ($data as $key => $value) {
                if ($value !== "") {
                    $entities = $this->model->filter($entities, $key, $value);
                }
            }
        }

        // order list
        $orderBy = $data->has('sort') && in_array($data['sort'], $this->model->sortable) ? $data['sort'] : "";

        if ($orderBy !== "") {
            $entities = $entities->orderBy($orderBy, $data->has('sortType') && $data['sortType'] == 1 ? 'asc' : 'desc');
        }

        $limit = $data->has('limit') ? (int) $data['limit'] : 50;
        if ($limit) {
            return $entities->paginate($limit);
        }
        return $entities->get();
    }

    /**
     * Update model.
     *
     * @param Model $entity
     * @param array $data
     *
     * @return Model
     */
    public function update($entity, $data = [])
    {
        if (!$entity || !$entity instanceof Model) {
            throw new ResponseExeption("Không tồn tại đối tượng!");
        }
        $data['updated_at'] = Carbon::now()->format('Y-m-d H:i:s');
        $entity->update($data);
        return $entity;
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
        return $this->model->updateOrCreate($condition, $data);
    }

    /**
     * Delete model.
     *
     * @param Model $entity
     *
     * @return void
     */
    public function delete($entity)
    {
        if (!$entity || !$entity instanceof Model) {
            throw new ResponseExeption("Không tồn tại đối tượng!");
        }
        $entity->delete();
        return $entity;
    }

    /**
     * Delete mutiple item.
     *
     * @param array $ids
     *
     * @return void
     */
    public function deleteMulti(array $ids = [])
    {
        $items = $this->model->whereIn('id', $ids)->delete();
    }


    /**
     * Get model count.
     *
     * @return int
     */
    public function count()
    {
        return $this->model->count();
    }

    /**
     * Get model total.
     *
     * @return int
     */
    public function total($field)
    {
        return $this->model->sum($field);
    }

    /**
     * Insert multiple values.
     *
     * @return int
     */
    public function insert($data)
    {
        return $this->model->insert($data);
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
        $raw = $field . ', count(' . $field . ') as ' . $field . '_count';

        return $this->model->select(DB::raw($raw))->groupBy($field)->get();
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
        $entity = $this->model->findOrFail($id);

        if (count($relations)) {
            return $entity->load($relations);
        }

        return $entity;
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
        $entity = $this->model->find($id);

        if (count($relations)) {
            return $entity->load($relations);
        }

        return $entity;
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

        $entities = $this->model->select($this->model->selectable ?? ['*']);


        if (count($relations)) {
            $entities = $entities->with($relations);
        }

        if (count($condition)) {
            foreach ($condition as $key => $value) {
                $entities = $entities->where($key, $value);
            }
        }

        return $entities;
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
        return $this->model->where($column, $value);
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
        return $this->model->whereIn($column, $values);
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
        return $this->model->select(...$column);
    }

    /**
     * Get all data.
     *
     * @return  List of Model
     */
    public function getAll()
    {
        return $this->model->all();
    }

    /**
     * Get model's fillable attribute.
     *
     * @return array
     */
    public function getFillable()
    {
        return $this->model->getFillable();
    }
}
