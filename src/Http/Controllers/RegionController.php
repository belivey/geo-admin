<?php
namespace Belivey\GeoAdmin\Http\Controllers;

use Belivey\GeoAdmin\Models\Region;

class Controller extends Controller
{
    public function index () {
        return Region::all();   
    }
}