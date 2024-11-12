<?php

namespace App\Services;

use App\Repositories\CampaignRepository;
use App\Services\BaseService;
use Illuminate\Pagination\LengthAwarePaginator;

class CampaignService extends BaseService
{
  /**
   * @param CampaignRepository $repository
   */
  public function __construct(CampaignRepository $repository)
  {
    $this->repository = $repository;
  } 
}