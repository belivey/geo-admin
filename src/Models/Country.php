<?php

namespace Belivey\GeoAdmin\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Belivey\GeoAdmin\Models\HasGeometry;

class Country extends Model
{
  use HasFactory, HasGeometry;

  protected $guarded = [];
  protected $geometry = ['boundary'];

  public function counties () {
    return $this->hasMany(County::class);
  }

  public function regions () {
    return $this->counties()->hasMany(Region::class);
  }

  public static function getByIntersect ($wkt) {
    return self::whereRaw('ST_Intersects(boundary,'.$wkt.')')->first();
  }
}