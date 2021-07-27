<?php

namespace Belivey\GeoAdmin\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Belivey\GeoAdmin\Traits\HasGeometry;

class SubDistrict extends Model
{
  use HasFactory, HasGeometry;

  // Disable Laravel's mass assignment protection
  protected $guarded = [];
  protected $geometry = ['boundary'];

  public function region () {
    return $this->belongsTo(Region::class);
  }

  public function districtUnion () {
    return $this->belongsTo(DistrictUnion::class);
  }

  public function district () {
    return $this->belongsTo(District::class);
  }
}