<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\appointment;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class AppointmentController extends Controller
{

    public function index(Request $request){
        $usuario = JWTAuth::parseToken()->authenticate();
        $perPage = $request->input('perPage') ? $request->input('perPage') : 10;

        if($usuario->role_id === 1){
            $termino = $request->termino;

            $appointments = Appointment::when($termino, function($q) use ($termino) {
                $q->where('email', 'like', '%' . $termino . '%')
                ->orWhere('phone', 'like', '%' . $termino . '%')
                ->orWhere('message', 'like', '%' . $termino . '%');
            })
            ->paginate($perPage);

            return ApiResponseController::response('Consulta exitosa', 200, $appointments);
        }

        if($usuario->role_id === 2){
            $broker_id = $usuario->broker_id;
            $properties_ids = Property::Where('broker_id', $broker_id)->get()->pluck('id')->toArray();
            $appointments = appointment::WhereHas('property', function($query) use ($properties_ids){
                $query->whereIn('id', $properties_ids);
            })
            ->when($request->termino, function($q) use ($request) {
             $termino = $request->termino;
             $q->where('email', 'like', '%' . $termino . '%')
            ->orWhere('phone', 'like', '%' . $termino . '%')
             ->orWhere('message', 'like', '%' . $termino . '%');
           })
            ->paginate($perPage);
            return ApiResponseController::response('Consulta exitosa', 200, $appointments);
        }
        if($usuario->role_id === 3){
        $agente_id = $usuario->id;

        $properties_ids = Property::where('created_by', $agente_id)->pluck('id')->toArray();

        $appointments = Appointment::whereHas('property', function($query) use ($properties_ids) {
            $query->whereIn('id', $properties_ids);
        })
        ->when($request->termino, function($q) use ($request) {
            $termino = $request->termino;
            $q->where('email', 'like', '%' . $termino . '%')
            ->orWhere('phone', 'like', '%' . $termino . '%')
            ->orWhere('message', 'like', '%' . $termino . '%');
        })
        ->paginate($perPage);

        return ApiResponseController::response('Consulta exitosa', 200, $appointments);

        }
    }

       public function getAllAppointments(Request $request){
        $usuario = JWTAuth::parseToken()->authenticate();
        $start = $request->start;
        $end = $request->end;

        if($usuario->role_id === 1){
            $appointments = appointment::when($start && $end, function ($query) use ($start, $end) {
            return $query->whereBetween('created_at', [$start, $end]);
        })
        ->get();
        return ApiResponseController::response('Consulta exitosa', 200, $appointments);
        }

        if($usuario->role_id === 2){
            $broker_id = $usuario->broker_id;
            $properties_ids = Property::Where('broker_id', $broker_id)->get()->pluck('id')->toArray();
            $appointments = appointment::WhereHas('property', function($query) use ($properties_ids){
                $query->whereIn('id', $properties_ids);
            })
            ->when($start && $end, function ($query) use ($start, $end) {
                return $query->whereBetween('created_at', [$start, $end]);
            })
            ->get();
            return ApiResponseController::response('Consulta exitosa', 200, $appointments);
        }

        if($usuario->role_id === 3){
            $agente_id = $usuario->id;

            $properties_ids = Property::where('created_by', $agente_id)->pluck('id')->toArray();

            $appointments = Appointment::whereHas('property', function($query) use ($properties_ids) {
                $query->whereIn('id', $properties_ids);
            })
            ->when($start && $end, function ($query) use ($start, $end) {
                return $query->whereBetween('created_at', [$start, $end]);
            })
            ->get();

            return ApiResponseController::response('Consulta exitosa', 200, $appointments);
        }

     }


     public function store(Request $request)
    {
        // Validación de datos
        $this->validate($request, [
            'property_id' => 'required',
            'email' => 'required|email',
            'phone' => 'required|min:6',
            'message' => '',
        ]);

        // Crear una nueva cita
        $appointment = new appointment;
        $appointment->property_id = $request->input('property_id');
        $appointment->email = $request->input('email');
        $appointment->phone = $request->input('phone');
        $appointment->message = $request->input('message');
        $today = now();
        $dayOfWeek = $today->dayOfWeek;
        $appointment->day_of_week = $dayOfWeek;

        // Guardar la cita en la base de datos
        $appointment->save();

        // Redireccionar a una página de éxito o hacer otra acción, por ejemplo:
        return ApiResponseController::response('Contacto enviado correctamente', 200);
    }
}
