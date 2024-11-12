<?php

namespace App\Contracts\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface BaseRepository
{
	/**
	 * @param  bool $with_trashed To include soft deleted models, but overrides can use int type (for parent)
	 * @return Collection
	 */
	public function getAll() : Collection;

	/**
	 * Returns LengthAwarePaginator instance of the collection of models with applying rules and filters
	 *
	 * @param  array $params
	 * @return LengthAwarePaginator
	 */
	public function getAllPaginated( ...$params ) : LengthAwarePaginator;

	/**
	 * @param  int $id
	 * @return Model|null
	 */
	public function getById( int $id ) : ?Model;

	/**
	 * @param  int $id
	 * @param  array $attributes
	 * @return bool
	 */
	public function update( int $id, array $attributes ) : bool;

	/**
	 * @param  array $attributes
	 * @return Model
	 */
	public function create( array $attributes ) : Model;

	/**
	 * @param  array  $ids
	 * @param  array  $attributes
	 * @return Model
	 */
	public function updateOrCreate( array $ids, array $attributes ) : Model;

	/**
	 * @param  int $id
	 * @return bool
	 */
	public function delete( int $id ) : bool;

	/**
	 * @param  Model $model
	 * @param  string $relation
	 * @param  array $relation_ids
	 * @return array
	 */
	public function sync( Model $model, string $relation, array $relation_ids ) : array;

	/**
	 * @param  Model $model
	 * @param  string $relation
	 * @param  array $relation_ids
	 * @return array
	 */
	public function syncWithoutDetaching( Model $model, string $relation, array $relation_ids ) : array;

	/**
	 * @param  Model $model
	 * @param  string $relation
	 * @param  array $relation_ids
	 * @return int
	 */
	public function attach( Model $model, string $relation, array $relation_ids ): void;

	/**
	 * @param  Model $model
	 * @param  string $relation
	 * @param  array $relation_ids
	 * @return array
	 */
	public function detach( Model $model, string $relation, array $relation_ids ): int;

	/**
	 * Sets relations to be returned with the model (collection)
	 *
	 * @param array $relations
	 * @return self
	 */
	public function setRelations( array $relations = [] ) : self;

	/**
	 * @param string $by
	 * @param string $way
	 * @return this
	 */
	public function setOrder( string $by, string $direction ) : self;

	/**
	 * @param int $per_page
	 * @return self
	 */
	public function setPerPage(int $per_page): self;

	/**
	 * @param string $client_id
	 * @return self
	 */
	public function forClient(int $client_id): self;

	/**
	 * @param int $limit
	 * @return self
	 */
	public function setLimit(int $limit): self;

  /**
   * Applies the sorting order from the sorter trait
   *
   * @param  Builder $query
   * @return Builder
   */
  public function applyOrder(Builder $query): Builder;
}
