<?php

namespace App\Http\Controllers;

use App\Models\CollegeDegree;
use App\Models\CollegeDegreeStatus;
use App\Models\Country;
use App\Models\ScoresDebug;
use App\Models\Student;
use App\Models\StudentCollegeDegree;
use App\Models\TitleStatus;
use App\Models\User;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Tymon\JWTAuth\Facades\JWTAuth;

class StudentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
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
        })->where('status_id', 1)
        ->paginate($perPage);

        return ApiResponseController::response('Consulta Exitosa', 200, $collegeDegreesPivot);
    }

    /**
     * Show the form for creating a new resource.v
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $documentFrontFileName        = null;
        $documentBackFileName        = null;
        $bornStudyCertificateFileName = null;

        // Check if document_file is a file
        if($request->hasFile('document_front_file')){
            $file = $request->file('document_front_file');
            $documentFrontFileName = time().$file->getClientOriginalName();
            $file->move(public_path().'/students-documents/', $documentFrontFileName);
        }

        // Check if born_file is a file
        if($request->hasFile('document_back_file')){
            $file = $request->file('document_back_file');
            $documentBackFileName = time().$file->getClientOriginalName();
            $file->move(public_path().'/students-documents/', $documentBackFileName);
        }

        // Check if born_file is a file
        if($request->hasFile('study_certificate_file')){
            $file = $request->file('study_certificate_file');
            $bornStudyCertificateFileName = time().$file->getClientOriginalName();
            $file->move(public_path().'/students-documents/', $bornStudyCertificateFileName);
        }


        $student = new Student;
	    $student->contract_number = $request->contract_number;
        $student->first_name = $request->first_name;
        $student->last_name = $request->last_name;
        $student->email = $request->email;
        $student->document = $request->document;
        $student->college_degree_id = $request->college_degree_id;
        $student->address = $request->address;
        $student->phone = $request->phone;
        $student->country_id = $request->country_id;
        $student->sex = $request->sex;
        $student->born_at = $request->born_at;
        $student->document_front_file = $documentFrontFileName;
        $student->document_back_file = $documentBackFileName;
        $student->study_certificate_file = $bornStudyCertificateFileName;
        $student->save();

	    return ApiResponseController::response('Alumno creada exitosamente', 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(!$user = Student::with('country', 'collegeDegrees', 'courses.asignature', 'scores')->find($id)){
            return ApiResponseController::response('', 204);
        }

        // Group scores by asignature with total_score average (total_score is string, so we need to convert it to int)
        $scores = $user->scores->groupBy('asignature_id')->map(function($item, $key){
            return [
                'asignature_id' => $key,
                'total_score' => $item->avg('total_score'),
            ];
        });

        // // Get college degrees with status_id = 2 in pivot table with asignatures
        // $collegeDegrees = $user->collegeDegrees->where('pivot.status_id', 2)->map(function($item){
        //     $item->asignatures;
        //     return $item;
        // });

        // // Remove asignatures from college degrees where socore is gratter than 70
        // $collegeDegrees = $collegeDegrees->map(function($item) use ($scores){
        //     $item->{'missing_asignatures'} = $item->asignatures->filter(function($asignature) use ($scores){
        //         $keep = true;
        //         $scores->map(function($score) use ($asignature, &$keep){
        //             if($score['asignature_id'] == $asignature->id){
        //                 if($score['total_score'] >= 70){
        //                     $keep = false;
        //                 }
        //             }
        //         });
        //         return $keep;
        //     })->map(function($asignature) use ($scores){
        //         $asignature->{'status'} = $scores->has($asignature->id) ? 'Reprobado' : 'Sin Calificar';
        //         $asignature->{'total_score'} = $scores->has($asignature->id) ? $scores->get($asignature->id)['total_score'] : NULL;
        //         return $asignature;
        //     })->values()->all();

        //     // Unset asignatures from college degree
        //     unset($item->asignatures);
        //     return $item;
        // })->values()->all();

        // $user->missing_asignatures = $collegeDegrees;

        return ApiResponseController::response('Consulta exitosa', 200, $user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(!$student = Student::find($id)){
            return ApiResponseController::response('', 204);
        }

        $documentFrontFileName        = $student->document_front_file;
        $documentBackFileName        = $student->document_back_file;
        $bornStudyCertificateFileName = $student->study_certificate_file;

        // Check if document_file is a file
        if($request->hasFile('document_front_file')){
            $file = $request->file('document_front_file');
            $documentFrontFileName = time().$file->getClientOriginalName();
            $file->move(public_path().'/students-documents/', $documentFrontFileName);
        }

        // Check if born_file is a file
        if($request->hasFile('document_back_file')){
            $file = $request->file('document_back_file');
            $documentBackFileName = time().$file->getClientOriginalName();
            $file->move(public_path().'/students-documents/', $documentBackFileName);
        }

        // Check if born_file is a file
        if($request->hasFile('study_certificate_file')){
            $file = $request->file('study_certificate_file');
            $bornStudyCertificateFileName = time().$file->getClientOriginalName();
            $file->move(public_path().'/students-documents/', $bornStudyCertificateFileName);
        }



        $student->contract_number = $request->contract_number;
        $student->first_name = $request->first_name;
        $student->last_name = $request->last_name;
        $student->email = $request->email;
        $student->document = $request->document;
        $student->college_degree_id = $request->college_degree_id;
        $student->address = $request->address;
        $student->phone = $request->phone;
        $student->country_id = $request->country_id;
        $student->sex = $request->sex;
        $student->born_at = $request->born_at;
        $student->document_front_file = $documentFrontFileName;
        $student->document_back_file = $documentBackFileName;
        $student->study_certificate_file = $bornStudyCertificateFileName;
        $student->save();

        // Conver array string to array
        $collageDegrees = json_decode($request->collegeDegrees);

        $ids = [];
        foreach($collageDegrees as $collageDegree){
            $ids[] = $collageDegree->college_degree_id;
        }

        // Sync
        $student->collegeDegrees()->sync($ids);

        foreach($collageDegrees as $collageDegree){
            $student->collegeDegrees()->updateExistingPivot($collageDegree->college_degree_id, [
                'status_id' => $collageDegree->status_id,
                'cohort' => $collageDegree->cohort,
                'promotion_year' => $collageDegree->promotion_year,
                'resolution' => $collageDegree->resolution,
                'resolution_date' => $collageDegree->resolution_date,
                'title_status_id' => $collageDegree->title_status_id,
            ]);
        }

        return ApiResponseController::response('Usuario actualizada exitosamente', 200);
    }

    public function uploadsFiles(Request $request)
    {
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


        unset($data[0]);

        $students = [];
        foreach($data as $student){

            $students[] = [
                'last_name'                       => $student[$nombres],
                'first_name'                      => $student[$apellidos],
                'sex'                             => $student[$sexo],
                'document'                        => $student[$documento],
                'nationality'                     => $student[$nacionalidad],
                'phone'                           => $student[$telefono],
                'email'                           => $student[$email],
                'registered_postgraduate_program' => $student[$programa],
                'campus'                          => $student[$sede],
                'subsidiary'                      => $student[$filial],
                'enrollment_date'                 => $student[$dechaInscripcion],
                'contract_number'                 => $student[$numeroInscripcion],
                'year'                            => $student[$cohorte],
                'college_degrees_status'          => $student[$status],
                'promotion'                       => $student[$promocion],
            ];


            $i = count($students) - 1;

            DB::table('students_debug')->insert($students[$i]);
            // remove nationality, registered_postgraduate_program from student
            unset($students[$i]['nationality']);
            unset($students[$i]['registered_postgraduate_program']);
            unset($students[$i]['status']);
            unset($students[$i]['college_degrees_status']);
            // Update or create student
            Student::updateOrCreate(
                ['document' => $students[$i]['document']],
                $students[$i]
            );
        }

        $collageDegrees = array_merge(
            DB::table('college_degrees')->get()->pluck('id', 'name')->toArray(),
            DB::table('bad_college_degrees')->selectRaw('college_degree_id as id, name')->get()->pluck('id', 'name')->toArray()
        );
        $status = DB::table('college_degree_status')->get()->pluck('id', 'name')->toArray();
        $studentsDebug = DB::table('students_debug')->get();
        $badPrograms = [];
        foreach($studentsDebug as $studentDebug){

            $program = $studentDebug->registered_postgraduate_program;
            $program = trim($program);
            $program = str_replace(array("\r", "\n"), '', $program);

            if(
                $program !='Diplomado de Actualizacion en Impuesto a la Renta de las Personas Fisicas' &&
                $program !='Programa' &&
                $program !='NUEVA REFORMA 2020 - 6 Meses' &&
                $program !='Reforma de actualizacion la ley 6380/19  para egresados' &&
                $program !='' && $program != NULL
            ){
                try{
                    DB::table('student_college_degrees')->insert([
                        'student_id' => Student::where('document', $studentDebug->document)->first()->id,
                        'college_degree_id' => $collageDegrees[$program],
                        'status_id' => $status[$studentDebug->college_degrees_status],
                    ]);
                } catch(\Exception $e){
                    $badPrograms[] = $program;
                }
            }
        }

        DB::table('bad_college_degrees')->insert(array_map(function($badProgram){
            return ['name' => $badProgram];
        }, array_unique($badPrograms)));

        $dom = new \DOMDocument();
        // Extract id from $data
        $ids = array_unique(array_map(function($student) use ($documento){
            return $student[$documento];
        }, $data));

        // Remove empty values
        $ids2 = [];
        foreach($ids as $id){
            if($id != NULL && $id != ''){
                $ids2[] = ''.$id.'';
            }
        }

        foreach(Student::whereIn('document', $ids2)->get()->toArray() as $student){

            try {
                unset($student['created_at']);
                unset($student['updated_at']);
                $student = self::RUCRequest($student);

                if(!$student['finded_ruc']){
                    $student = self::IPSRequest($student, $dom);
                }

                Student::where('id', $student['id'])->update($student);
            } catch (\Throwable $th) {}

        }

        // tuncate students_debug
        DB::table('students_debug')->truncate();

        return ApiResponseController::response('Registros importados', 200);
    }

    public function IPSRequest($student, $dom)
    {
            $url = 'https://servicios.ips.gov.py/consulta_asegurado/comprobacion_de_derecho_externo.php?nro_cic='.$student['document'].'&envio=ok&bandera=1';

            $client = new \GuzzleHttp\Client();
            $response = $client->request('GET', $url);
            $response = $response->getBody()->getContents();
            @$dom->loadHTML($response);
            $xpath = new \DOMXPath($dom);

            // Get tr with bgcolor attribute
            $tr = $xpath->query('//tr[@bgcolor]');
            if($tr->length == 0){
                return [
                    ...$student,
                    'first_name' => $student['first_name'],
                    'last_name'  => $student['last_name'],
                    'finded_ips' => false,
                ];
            }
            $td = $tr->item(0)->getElementsByTagName('td');

            return [
                ...$student,
                'first_name' => $td->item(2)->nodeValue,
                'last_name' => $td->item(3)->nodeValue,
                'finded_ips'=> true
            ];
    }

    public function RUCRequest($student)
    {
        $url = 'https://www.ruc.com.py/index.php/inicio/consulta_ruc';

        // cookie: _ga=GA1.3.575893952.1664673006; _fbp=fb.2.1664673006369.1062720108; _gid=GA1.3.1302468609.1664887368; _gat=1
        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', $url, [
            'form_params' => [
                'buscar' => $student['document'],
            ],
            'headers' => [
                'Cookie' => '_ga=GA1.3.575893952.1664673006; _fbp=fb.2.1664673006369.1062720108; _gid=GA1.3.1302468609.1664887368; _gat=1',
                "origin" => 'https://www.ruc.com.py',
                "referer" => 'https://www.ruc.com.py/',
                "sec-ch-ua" => '"Chromium";v="106", "Microsoft Edge";v="106", "Not;A=Brand";v="99"',
                "sec-ch-ua-mobile" => '?0',
                "sec-ch-ua-platform" => '"Windows"',
                "sec-fetch-dest" => 'empty',
                "sec-fetch-mode" => 'cors',
                "sec-fetch-site" => 'same-origin',
                "user-agent" => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/106.0.0.0 Safari/537.36 Edg/106.0.1370.34',
                "x-requested-with" => 'XMLHttpRequest',
            ]
        ]);
        $response = $response->getBody()->getContents();

        if(!$response){
            return [
                ...$student,
                'finded_ruc' => false,
            ];
        }

        $names = json_decode($response)[0]->c_razon_social;
        return [
            ...$student,
            'first_name' => isset(explode(', ', $names)[1]) ? explode(', ', $names)[1] : '',
            'last_name' => explode(', ', $names)[0],
            'finded_ruc'=> true
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function uploadStudentFile(Request $request)
    {

        $colllegeDegreeOrigin = CollegeDegree::all()->pluck('id', 'name')->toArray();
        $badCollegeDegree = DB::table('bad_college_degrees')->get()->pluck('college_degree_id', 'name')->toArray();
        $collegeDegrees = array_merge($colllegeDegreeOrigin, $badCollegeDegree);

        $studentsDB = DB::table('students')->get()->pluck('id', 'document')->toArray();

        $rel = DB::table('student_college_degrees')->selectRaw('id, CONCAT(student_id, "-", college_degree_id) as rel')->get()->pluck('id', 'rel')->toArray();
        $status = CollegeDegreeStatus::all()->pluck('id', 'name')->toArray();
        $titleStatus = TitleStatus::all()->pluck('id', 'name')->toArray();
        $countries = Country::all()->pluck('id', 'name')->toArray();
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
        $fechaInscripcion  = array_search('Fecha Inscripción', $data[0]);
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

            $document = trim($student[$documento]);
            $document = preg_replace('/[^0-9]/', '', $document);
            if(!isset($studentsDB[$document])){

                $id = DB::table('students')->insertGetId([
                    'first_name'      => $student[$nombres],
                    'last_name'       => $student[$apellidos],
                    'sex'             => $student[$sexo],
                    'document'        => $document,
                    'phone'           => $student[$telefono],
                    'email'           => $student[$email],
                    'country_id'      => $countries[$student[$nacionalidad]] ?? NULL,
                    'campus'          => $student[$sede],
                    'subsidiary'      => $student[$filial],
                    'enrollment_date' => $student[$fechaInscripcion],
                    'contract_number' => $student[$numeroInscripcion],
                    'status'          => $student[$activo],
                ]);

                $studentsDB[$document] = $id;
            }

            $student_id = $studentsDB[$document];

            // Check if college degree exists
            if(!isset($collegeDegrees[$student[$programa]])){
                continue;
            }


            $rel_id = $student_id . '-' . $collegeDegrees[$student[$programa]];

            if(!isset($rel[$rel_id])){
                DB::table('student_college_degrees')->insert([
                    'student_id' => $student_id,
                    'college_degree_id' => $collegeDegrees[$student[$programa]],
                    'status_id' => $status[$student[$status]] ?? NULL,
                    'promotion_year' => $student[$promocion],
                    'title_status_id' => $titleStatus[$student[$estadoTitulo]] ?? NULL,
                    'resolution' => $student[$resolucion],
                    'resolution_date' => $student[$fechaResolucion],
                ]);
            } else {
                DB::table('student_college_degrees')
                ->where('student_id', $student_id)
                ->where('college_degree_id', $collegeDegrees[$student[$programa]])
                ->update([
                    'status_id'       => $status[$student[$status]] ?? NULL,
                    'promotion_year'  => $student[$promocion],
                    'title_status_id' => $titleStatus[$student[$estadoTitulo]] ?? NULL,
                    'resolution'      => $student[$resolucion],
                    'resolution_date' => $student[$fechaResolucion],
                ]);
            }


        }

        return ApiResponseController::response('Exito', 200);
    }
}
