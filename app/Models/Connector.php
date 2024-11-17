<?php

namespace App\Models;

use App\Models\Campaign;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Connector extends BaseModel
{
  use HasFactory;  


  public const STATUS_PUBLISHED = 'published';
  public const STATUS_DRAFT = 'draft';
  public const STATUS_DISABLED = 'disabled';

  public const STATUSES = [
    self::STATUS_PUBLISHED,
    self::STATUS_DRAFT,
    self::STATUS_DISABLED,
  ];

  public const ATTRIBUTES = [
    'name',
    'output',
    'status',
  ];
  public const BOOL_ATTRIBUTES = [];
  public const JSON_ATTRIBUTES = [
    'data'
  ];
  public const FILTER_FIELDS = 'coalesce(title, "")' 
    .', " ", coalesce(text, "")';

  /**
   * The attributes that should be cast.
   *
   * @var array
   */
  protected $casts = [
    'data' => 'json'
  ];

 
  //
  // eloquent relationships
  //
 
  public function process() { return $this->belongsTo( Process::class ); }
  public function campaign() { return $this->belongsTo( Campaign::class ); }

  public function output_connector() { return $this->hasOne( Connector::class, 'id', 'connector_id' ); }

  //
  // end of eloquent relationships
  //
}
