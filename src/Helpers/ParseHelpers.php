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
    if (in_array($status, self::$municipalDistricts)) {
      return 'municipal';
    }

    if (in_array($status, self::$urbans)) {
      return 'urban';
    }

    return null;
  }

  public static function regionAddr ($meta) {
    return array_key_exists('ADDR_REGIO', $meta)
      ? $meta['ADDR_REGIO']
      : null;
  }

  public static function districtAddr ($meta) {
    return array_key_exists('ADDR_DISTR', $meta)
      ? $meta['ADDR_DISTR']
      : null;
  }

  public static function status ($meta) {
    return array_key_exists('OFFICIAL_S', $meta)
      ? $meta['OFFICIAL_S']
      : null;
  }

  public static function isValid ($meta) {
    return $meta['OSM_TYPE'] === 'relation';
  }

  public static function getSubDistrictType ($status) {
    if ($status === 'ru:внутригородская территория города федерального значения') {
      return 'federal';
    }

    if ($status === 'ru:городское поселение') {
      return 'urban';
    }

    if ($status === 'ru:сельское поселение') {
      return 'rural';
    }

    return null;
  }
}