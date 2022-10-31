<?php

namespace App\Http\Controllers;

use App\Models\CollegeDegree;
use App\Models\Student;
use App\Models\StudentCollegeDegree;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentsSharedController extends Controller
{
    public function getMissingAsignatures(Request $request, $collegeDegreeID, $studentID)
    {

        // Get user from token
        $user = Student::where('id', $studentID)->with('scores')->first();
        $scores=$user->scores;

        $scoresAVG = $user->scores->groupBy('asignature_id')->map(function($item, $key){
            return [
                'score_id' => $item->max('id'),
                'asignature_id' => $key,
                'total_score_avg' => $item->avg('total_score')
            ];
        });

         // Get college degrees with status_id = 2 in pivot table with asignatures
         $collegeDegree = CollegeDegree::with('asignatures')->where('id', $collegeDegreeID)->first();

        // Remove asignatures from college degrees where socore is gratter than 70
            $collegeDegree->{'missing_asignatures'} = $collegeDegree->asignatures->filter(function($asignature) use ($scoresAVG){
                $keep = true;
                $scoresAVG->map(function($score) use ($asignature, &$keep){
                    if($score['asignature_id'] == $asignature->id){
                        if($score['total_score_avg'] >= 70){
                            $keep = false;
                        }
                    }
                });
                return true;
            })->map(function($asignature) use ($scoresAVG, $scores){

                $asignature->{'score'} = NULL;
                $scoreAVG = $scoresAVG->filter(function($score) use ($asignature){
                    return $score['asignature_id'] == $asignature->id;
                })->first();

                if($scoreAVG){
                    $asignature->{'score'} = $scores->filter(function($score) use ($scoreAVG){
                        return $score['id'] == $scoreAVG['score_id'];
                    })->first();
                }
                return $asignature;
            })->values()->all();

            // Unset asignatures from college degree
            unset($collegeDegree->asignatures);

            return ApiResponseController::response('Consulta Exitosa', 200, $collegeDegree);
    }

    public function exportScoresPDF(Request $request, $studentID)
    {
        $student = Student::where('id', $studentID)->with('scores.asignature')->first();
        $scores = $student->scores;

        // return $scores;

        // $scores;

        // return view('certificates.esp-laboral', compact('student', 'scores'));

        return $pdf = \PDF::loadView('certificates.esp-laboral', ['student' => $student, 'scores' => $scores])->stream('archivo.pdf');
    }

    public function uploadStudentsGraduatesFile(Request $request)
    {
         // max execution time
         ini_set('max_execution_time', -1);

         // return file name
         $file = $request->file('file');

         // open file
         $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);

         // return data
         $data              = $spreadsheet->getSheet(0)->toArray();


         $apellidos       = array_search('Apellidos', $data[0]);
         $nombres         = array_search('Nombres', $data[0]);
         $documento       = array_search('Documento', $data[0]);
         $resolucion      = array_search('Resolucion', $data[0]);
         $fechaResolucion = array_search('Fecha resolucion', $data[0]);
         $telefono        = array_search('Telefono', $data[0]);
         $email           = array_search('Email', $data[0]);
         $sede            = array_search('Sede', $data[0]);
         $titulo          = array_search('Titulo', $data[0]);
         $carrera         = array_search('Carrera', $data[0]);
         $promocion       = array_search('Promocion', $data[0]);
         $estadoTitulo    = array_search('Estado titulo', $data[0]);
         $tipo    = array_search('Tipo', $data[0]);

         unset($data[0]);

         $studentsDB = Student::all()->pluck('id', 'document')->toArray();
         $collegeDegreesDB = CollegeDegree::all()->pluck('id', 'name')->toArray();
         $relDB = StudentCollegeDegree::selectRaw('CONCAT(college_degree_id, "-", student_id) as rel, id')->get()->pluck('id', 'rel')->toArray();
        //  return $relDB;
         $collegeDegreesDB = [
            ...$collegeDegreesDB,
            "Impuestos y Procedimientos (ESIP)" => 9,
            "Auditoria (ESA)" => 4,
            "Auditoria Impositiva (EIA)" => 5,
            "Impuestos y Asesoría Impositiva (ESIAI)" => 8,
            "Impuestos y Auditoria (MAIA)" => 14,
            "Derecho y Practica Laboral (ESDPL)" => 7,
            "Tributación y Asesoría Impositiva (MAETAI)" => 15,
            "Internacional en Derecho Comercial y Asesoramiento Impositivo (MAEDCIAI)" => 13,
            "Auditoria Impositiva (MIA)" => 11,
            "Contabilidad Estrategica y Tributacion (MAECET)" => 12,
            'Derecho y Practica Laboral  (ESDPL)' => 7,
            'Auditoría Impositiva (MIA)' => 11,
            'Impuestos y Auditoría (MAIA)' => 14
         ];

         $debug = []; $rel = []; $students = []; $collegeDegrees = [];
         $tipos=['GRADUADO' => 3, 'EGRESADO' => 2];
         foreach ($data as $student) {
            // $do = preg_replace('/[^0-9]/', '', $student[$documento]);
            // if(!isset($studentsDB[$do])){
                // $debug[] =  $student[$documento];
                $student[$documento] = preg_replace('/[^0-9]/', '', $student[$documento]);
                // $students[] = [
                //     'document' => $student[$documento],
                //     'first_name' => $student[$nombres],
                //     'last_name' => $student[$apellidos],
                //     'phone' => $student[$telefono],
                //     'email' => $student[$email],
                //     'created_at' => now(),
                //     'updated_at' => now(),
                // ];
            // }

            // if(!isset($collegeDegreesDB[$student[$carrera]])){
                // $debug[] =  $student[$carrera];
            // }

            $r = $collegeDegreesDB[$student[$carrera]] . '-' . $studentsDB[$student[$documento]];
            if(!isset($relDB[$r])){
                $rel[] = [
                    'student_id' => $studentsDB[$student[$documento]],
                    'college_degree_id' => $collegeDegreesDB[$student[$carrera]],
                    'status_id' => $tipos[$student[$tipo]],
                    'resolution' => $student[$resolucion],
                    'resolution_date' => $student[$fechaResolucion],
                    'promotion_year' => $student[$promocion],
                    'title_status' => $student[$estadoTitulo],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            // $rel[] = [
            //     'student_id' => $studentsDB[$student[$documento]],
            //     'college_degree_id' => $collegeDegreesDB[$student[$carrera]],
            //     'status_id' => $tipos[$student[$tipo]],
            //     'resolution' => $student[$resolucion],
            //     'resolution_date' => $student[$fechaResolucion],
            //     'promotion_year' => $student[$promocion],
            //     'title_status' => $student[$estadoTitulo],
            //     'created_at' => now(),
            //     'updated_at' => now(),
            // ];


         }
        //  $debug = array_unique($debug);
        //  return $rel;

            $chunks = array_chunk($rel, 1000);

            foreach ($chunks as $chunk) {
                DB::table('student_college_degrees')->insert($chunk);
            }

        return 'Exito';
    }

    public function removeCollegeDegree(Request $request, $studentID, $collegeDegreeID)
    {
        $student = Student::find($studentID);
        $collegeDegree = CollegeDegree::find($collegeDegreeID);
        StudentCollegeDegree::where('student_id', $studentID)->where('college_degree_id', $collegeDegreeID)->update([
            'deleted_at' => now()
        ]);
        return ApiResponseController::response('Se ha removido la carrera ' . $collegeDegree->name . ' del estudiante ' . $student->first_name . ' ' . $student->last_name, 200);
    }

    public function showWithGraduateAndEgresadoCollegeDegree($id)
    {

        $user = Student::with('country', 'collegeDegrees', 'courses.asignature', 'scores', 'collegeDegreesPivot')
        ->whereHas('collegeDegreesPivot', function($q){
            return $q->where('status_id', 2);
        })->find($id);

        if(!$user){
            return ApiResponseController::response('', 204);
        }


        return ApiResponseController::response('Consulta exitosa', 200, $user);
    }
}
