<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;

class CountriesController extends Controller
{
    public function index()
    {
        return ApiResponseController::response('Exito', 200, Country::all());
    }
}
