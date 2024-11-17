<?php

namespace App\Http\Resources\Process;

use Illuminate\Http\Request;
use App\Http\Resources\Process\ProcessIndex;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProcessCollection extends ResourceCollection
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
  public $collects = ProcessIndex::class;

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
          'label' => 'Class',
          'property' => 'class',
          'sortable' => true,
          'align' => 'left',
          'editable' => false,
          'type' => 'string',
        ],
      ]
    ];
  }
}
