<?php
namespace Belivey\GeoAdmin\Database\Seeds;

use Illuminate\Database\Seeder;
use Belivey\GeoAdmin\Models\Region;
use Belivey\GeoAdmin\Models\District;
use Belivey\GeoAdmin\Models\DistrictUnion;
use Belivey\GeoAdmin\Models\SubDistrict;
use Belivey\GeoAdmin\Helpers\GeoHelpers;
use Belivey\GeoAdmin\Helpers\ParseHelpers;

use Shapefile\Shapefile;
use Shapefile\ShapefileException;
use Shapefile\ShapefileReader;

class L8Seeder extends Seeder
{
  public function run()
  {
    $handle = opendir('vendor/belivey/geo-admin/database/seeds/data/l8/');

    while ($entry = readdir($handle)) {
        preg_match('/(.+).shp$/', $entry, $matches);
        if ($matches) { 
            try {
                // Open Shapefile
                $Shapefile = new ShapefileReader('vendor/belivey/geo-admin/database/seeds/data/l8/'.$matches[1].'.shp');

                // Read all records
                $tot = $Shapefile->getTotRecords();

                for ($i = 1; $i <= $tot; ++$i) {
                    try {
                        // Manually set current record. Don't forget this!
                        $Shapefile->setCurrentRecord($i);
                        // Fetch a Geometry
                        $Geometry = $Shapefile->fetchRecord();

                        list($geom, $meta) = GeoHelpers::splitGeometry($Geometry);
                        if (!$geom) continue;

                        $region_id = Region::getByAny(
                            ParseHelpers::regionAddr($meta),
                            $geom
                        )?->id;

                        $district_id = District::getByAny(
                            ParseHelpers::districtAddr($meta),
                            $geom
                        )?->id;

                        $district_union_id = DistrictUnion::getByGeometry($geom)?->id;

                        $type = ParseHelpers::getSubDistrictType(ParseHelpers::status($meta));

                        SubDistrict::updateOrCreate([
                            'title' => $meta['NAME'],
                            'region_id' => $region_id
                        ],[
                            'type' => $type,
                            'district_id' => $district_id,
                            'district_union_id' => $district_union_id,
                            'osm_id' => $meta['OSM_ID'],
                            'boundary' => \DB::raw($geom)
                        ]);
                        
                        
                    } catch (ShapefileException $e) {
                        // Handle some specific errors types or fallback to default
                        switch ($e->getErrorType()) {
                            // We're crazy and we don't care about those invalid geometries... Let's skip them!
                            case Shapefile::ERR_GEOM_RING_AREA_TOO_SMALL:
                            case Shapefile::ERR_GEOM_RING_NOT_ENOUGH_VERTICES:
                                // The following "continue" statement is just syntactic sugar in this case
                                continue 2;
                                break;
                                
                            // Let's handle this case differently... :)
                            case Shapefile::ERR_GEOM_POLYGON_WRONG_ORIENTATION:
                                exit("Do you want the Earth to change its rotation direction?!?");
                                break;
                                
                            // A fallback is always a nice idea
                            default:
                                exit(
                                    "Error Type: "  . $e->getErrorType()
                                    . "\nMessage: " . $e->getMessage()
                                    . "\nDetails: " . $e->getDetails()
                                );
                                break;
                        }
                    }
                }
            } catch (ShapefileException $e) {
                /*
                    Something went wrong during Shapefile opening!
                */
            }
        }
    }
  }
}