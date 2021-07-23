<?php
namespace Belivey\GeoAdmin\Database\Seeds;

use Illuminate\Database\Seeder;
use Belivey\LaravelGeoAdmin\Models\Region;

class RegionSeeder extends Seeder
{
  public function run()
  {
    Region::create(['title' => 'test']);
  }
}