<?php

namespace Belivey\GeoAdmin\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Belivey\GeoAdmin\Traits\HasGeometry;

class County extends Model
{
  use HasFactory, HasGeometry;

  protected $guarded = [];
  protected $geometry = ['boundary'];
}