<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Editorial;

class EditorialController extends BaseController
{
    protected $editorial = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Editorial $editorial)
    {
        //$this->middleware('auth:api');
        $this->editorial = $editorial;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $editoriales = Editorial::all();

        return $this->sendResponse($editoriales, 'Lista de editoriales');
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
        $editorial = $this->editorial->create([
            'nombre' => $request->get('nombre'),
            'empresa' => $request->get('empresa'),
            'localizacion' => $request->get('localizacion')
        ]);

        return $this->sendResponse($editorial, 'Editorial creado correctamente');

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
        $editorial = $this->editorial->findOrFail($id);

        $editorial->update($request->all());

        return $this->sendResponse($editorial, 'Información de la editorial actualizada');

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

        $editorial = Editorial::findOrFail($id);
        // delete the user

        $editorial->delete();

        return $this->sendResponse([$editorial], 'Editorial eliminado correctamente');

    }
}
