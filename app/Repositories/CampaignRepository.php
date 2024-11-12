<?php

namespace App\Repositories;

use App\Models\Campaign; 
use Illuminate\Support\Arr;
use App\Repositories\BaseRepository;  
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class CampaignRepository extends BaseRepository
{ 

  /**
   * @param Tile $model
   */
  public function __construct(Campaign $model)
  {
    $this->model = $model;
    $this->filter_fields = $this->model::FILTER_FIELDS;
    // parent::__construct();
  }
 
}