<?php

namespace App\Repositories;

use App\Contracts\Repositories\BaseRepository as BaseRepositoryInterface;
use App\Repositories\Sorters\BasicSorters;
use App\Repositories\Traits\Filters;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class BaseRepository implements BaseRepositoryInterface
{
  use Filters;
  use BasicSorters;

  /**
   * @var Model
   */
  protected $model;

  /**
   * @var integer
   */
  protected $client_id = 0;

  /**
   * Eloquent model relations that should be loaded with the model by default
   *
   * @var array
   */
  protected $relations = [];

  /**
   * Eloquent model relation counts that should be loaded with the model by default
   *
   * @var array
   */
  protected $aggregates = [];

  /**
   * orderBy column
   * @var string
   */
  protected $order_by = 'id';

  /**
   * orderBy direction
   * @var string
   */
  protected $order_direction = 'desc';

  /**
   * orderBy direction
   * @var string
   */
  protected $per_page = 50;

  /**
   * limit
   * @var int
   */
  protected $limit = null;

  /**
   * @inheritDoc
   */
  public function getAll(): Collection
  {
  
    $return = $this->model->where(function ($query) {
        if ($this->client_id !== 0) {
          $query->where('institution_id', $this->client_id);
        }
      })
      ->where(function ($query) {
        $this->applySearch($query->getQuery(), $this->search);
      })
      ->where(function ($query) {
        $this->applyConstraints($query->getQuery());
      })
      ->orWhere(function ($query) {
        $this->applyOrConstraints($query->getQuery());
      })
      ->with($this->relations)
      ->withCount($this->aggregates)
      ->when(true, function ($query) {
        $this->applyOrder($query->getQuery());
      })
      ->limit($this->limit)
      ->get();
   
 

    $this->resetFilters();
    $this->resetOrder();
    return $return;
  }

  /**
   * @inheritDoc
   */
  public function getAllPaginated(...$params): LengthAwarePaginator
  {
    $return = $this->model->where(function ($query) {
        if ($this->client_id !== 0) {
          $query->where('institution_id', $this->client_id);
        }
      })
      ->with($this->relations)
      ->withCount($this->aggregates)
      ->where(function ($query) {
        $this->applySearch($query->getQuery(), $this->search);
      })
      ->where(function ($query) {
        $this->applyConstraints($query->getQuery());
      })
      ->orWhere(function ($query) {
        $this->applyOrConstraints($query->getQuery());
      })
      ->when(true, function ($query) {
        $this->applyOrder($query->getQuery());
      })
 
      ->paginate($this->per_page)
      ->withQueryString();

    $this->resetFilters();
    $this->resetOrder();
    return $return;
  }

  /**
   * @inheritDoc
   */
  public function getById(int $id): ?Model
  {
    return $this->model->with($this->relations)->withCount($this->aggregates)->find($id);
  }

  /**
   * @inheritDoc
   */
  public function update(int $id, array $attributes): bool
  {
    return $this->model->find($id)->update($attributes);
  }

  /**
   * @inheritDoc
   */
  public function create(array $attributes): Model
  {
    return $this->model->create($attributes);
  }

  /**
   * @inheritDoc
   */
  public function updateOrCreate(array $ids, array $attributes): Model
  {
    return $this->model->updateOrCreate($ids, $attributes);
  }

  /**
   * @inheritDoc
   */
  public function delete(int $id): bool
  {
    return $this->model->find($id)->delete();
  }

  /**
   * @inheritDoc
   */
  public function sync(Model $model, string $relation, array $relation_ids): array
  {
    return $model->$relation()->sync(array_filter($relation_ids));
  }

  /**
   * @inheritDoc
   */
  public function syncWithoutDetaching(Model $model, string $relation, array $relation_ids): array
  {
    return $model->$relation()->syncWithoutDetaching($relation_ids);
  }

  /**
   * @inheritDoc
   */
  public function attach(Model $model, string $relation, array $relation_ids): void
  {
    $model->$relation()->attach($relation_ids);
  }

  /**
   * @inheritDoc
   */
  public function detach(Model $model, string $relation, array $relation_ids): int
  {
    return $model->$relation()->detach($relation_ids);
  }

  /**
   * @inheritDoc
   */
  public function setRelations(array $relations = []): self
  {
    $this->relations = $relations;

    return $this;
  }

  /**
   * @inheritDoc
   */
  public function setAggregates(array $aggregates = []): self
  {
    $this->aggregates = $aggregates;

    return $this;
  }

  /**
   * @inheritDoc
   */
  public function setOrder(string $by, string $direction): self
  {
    $this->order_by = $by;
    $this->order_direction = $direction;

    return $this;
  }

  /**
   * @inheritDoc
   */
  public function setPerPage(int $per_page): self
  {
    $this->per_page = $per_page;

    return $this;
  }

  /**
   * @inheritDoc
   */
  public function forClient(int $client_id): self
  {
    $this->client_id = $client_id;

    return $this;
  }

  /**
   * @inheritDoc
   */
  public function setLimit(int $limit): self
  {
    $this->limit = $limit;

    return $this;
  }

  /**
   * @inheritDoc
   */
  public function applyOrder(Builder $query): Builder
  {
    if (method_exists($this, 'orderBy_' . $this->order_by)) {
      return call_user_func_array([$this, 'orderBy_' . $this->order_by], [$query, $this->order_direction]);
    }

    return $query;
  }

  /**
   * Resets the ordering values
   *
   * @return void
   */
  protected function resetOrder(): void
  {
    $this->order_by = 'id';
    $this->order_direction = 'desc';
  }
}
