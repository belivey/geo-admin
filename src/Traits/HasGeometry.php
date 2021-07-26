<?php

namespace Belivey\GeoAdmin\Traits;

trait HasGeometry {
  protected $geometryAsText = true;

  public function getBoundaryAttribute($value) {
    return json_decode($value);
  }

  public function newQuery($excludeDeleted = true)
  {
      if (!empty($this->geom) && $this->geometryAsText === true)
      {
          $raw = '';
          foreach ($this->geom as $column)
          {
              $raw .= 'ST_AsGeoJson(`' . $this->table . '`.`' . $column . '`) as `' . $column . '`, ';
          }
          $raw = substr($raw, 0, -2);
          return parent::newQuery($excludeDeleted)->addSelect('*', \DB::raw($raw));
      }
      return parent::newQuery($excludeDeleted);
  }
} 