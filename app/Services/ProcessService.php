<?php

namespace App\Services;

use App\Repositories\ProcessRepository;
use App\Services\BaseService;
use Illuminate\Pagination\LengthAwarePaginator;

class ProcessService extends BaseService
{
  /**
   * @param ProcessRepository $repository
   */
  public function __construct(ProcessRepository $repository)
  {
    $this->repository = $repository;
  } 
}