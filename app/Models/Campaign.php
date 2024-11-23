<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends BaseModel
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
  ];
  public const BOOL_ATTRIBUTES = [];
  public const JSON_ATTRIBUTES = [
 
  ];
  public const FILTER_FIELDS = 'coalesce(name, "")'  ;

  /**
   * The attributes that should be cast.
   *
   * @var array
   */
  protected $casts = [
 
  ];

 
  //
  // eloquent relationships
  //
   
  public function connectors() { return $this->hasMany( Connector::class ); }
  public function processes() { return $this->belongsToMany( Process::class ); }
 
  //
  // end of eloquent relationships
  //
}
