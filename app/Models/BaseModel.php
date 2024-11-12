<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    use HasFactory;

    public const ATTRIBUTES = [];
    public const BOOL_ATTRIBUTES = [];
    public const JSON_ATTRIBUTES = [];
    public const ADMIN_ATTRIBUTES = [];
    public const FILTER_FIELDS = '';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
}
