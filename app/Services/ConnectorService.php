<?php

namespace App\Services;

use App\Repositories\ConnectorRepository;
use App\Services\BaseService;
use Illuminate\Pagination\LengthAwarePaginator;

class ConnectorService extends BaseService
{
  /**
   * @param ConnectorRepository $repository
   */
  public function __construct(ConnectorRepository $repository)
  {
    $this->repository = $repository;
  } 
}