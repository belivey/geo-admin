<?php
namespace Belivey\GeoAdmin\Database\Seeds;

use Illuminate\Database\Seeder;
use Belivey\GeoAdmin\Models\Region;

use Shapefile\Shapefile;
use Shapefile\ShapefileException;
use Shapefile\ShapefileReader;

class L2L3Seeder extends Seeder
{
  public function run()
  {
    $handle = opendir('vendor/belivey/geo-admin/database/seeds/data/l2/');
    $crops = [];

    // dd($handle);
    while ($entry = readdir($handle)) {
        preg_match('/(.+).shp$/', $entry, $matches);
        if ($matches) { 
            try {
                // Open Shapefile
                $Shapefile = new ShapefileReader('vendor/belivey/geo-admin/database/seeds/data/l2/'.$matches[1].'.shp');
                dd($Shapefile);
                // Read all records
                $tot = $Shapefile->getTotRecords();
                dd ($tot);
                for ($i = 1; $i <= $tot; ++$i) {
                    try {
                        // Manually set current record. Don't forget this!
                        $Shapefile->setCurrentRecord($i);
                        // Fetch a Geometry
                        $Geometry = $Shapefile->fetchRecord();
                        // Skip deleted records
                        if ($Geometry->isDeleted()) {
                            continue;
                        }

                        $meta = $Geometry->getDataArray();
                        dd($meta);
                        if ($meta['CROP']=="Пар") print_r($meta);
                        $crops[$meta['CROP']] = null;
                        $org = Organization::find($org_id);
                        $district = District::find($district_id);
                        $coordinates = json_decode($Geometry->getGeoJSON())->coordinates;
                        dd ($Geometry->getGeoJSON());

                        
                        if (json_decode($Geometry->getGeoJSON())->type == 'Polygon'){
                            continue;
                            $geom = 'ST_PolygonFromText(\'POLYGON (';

                            foreach ($coordinates as $sub_index => $sub_poly) {
                                $geom .= $sub_index > 0 ? ',(' : '(';

                                foreach ($sub_poly as $index => $point) {
                                    $geom .= $point[1].' '.$point[0].',';
                                }
                                $geom = substr($geom, 0, -1);
                                $geom .= ')';
                            }
                            $geom.=')\', 4326)';
                        } elseif (json_decode($Geometry->getGeoJSON())->type == 'MultiPolygon') {
                            $geom = 'ST_MultiPolygonFromText(\'MULTIPOLYGON (';
                            foreach ($coordinates as $poly_index => $poly) {
                                $geom .= $poly_index > 0 ? ',(' : '(';
                                foreach ($poly as $sub_index => $sub_poly) {
                                    $geom .= $sub_index > 0 ? ',(' : '(';

                                    foreach ($sub_poly as $index => $point) {
                                        $geom .= $point[1].' '.$point[0].',';
                                    }
                                    $geom = substr($geom, 0, -1);
                                    $geom .= ')';
                                }
                                $geom.=')';
                            }
                            $geom.=')\', 4326)';;
                        }
                        $field = $org->fields()->create([
                            'title' => $meta['F_NAME'],
                            'geometry' => \DB::raw($geom),
                            'area' => \DB::raw('ST_AREA('.$geom.')/10000'),
                            'region_id' => $district->region_id,
                            'district_id' => $district->id,
                            'category' => 'arable',
                        ]);
                        Field::find($field->id)->setForecastPoint();

                        $specie_id = $species[$meta['CROP']];
                        // dd($specie_id);
                        if ($specie_id){
                            $field->sowCycles()->create([
                                'specie_id' => $specie_id,
                                'geometry' => \DB::raw($geom),
                                'area' => \DB::raw('ST_AREA('.$geom.')/10000'),
                                'seed_date' => $meta['SOW_DATE'] ? [$meta['SOW_DATE']] : ['2021-04-15', '2021-05-30'],
                            ]);
                        }
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
    dd ($crops);
  }
}