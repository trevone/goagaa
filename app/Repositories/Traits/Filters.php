<?php

namespace App\Repositories\Traits;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Arr;

trait Filters
{
  /**
   * @var string
   */
  protected $search = '';

  /**
   * @var string
   */
  protected $filter_fields = '';

  /**
   * @var array
   */
  protected $constraints = [];

  /**
   * @var array
   */
  protected $or_constraints = [];

  /**
   * A bit better text search search. It will search for all words in the search string separatelly thgough given fields.
   * Not tested for performance and extra care have to be applied to coalesce with posible NULL fields
   * Does the job for this scale of business, though
   *
   * @param  Builder $query
   * @param  string $search
   * @param  string $fields_to_search
   * @return Builder
   */
  protected function search(Builder $query, string $search, string $fields_to_search): Builder
  {
    // This one separates search words by dashes, commas, spaces, dots, etc.
    //
    //  preg_match_all('/"(?:\\\\.|[^\\\\"])*"|\S+/', $search, $words1);
    preg_match_all('/(?<=")(?!\s)[^"]+(?=")|\w+/', $search, $words1);
    $words = Arr::flatten($words1);

    $query->where(function ($query) use ($words, $fields_to_search) {
      foreach ($words ?? [] as $word) {
        $word = preg_replace('/^"|"$/', '', $word);
        $search = '(' . $word . '.*)';
        $query->whereRaw('concat( ' . $fields_to_search . ' ) rlike "' . $search . '"');
      }
    });

    return $query;
  }

  /**
   * @param  Builder $query
   * @param  string $search
   * @return Builder
   */
  protected function applySearch(Builder $query): Builder
  {
    if ($this->search != '' &&  $this->filter_fields != '') {

      $this->search($query, $this->search, $this->filter_fields);
    }
    return $query;
  }

  /**
   * @inheritDoc
   */
  public function searchFor(string $search): self
  {
    $this->search = $search;

    return $this;
  }

  /**
   * @inheritDoc
   */
  public function where(string $field, $value, $comparison): self
  {
    $this->constraints[$field] = [$value, $comparison];

    return $this;
  }

  /**
   * @inheritDoc
   */
  public function orWhere(string $field, $value, $comparison): self
  {
    $this->or_constraints[$field] = [$value, $comparison];

    return $this;
  }

  /**
   * @inheritDoc
   */
  public function applyConstraints(Builder $query): Builder
  {
    foreach ($this->constraints as $field => $value) {

      if(is_array($value[0])) {
        $query->whereIn($field, $value[0]);
      }
      else if( $value[0] === null) {
        $query->whereNull($field, 'and', $value[1] == 'is' ? false : true);
      }
      else {
        $query->where($field, $value[1], $value[0]);
      }
    }

    return $query;
  }

  /**
   * @inheritDoc
   */
  public function applyOrConstraints(Builder $query): Builder
  {
    foreach ($this->or_constraints as $field => $value) {

      if(is_array($value[0])) {
        $query->whereIn($field, $value[0]);
      }
      else if( $value[0] === null) {
        $query->whereNull($field, 'and', $value[1] == 'is' ? false : true);
      }
      else {
        $query->where($field, $value[1], $value[0]);
      }
    }

    return $query;
  }

  /**
   * @inheritDoc
   */
  public function resetFilters(): self
  {
    $this->search = '';
    $this->filter_fields = '';
    $this->constraints = [];
    $this->or_constraints = [];

    return $this;
  }
}
