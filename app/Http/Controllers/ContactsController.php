<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContactsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPage = $request->input('perPage') ? $request->input('perPage') : 10;

		$contact = Contact::when($request->city, function($q) use ($request) {
            $q->where('city', $request->city);
        })
        ->when($request->company, function($q) use ($request) {
            $q->where('compny', $request->company);
        })
        ->when($request->profession, function($q) use ($request) {
            $q->where('profession', $request->profession);
        })
        ->when($request->file, function($q) use ($request) {
            $q->where('file', 'like', '%'.$request->file.'%');
        })
        ->paginate($perPage);

        return ApiResponseController::response('Consulta Exitosa', 200, $contact);
    }

    public function getAll(Request $request)
    {
        $perPage = $request->input('perPage') ? $request->input('perPage') : 10;

		$contact = Contact::selectRaw('first_name as Nombre, last_name as Apellido, city as Ciudad, email as Email, company as Empresa, profession as Profesion, file as Archivo')
        ->when($request->city, function($q) use ($request) {
            $q->where('city', $request->city);
        })
        ->when($request->company, function($q) use ($request) {
            $q->where('compny', $request->company);
        })
        ->when($request->profession, function($q) use ($request) {
            $q->where('profession', $request->profession);
        })
        ->when($request->file, function($q) use ($request) {
            $q->where('file', 'like', '%'.$request->file.'%');
        })
        ->get();

        return ApiResponseController::response('Consulta Exitosa', 200, $contact);
    }


    public function uploadDocument(Request $request)
    {
         // max execution time
         ini_set('max_execution_time', -1);

         // return file name
         $file = $request->file('file');

         // open file
         $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);

         // return data
         $data = $spreadsheet->getSheet(0)->toArray();



        $first_name = array_search('Nombre', $data[0]);
        $last_name  = array_search('Apellido', $data[0]);
        $document   = array_search('Cédula', $data[0]);
        $company    = array_search('Empresa', $data[0]);
        $email      = array_search('Email', $data[0]);
        $profession = array_search('Profesión', $data[0]);
        $city       = array_search('Ciudad', $data[0]);
        $file       = array_search('Archivo', $data[0]);
        $tab        = array_search('Pestana', $data[0]);
        $phone      = array_search('telefono', $data[0]);

        unset($data[0]);

        $contacts = [];
        foreach($data as $student){

            $contacts[] = [
                'first_name' => $student[$first_name],
                'last_name'  => $student[$last_name],
                'document'   => $student[$document],
                'company'    => $student[$company],
                'email'      => $student[$email],
                'profession' => $student[$profession],
                'city'       => $student[$city],
                'file'       => $student[$file],
                'tab'        => $student[$tab],
                'phone'      => $student[$phone]
            ];
        }

        foreach (array_chunk($contacts,1000) as $t)
        {
            DB::table('contacts')->insert($t);
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        $contact             = new Contact;
        $contact->first_name = $request->first_name;
        $contact->last_name  = $request->last_name;
        $contact->email      = $request->email;
        // $contact->phone      = $request->phone;
        $contact->company    = $request->company;
        $contact->document   = $request->document;
        $contact->city       = $request->city;
        $contact->profession = $request->profession;
        $contact->save();

	    return ApiResponseController::response('Contacto creada exitosamente', 201);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(!$contact = Contact::find($id)){
            return ApiResponseController::response('', 204);
        }

        return ApiResponseController::response('Consulta exitosa', 200, $contact);
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
        if(!$contact = Contact::find($id)){
            return ApiResponseController::response('', 204);
        }

        $contact->first_name = $request->first_name;
        $contact->last_name  = $request->last_name;
        $contact->email      = $request->email;
        // $contact->phone      = $request->phone;
        $contact->company    = $request->company;
        $contact->document   = $request->document;
        $contact->city       = $request->city;
        $contact->profession = $request->profession;
        $contact->save();

	    return ApiResponseController::response('Contacto creada exitosamente', 201);

    }


    public function getPropertyEntities(Request $request)
    {
        $professions = Contact::select('profession')->distinct()->get()->pluck('profession');
        $company    = Contact::select('company')->distinct()->get()->pluck('company');
        $city       = Contact::select('city')->distinct()->get()->pluck('city');
        $files     = Contact::select('file')->distinct()->get()->pluck('file');

        $entities = [
            'professions' => $professions,
            'companies'     => $company,
            'cities'        => $city,
            'files'       => $files

        ];

        return ApiResponseController::response('Consulta exitosa', 200, $entities);
    }
}
