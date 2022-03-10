<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Biblioteca;
use Illuminate\Support\Facades\DB;

class BibliotecaController extends BaseController
{
    protected $biblioteca = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Biblioteca $biblioteca)
    {
        //$this->middleware('auth:api');
        $this->biblioteca = $biblioteca;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        //return '<h1> Prueba de respuesta </h1>';
        $biblioteca  = $this->biblioteca->all();

        return $this->sendResponse($biblioteca, 'Lista de bibliotecas');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $inst = $this->biblioteca->create([
            'nombre' => $request->get('nombre'),
            'codigo' => $request->get('codigo'),
            'facultad' => $request->get('facultad'),
            'edificio' => $request->get('edificio'),
            'aula' => $request->get('aula'),
            'piso' => $request->get('piso'),
            'id_institucion' => $request->get('id_institucion')
        ]);


        return $this->sendResponse($inst, 'Biblioteca creada con exito');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $biblioteca = DB::select('SELECT biblioteca.*
        FROM biblioteca 
        INNER JOIN institucion 
        ON biblioteca.id_institucion = institucion.id
        WHERE institucion.id = :id ', ['id' =>$id]);

        return $this->sendResponse($biblioteca, 'Informacion de las biblioteca de la instutcion');
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
        //
        $biblioteca = $this->biblioteca->findOrFail($id);
        $biblioteca->update($request->all());

        return $this->sendResponse($biblioteca, 'Informacion de la biblioteca actualizada correctamente');
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
        
        //$this->authorize('isAdmin');

        $biblioteca = Biblioteca::findOrFail($id);
        // delete the user

        $biblioteca->delete();

        return $this->sendResponse([$biblioteca], 'La biblioteca ha sido eliminada');

    }
}
