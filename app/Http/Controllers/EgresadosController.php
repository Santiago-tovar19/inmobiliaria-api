<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\StudentCollegeDegree;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class EgresadosController extends Controller
{
    public function getEgresadosStudents(Request $request)
    {
        $perPage = $request->input('perPage') ? $request->input('perPage') : 10;

        $collegeDegreesPivot = StudentCollegeDegree::with('student.moodle', 'collegeDegree')
        ->whereHas('student', function($query) use ($request){
            $query->where('first_name', 'like', '%'.$request->input('searchString').'%')
                  ->orWhere('last_name', 'like', '%'.$request->input('searchString').'%')
                  ->orWhere('email', 'like', '%'.$request->input('searchString').'%')
                  ->orWhere('phone', 'like', '%'.$request->input('searchString').'%')
                  ->orWhere('address', 'like', '%'.$request->input('searchString').'%')
                  ->orWhere('document', 'like', '%'.$request->input('searchString').'%');
        })
        ->when(($request->input('college_degree_id')!=''), function($q2) use ($request){
            return $q2->where('college_degree_id', $request->input('college_degree_id'));
        })->where('status_id', 2)
        ->paginate($perPage);

        return ApiResponseController::response('Consulta Exitosa', 200, $collegeDegreesPivot);
    }

    public function getAllEgresados(Request $request, $fromController=false)
    {

		$tudents = Student::with('moodle', 'collegeDegreesPivot', 'collegeDegrees')->when(($request->input('searchString')!=''), function($q) use ($request){
			// Get all columns
            $columns = Schema::getColumnListing('students');
            foreach($columns as $column){
                $q->orWhere($column, 'LIKE', '%'.$request->input('searchString').'%');
            }
		})
        // collegeDegrees
        ->whereHas('collegeDegreesPivot', function($q) use ($request){
            return $q->where('status_id', 2)
            ->when(($request->input('college_degree_id')!=''), function($q2) use ($request){
                return $q2->where('college_degree_id', $request->input('college_degree_id'));
            });
        })
        ->get();

        // Change first_name by Nombre
        $newStudents = $tudents->map(function($item) use ($request){
            // New object
            $newItem = new \stdClass();
            $newItem->{'Nombre'} = $item->first_name;
            $newItem->{'Apellido'} = $item->last_name;
            $newItem->{'Cedula'} = $item->document;
            $newItem->{'Email'} = $item->email;
            $newItem->{'Telefono'} = $item->phone;
            $newItem->{'FechaNacimiento'} = $item->born_at;
            $newItem->{'Carrera'} = $item->collegeDegrees->where('pivot.status_id', 2)->when(($request->input('college_degree_id')!=''), function($q) use($request){
                return $q->where('id', $request->input('college_degree_id'));
            })->first()->name;

            return $newItem;
        });

        if($fromController){
            return $newStudents;
        }
        return ApiResponseController::response('Consulta Exitosa', 200, $newStudents);
    }

    public function exportPDFEgresados(Request $request)
    {

        $data = [
            'students' => self::getAllEgresados($request, true),
            'type' => 'Egresados',
        ];
        return $pdf = \PDF::loadView('exports.students', $data)->stream('archivo.pdf');
    }

    public function show($id)
    {
        if(!$user = Student::with('country', 'collegeDegrees', 'courses.asignature', 'scores')->find($id)){
            return ApiResponseController::response('', 204);
        }

        $c = $user->collegeDegrees->where('pivot.status_id', 2)->values()->all();
        $user->unsetRelation('collegeDegrees');
        $user->{'college_degrees'} = $c;


        return ApiResponseController::response('Consulta exitosa', 200, $user);
    }
}
