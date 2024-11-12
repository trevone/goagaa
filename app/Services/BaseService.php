<?php

namespace App\Services;

use App\Contracts\Services\BaseService as BaseServiceInterface;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class BaseService implements BaseServiceInterface
{
  /**
   * @var BaseRepository
   */
  protected $repository;

  /**
   * @inheritDoc
   */
  public function getAll(): Collection
  {
    return $this->repository->getAll();
  }

  /**
   * Returns LengthAwarePaginator instance of the collection of models with applying rules and filters
   *
   * @param  array $params
   * @return LengthAwarePaginator
   */
  public function getAllPaginated(...$params): LengthAwarePaginator
  {
    return $this->repository->getAllPaginated($params);
  }

  /**
   * @inheritDoc
   */
  public function getById(int $id): ?Model
  {
    return $this->repository->getById($id);
  }

  /**
   * @inheritDoc
   */
  public function takeFirst(): ?Model
  {
    return $this->repository->setLimit(1)->getAll()->first();
  }

  /**
   * @inheritDoc
   */
  public function update(int $id, array $attributes): bool
  {
    return $this->repository->update($id, $attributes);
  }

  /**
   * @inheritDoc
   */
  public function create(array $attributes): Model
  {
    return $this->repository->create($attributes);
  }

  /**
   * @inheritDoc
   */
  public function updateOrCreate(array $ids, array $attributes): Model
  {
    return $this->repository->updateOrCreate($ids, $attributes);
  }

  /**
   * @inheritDoc
   */
  public function delete(int $id): bool
  {
    return $this->repository->delete($id);
  }

  /**
   * @inheritDoc
   */
  public function sync(Model $model, string $relation, array $relation_ids): array
  {
    return $this->repository->sync($model, $relation, $relation_ids);
  }

  /**
   * @inheritDoc
   */
  public function syncWithoutDetaching(Model $model, string $relation, array $relation_ids): array
  {
    return $this->repository->syncWithoutDetaching($model, $relation, $relation_ids);
  }

  /**
   * @inheritDoc
   */
  public function attach(Model $model, string $relation, array $relation_ids): void
  {
    $this->repository->attach($model, $relation, $relation_ids);
  }

  /**
   * @inheritDoc
   */
  public function detach(Model $model, string $relation, array $relation_ids): int
  {
    return $this->repository->detach($model, $relation, $relation_ids);
  }

  /**
   * @inheritDoc
   */
  public function setRelations(array $relations = []): self
  {
    $this->repository->setRelations($relations);

    return $this;
  }

  /**
   * @inheritDoc
   */
  public function setAggregates(array $aggregates = []): self
  {
    $this->repository->setAggregates($aggregates);

    return $this;
  }

  /**
   * @inheritDoc
   */
  public function order(string $by, string $way): self
  {
    $this->repository->setOrder($by, $way);

    return $this;
  }

  /**
   * @inheritDoc
   */
  public function perPage(int $per_page): self
  {
    $this->repository->setPerPage($per_page);

    return $this;
  }

  /**
   * @inheritDoc
   */
  public function forClient(int $client_id = 0): self
  {
    $this->repository->forClient($client_id);

    return $this;
  }

  /**
   * @inheritDoc
   */
  public function search(string $search = ''): self
  {
    $this->repository->searchFor($search);

    return $this;
  }

  /**
   * @inheritDoc
   */
  public function where(string $field, $value, $comparison = '='): self
  {
    $this->repository->where($field, $value, $comparison);

    return $this;
  }

  /**
   * @inheritDoc
   */
  public function orWhere(string $field, $value, $comparison = '='): self
  {
    $this->repository->orWhere($field, $value, $comparison);

    return $this;
  }

  /**
   * @inheritDoc
   */
  public function whereIn(string $field, array $value): self
  {
    $this->repository->where($field, $value, 'in');

    return $this;
  }

  /**
   * @inheritDoc
   */
  public function resetFilters(?string $field = null, $value = null): self
  {
    $this->repository->resetFilters();

    if ($field != null) {

      $this->where($field, $value);
    }

    return $this;
  }

  /**
   * @inheritDoc
   */
  public function limit(int $limit): self
  {
    $this->repository->setLimit($limit);

    return $this;
  }
}
