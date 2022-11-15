<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\PropertyView;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function getAdmindMasterData(Request $request)
    {


        $masterAdminCount = User::where('role_id', 1)->count();
        $agentCount = User::where('role_id', 3)->count();
        $costumerCount = User::where('role_id', 4)->count();
        $propertyCount = Property::where('created_by', $request->user()->id)->count();

        $todayPropertiesViewsCount = PropertyView::whereDate('created_at', today())
        ->whereHas('property', function($q){
            $q->where('created_by', auth()->user()->id);
        })
        ->count();


        $moreViewedProperties= PropertyView::select('property_id', \DB::raw('count(*) as total'))
        ->with('property.images')
        ->groupBy('property_id')
        ->orderBy('total', 'desc')
        ->limit(5)
        ->get();



        return ApiResponseController::response('Exito', 200, [
            'masterAdminCount'          => $masterAdminCount,
            'agentCount'                => $agentCount,
            'costumerCount'             => $costumerCount,
            'todayPropertiesViewsCount' => $todayPropertiesViewsCount,
            'propertyCount'             => $propertyCount,
            'moreViewedProperties'      => $moreViewedProperties
        ]);
    }
}
