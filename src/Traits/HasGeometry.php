<?php

namespace Belivey\GeoAdmin\Traits;

trait HasGeometry {
  protected $geometryAsText = true;

  public function getBoundaryAttribute($value) {
    return json_decode($value);
  }

  public function newQuery($excludeDeleted = true)
  {
      if (!empty($this->geometry) && $this->geometryAsText === true)
      {
          $raw = '';
          foreach ($this->geometry as $column)
          {
              $raw .= 'ST_AsGeoJson(`' . $this->table . '`.`' . $column . '`) as `' . $column . '`, ';
              $raw .= 'ST_AsWkt(`' . $this->table . '`.`' . $column . '`) as `wkt`, ';
          }
          $raw = substr($raw, 0, -2);
          return parent::newQuery($excludeDeleted)->addSelect('*', \DB::raw($raw));
      }
      return parent::newQuery($excludeDeleted);
  }

  public static function getByIntersect ($wkt) {
    return self::whereRaw('ST_Intersects(boundary,'.$wkt.')')->first();
  }
} 