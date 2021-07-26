<?php
namespace Belivey\GeoAdmin\Helpers;

class GeoHelpers
{
  public static function wktFromJson ($json) {
    $geometry = json_decode($json);
    $coordinates = $geometry->coordinates;

    if ($geometry->type == 'Polygon'){
        $wkt = 'ST_PolygonFromText(\'POLYGON (';

        foreach ($coordinates as $sub_index => $sub_poly) {
            $wkt .= $sub_index > 0 ? ',(' : '(';

            foreach ($sub_poly as $index => $point) {
                $wkt .= $point[1].' '.$point[0].',';
            }
            $wkt = substr($wkt, 0, -1);
            $wkt .= ')';
        }
        $wkt.=')\', 4326)';
    } elseif ($geometry->type == 'MultiPolygon') {
        $wkt = 'ST_MultiPolygonFromText(\'MULTIPOLYGON (';
        foreach ($coordinates as $poly_index => $poly) {
            $wkt .= $poly_index > 0 ? ',(' : '(';
            foreach ($poly as $sub_index => $sub_poly) {
                $wkt .= $sub_index > 0 ? ',(' : '(';

                foreach ($sub_poly as $index => $point) {
                    $wkt .= $point[1].' '.$point[0].',';
                }
                $wkt = substr($wkt, 0, -1);
                $wkt .= ')';
            }
            $wkt.=')';
        }
        $wkt.=')\', 4326)';;
    }
  }
}