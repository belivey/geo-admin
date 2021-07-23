<?php
namespace Belivey\GeoAdmin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Belivey\GeoAdmin\Models\Region;

class RegionController extends Controller
{
    public function index (Request $request) {
        return Region::all();   
    }
}