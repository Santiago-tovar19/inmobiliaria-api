<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BrokersController extends Controller
{
    public function getAll(Request $request)
    {
        $brokers = \App\Models\Broker::all();
        return ApiResponseController::response('Success', 200, $brokers);
    }
}
