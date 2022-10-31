<?php

namespace App\Http\Controllers;

use App\Models\AcademicClass;
use App\Models\Asignature;
use App\Models\CollegeDegree;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// use PDF as PDFitop;

class TestController extends Controller
{

    public function index(Request $request)
    {

        return view('exports.students');
        $data = [
            'titulo' => 'Styde.net'
        ];

        return $pdf = \PDF::loadView('certificates.esp-laboral')->stream('archivo.pdf');

        return $pdf->download('archivo.pdf');
    }


    public function uploadScoreFile(Request $request)
    {
        // max execution time
        ini_set('max_execution_time', -1);
        ini_set('memory_limit', -1);

        // return file name
        $file = $request->file('file');

        // open file
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);

        // return data
        $data              = $spreadsheet->getSheet(0)->toArray();

        $document                = array_search('Documento', $data[0]);
        $college_degree          = array_search('Maestria', $data[0]);
        $code                    = array_search('Codigo', $data[0]);
        $campus                  = array_search('Sede', $data[0]);
        $asignature              = array_search('Modulo', $data[0]);
        $workload                = array_search('Carga horaria', $data[0]);
        $teacher                 = array_search('Docente titular', $data[0]);
        $process_score           = array_search('Puntacion de proceso', $data[0]);
        $points_earned           = array_search('Puntos obtenidos	', $data[0]);
        $exam_date               = array_search('Fecha Examen', $data[0]);
        $period                  = array_search('Periodo', $data[0]);
        $total_points            = array_search('Total puntos', $data[0]);
        $correct_points          = array_search('Puntos correctos', $data[0]);
        $total_score             = array_search('Puntaje total', $data[0]);
        $final_note              = array_search('Nota final', $data[0]);
        $letters                 = array_search('Letras', $data[0]);
        $final_evaluation_report = array_search('Acta de evlacion final', $data[0]);
        $year                    = array_search('Ano', $data[0]);

        unset($data[0]);

        $scores = [];
        $debug = [];
        foreach($data as $student){

                $scores[] = [
                    'document'                => $student[$document],
                    'college_degree'          => $student[$college_degree],
                    'code'                    => $student[$code],
                    'campus'                  => $student[$campus],
                    'asignature'              => $student[$asignature],
                    'workload'                => $student[$workload],
                    'teacher'                 => $student[$teacher],
                    'process_score'           => $student[$process_score],
                    'points_earned'           => $student[$points_earned],
                    'exam_date'               => $student[$exam_date],
                    'period'                  => $student[$period],
                    'total_points'            => $student[$total_points],
                    'correct_points'          => $student[$correct_points],
                    'total_score'             => $student[$total_score],
                    'final_note'              => $student[$final_note],
                    'letters'                 => $student[$letters],
                    'final_evaluation_report' => $student[$final_evaluation_report],
                    'year'                    => $student[$year]
                ];

            // return $scores[count($scores) - 1];
        }

        foreach (array_chunk($scores,1000) as $t)
        {
            DB::table('scores_debug')->insert($t);
        }

        return "Exito";

    }



    public function uploadStudentFile(Request $request)
    {

        $colllegeDegreeOrigin = CollegeDegree::all()->pluck('id', 'name')->toArray();
        $badCollegeDegree = DB::table('bad_college_degrees')->get()->pluck('college_degree_id', 'name')->toArray();
        $collegeDegrees = array_merge($colllegeDegreeOrigin, $badCollegeDegree);

        $StudentsDB = DB::table('students')->get()->pluck('id', 'document')->toArray();

        $rel = DB::table('student_college_degrees')->selectRaw('id, CONCAT(student_id, "-", college_degree_id) as rel')->get()->pluck('id', 'rel')->toArray();
        // return $rel;

        // max execution time
        ini_set('max_execution_time', -1);

        // return file name
        $file = $request->file('file');

        // open file
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);

        // return data
        $data              = $spreadsheet->getSheet(0)->toArray();

        $nombres           = array_search('Nombres', $data[0]);
        $apellidos         = array_search('Apellidos', $data[0]);
        $sexo              = array_search('Sexo', $data[0]);
        $documento         = array_search('Documento', $data[0]);
        $telefono          = array_search('Telefono', $data[0]);
        $email             = array_search('Email', $data[0]);
        $nacionalidad      = array_search('Nacionalidad', $data[0]);
        $programa          = array_search('Programa', $data[0]);
        $cohorte           = array_search('Cohorte', $data[0]);
        $sede              = array_search('Sede', $data[0]);
        $filial            = array_search('Filial', $data[0]);
        $dechaInscripcion  = array_search('Fecha Inscripción', $data[0]);
        $numeroInscripcion = array_search('Numero Inscripción', $data[0]);
        $status            = array_search('Status', $data[0]);
        $promocion         = array_search('Promoción', $data[0]);
        $estadoTitulo      = array_search('Estado titulo', $data[0]);
        $activo            = array_search('Activo', $data[0]);
        $resolucion        = array_search('Resolucion', $data[0]);
        $fechaResolucion   = array_search('Fecha resolucion', $data[0]);



        unset($data[0]);

        $students = [];
        $debug = [];
        foreach($data as $student){


        }

        return $debug;
    }


    public function importAttendances(){
        ini_set('max_execution_time', -1);
      $asistencias =  DB::connection('old')->table('asistencia')->get();

      $usuarios = DB::connection('old')->table('usuario')->get()->pluck('usuario_documento', 'usuario_id')->toArray();
      $students = Student::all()->pluck('id', 'document')->toArray();
      $academicClasses = AcademicClass::all()->pluck('id', 'old_id')->toArray();
    //   return $students;

      $newAistencias = [];
      $debug = [];
      foreach ($asistencias as $asistencia) {

        if(!isset($usuarios[$asistencia->alumno_id])){
            // $debug[] = '';
            continue;
        }

        $document = trim($usuarios[$asistencia->alumno_id]);

        if(!isset($students[$document])){
            // $debug[] = '';
            continue;
        }

        $student_id = $students[$document];

        if(!isset($academicClasses[$asistencia->clase_id])){
            // $debug[] = $asistencia->clase_id;
            continue;
        }

        $academic_class_id = $academicClasses[$asistencia->clase_id];

        $newAistencias[] = [
            'academic_class_id' => $academic_class_id,
            'student_id' => $student_id,
            'status' => true,
            'attendance_type_id' => $asistencia->tipoasistencia_id,
            'old_id' => $asistencia->asistencia_id,
        ];
      }

      foreach (array_chunk($newAistencias,1000) as $t)
        {
            DB::table('attendances')->insert($t);
        }

        return "Exito";
    }
    public function setStudentCourseRelation(){
        // Maximum execution time of 30 seconds exceeded
        ini_set('max_execution_time', -1);

        $newRels=[];
        $rels = DB::connection('old')->table('cursousuario')->get();
        foreach($rels as $rel){
            if(!$alumno = DB::connection('old')->table('usuario')->where('usuario_id', $rel->alumno_id)->first()){
                continue;
            }

            if(!$student = Student::where('document', $alumno->usuario_documento)->first()){
                continue;
            }

            $courses = DB::table('courses')->where('old_id', $rel->curso_id)->get();

            foreach ($courses as $course) {
                $newRels[] = [
                    'student_id' => $student->id,
                    'course_id' => $course->id,
                    'status_id' => $rel->estatus_id,
                    'old_id' => $rel->cursousuario_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        foreach (array_chunk($newRels,1000) as $t)
        {
            DB::table('course_student')->insert($t);
        }

        return "Exito";
    }

    public function importClasses()
    {
        $clases=DB::connection('old')->table('clase')->get();

        $newCases = [];
        $debug = [];
        foreach ($clases as $clase) {
            if(!$course = DB::table('courses')->where('old_id', $clase->curso_id)->where('old_module_id', $clase->modulo_id)->first()){
                $debug[] =  $clase->curso_id;
                continue;
            }
            $newCases[] =[
                'datetime' => $clase->clase_fecha,
                'status' => $clase->estatus_id,
                'class_number' => null,
                'course_id' => $course->id,
                'old_id' => $clase->clase_id,
            ];
        }

        foreach (array_chunk($newCases,1000) as $t)
        {
            DB::table('academic_classes')->insert($t);
        }

        return "Exito";


    }

    public function setCourseAsignatureRelation(){

        $modulos = DB::connection('old')->table('modulo')->get();

        $newModulos = [];
        $debug = [];
        foreach($modulos as $modulo){
            $nombre = $modulo->modulo_nombre;
            if(str_contains($modulo->modulo_nombre, ' - ')){
                $nombre = self::GetLongestStr(explode(' - ', $modulo->modulo_nombre));
            }

            if(str_contains($modulo->modulo_nombre, '-')){
                $nombre = self::GetLongestStr(explode('-', $modulo->modulo_nombre));
            }

            if(str_contains($modulo->modulo_nombre, '_')){
                $nombre = self::GetLongestStr(explode('_', $modulo->modulo_nombre));
            }

            // Change á, é, í, ó, ú to a, e, i, o, u
            $nombre = str_replace(['á', 'é', 'í', 'ó', 'ú'], ['a', 'e', 'i', 'o', 'u'], $nombre);

            // remove all non-alphanumeric characters except spaces
            $nombre = preg_replace('/[^A-Za-z0-9 ]/', '', $nombre);

            // All lowercase
            $nombre = strtolower($nombre);

            $nombre=trim($nombre);

            // Check if previous name is in the new array

            $newModulos[$modulo->modulo_nombre] = $nombre;
        }

        // return $newModulos;





        $relCursosModulos = DB::connection('old')->table('cursomodulo')
            ->whereIn('curso_id', DB::table('courses')->pluck('old_id')->toArray())
            ->get();

        $badAsignatures = DB::table('bad_asignatures')->get()->pluck('asignature_id', 'name')->toArray();

        $newRelations = [];
        $debug = [];
        foreach ($relCursosModulos as $rel) {
            $asignature = [];
            $moduloName = DB::connection('old')->table('modulo')->where('modulo_id', $rel->modulo_id)->first()->modulo_nombre;


            $asignature = Asignature::where('name', $moduloName)->first();

            if(!$asignature){
                if(isset($badAsignatures[$newModulos[$moduloName]])){
                $asignature = Asignature::where('id', $badAsignatures[$newModulos[$moduloName]])->first();
                }
            }

            if(!$asignature){
                $asignature = DB::select("SELECT * FROM bad_asignatures WHERE NAME = '"."$newModulos[$moduloName]"."'");
                $asignature = count($asignature) > 0 ? Asignature::where('id', $asignature[0]->asignature_id)->first() : null;
            }


            if(!$asignature){
                // $debug[] = "SELECT * FROM bad_asignatures WHERE NAME = '"."$newModulos[$moduloName]"."'";
                continue;
            }

            DB::table('courses')->where('old_id', $rel->curso_id)->where('old_module_id', $rel->modulo_id)->update([
                'asignature_id' => $asignature->id
            ]);
        }

        return "Exito";

    }

    public function importCourses(Request $request)
    {

        $materias = DB::connection('old')->table('materia')->get();

        $newMaterias = [];
        foreach($materias as $materia){
            $nombre = '';
            if(str_contains($materia->materia_nombre, ' - ')){
                $nombre = self::GetLongestStr(explode(' - ', $materia->materia_nombre));
            }

            $nombre=trim($nombre);

            $newMaterias[$materia->materia_nombre] = $nombre;
        }
        // return $newMaterias;



        $newCourses = [];
        $cursoModulo = DB::connection('old')->table('cursomodulo')->get();

        $mat = [];
        foreach($cursoModulo as $cursoModulo){

            $course = DB::connection('old')->table('curso')->where('curso_id', $cursoModulo->curso_id)->first();

            if(!$course->materia_id){
                continue;
            }

            if(!$materia = DB::connection('old')->table('materia')->where('materia_id', $course->materia_id)->first()){
                $mat[]=$course->materia_id;
                continue;
            }

            if(!$collegeDegree = DB::table('bad_college_degrees')->where('name', 'LIKE', '%'.$newMaterias[$materia->materia_nombre]. '%')->first()){
                continue;
            }

            $mat[] = $course->curso_id;
            $newCourses[] = [
                "hours"             => $course->curso_horas,
                "class_number"      => $course->curso_clases,
                "modality"          => $course->tipocurso_id,
                "status"            => $course->estatus_id,
                "start_date"        => $course->curso_fechaini,
                "end_date"          => $course->curso_fechaini,
                "college_degree_id" => $collegeDegree->college_degree_id,
                "old_id"            => $course->curso_id,
                "old_module_id"     => $cursoModulo->modulo_id,
                // "asignature_id" => $asignature->id,
            ];
        }
        // return count($newCourses);
        DB::table('courses')->insert($newCourses);
        return "Exito";
    }

    public function GetLongestStr($arr)
    {
        $longest = '';
        foreach($arr as $item){
            $longest = strlen($longest)<strlen($item) ? $item : $longest;
        }
        return $longest;
    }

    public function leyesLinks()
    {
        // maximum execution time of 30 seconds
        set_time_limit(-1);

        $leyes = DB::table('ley')->get();
        $debug = [];
        foreach ($leyes as $ley) {

            if(strlen($ley->ley_nombre) < 3){
                continue;
            }
            $leyNombre = $ley->ley_nombre;
            foreach ($leyes as $leyRed) {
                if($leyRed->ley_nombre == $leyNombre || !$leyRed->ley_descrip || strlen($leyRed->ley_nombre) < 3 || strlen($leyRed->ley_descrip) < 3){
                    continue;
                }

                // Find leyNombre in leyRed->ley_descrip
                $pos = strpos($leyRed->ley_descrip, $leyNombre);
                if ($pos !== false) {
                    $debug[] = $leyNombre . ', ' . $leyRed->ley_nombre;
                }

            }
        }
        return $debug;
    }

}
