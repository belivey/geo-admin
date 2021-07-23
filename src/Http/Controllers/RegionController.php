<?php
namespace Belivey\GeoAdmin\Http\Controllers;

use Belivey\GeoAdmin\Models\Region;

class RegionController extends Controller
{
    public function index () {
        return Region::all();   
    }
}