<?php

namespace App\Http\Resources\Connector;

use Illuminate\Http\Request;
use App\Http\Resources\Connector\ConnectorIndex;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ConnectorCollection extends ResourceCollection
{
  /**
   * The "data" wrapper that should be applied.
   *
   * @var string|null
   */
  public static $wrap = 'data';

  /**
   * The resource that this resource collects.
   *
   * @var string
   */
  public $collects = ConnectorIndex::class;

  /**
   * Transform the resource collection into an array.
   *
   * @return array<int|string, mixed>
   */
  public function toArray(Request $request): array
  {
    // return parent::toArray($request);

    return [
      'data' => $this->collection,
      'headers' => [
        [
          'label' => 'Id',
          'property' => 'id',
          'sortable' => true,
          'align' => 'left',
          'editable' => false,
          'type' => 'numeric',
        ], 
        [
          'label' => 'Name',
          'property' => 'name',
          'sortable' => true,
          'align' => 'left',
          'editable' => false,
          'type' => 'string',
        ],    
      ]
    ];
  }
}