<?php

namespace App\Http\Controllers;

use App\Models\broker;
use Illuminate\Http\Request;

class BrokersController extends Controller
{
    public function getAll(Request $request)
    {
        $brokers = broker::all();
        return ApiResponseController::response('Success', 200, $brokers);
    }
}
