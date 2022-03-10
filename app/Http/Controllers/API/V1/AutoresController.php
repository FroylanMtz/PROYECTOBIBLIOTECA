<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Autor;

class AutoresController extends BaseController
{
    protected $autor = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Autor $autor)
    {
        //$this->middleware('auth:api');
        $this->autor = $autor;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $autores = Autor::all();

        return $this->sendResponse($autores, 'Lista de autores');
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
        $autor = $this->autor->create([
            'nombres' => $request->get('nombres'),
            'apellidos' => $request->get('apellidos'),
            'nacionalidad' => $request->get('nacionalidad'),
            'genero' => $request->get('genero'),
            'fecha_nacimiento' =>  $request->get('fecha_nacimiento')
        ]);

        return $this->sendResponse($autor, 'Autor creador correctamente');

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
        $autor = $this->autor->findOrFail($id);

        $autor->update($request->all());

        return $this->sendResponse($autor, 'Información de autor actualizada');

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

        $autor = Autor::findOrFail($id);
        // delete the user

        $autor->delete();

        return $this->sendResponse([$autor], 'Autor eliminado correctamente');

    }
}
