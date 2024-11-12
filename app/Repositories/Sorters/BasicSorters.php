<?php

namespace App\Repositories\Sorters;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Arr;

trait BasicSorters
{
  /**
   * @param  Builder $query
   * @param  string  $direction
   * @return Builder
   */
  protected function orderBy_id(Builder $query, string $direction): Builder
  {
    return $query->orderBy('id', $direction);
  }

  /**
   * @param  Builder $query
   * @param  string  $direction
   * @return Builder
   */
  protected function orderBy_name(Builder $query, string $direction): Builder
  {
    return $query->orderBy('name', $direction);
  }

  /**
   * @param  Builder $query
   * @param  string  $direction
   * @return Builder
   */
  protected function orderBy_status(Builder $query, string $direction): Builder
  {
    return $query->orderBy('status', $direction);
  }

  /**
   * @param  Builder $query
   * @param  string  $direction
   * @return Builder
   */
  protected function orderBy_order(Builder $query, string $direction): Builder
  {
    return $query->orderBy('order', $direction);
  }

  /**
   * @param  Builder $query
   * @param  string  $direction
   * @return Builder
   */
  protected function orderBy_title(Builder $query, string $direction): Builder
  {
    return $query->orderBy('title', $direction);
  }

  /**
   * @param  Builder $query
   * @param  string  $direction
   * @return Builder
   */
  protected function orderBy_updated_at(Builder $query, string $direction): Builder
  {
    return $query->orderBy('updated_at', $direction);
  }

  /**
   * @param  Builder $query
   * @param  string  $direction
   * @return Builder
   */
  protected function orderBy_created_at(Builder $query, string $direction): Builder
  {
    return $query->orderBy('created_at', $direction);
  }
}
