<?php

namespace Belivey\GeoAdmin\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Belivey\GeoAdmin\Models\HasGeometry;

class Region extends Model
{
  use HasFactory, HasGeometry;

  // Disable Laravel's mass assignment protection
  protected $guarded = [];
  protected $geometry = ['boundary'];
}