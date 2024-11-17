<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Process extends BaseModel
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
    'class',
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
 
  public function connectors() { return $this->hasMany( Connector::class ); }
  //
  // end of eloquent relationships
  //
}
