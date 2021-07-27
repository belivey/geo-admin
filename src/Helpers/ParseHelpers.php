<?php
namespace Belivey\GeoAdmin\Helpers;

class ParseHelpers
{
  protected static $municipalDistricts = [
    'ru:муниципальный район'
  ];

  protected static $urbans = [
    'ru:городской округ'
  ];

  public static function getDistrictType ($status) {
    if (in_array($status, self::municipalDistricts)) {
      return 'municipal';
    }

    if (in_array($status, self::urbans)) {
      return 'urban';
    }

    return null;
  }
}