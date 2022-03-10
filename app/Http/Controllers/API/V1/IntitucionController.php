<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\UserRequest;
use Illuminate\Http\Request;
use App\Models\Institucion;

class IntitucionController extends BaseController
{
    protected $institucion = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Institucion $institucion)
    {
        //$this->middleware('auth:api');
        $this->institucion = $institucion;
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
        $instituciones  = $this->institucion->all();

        return $this->sendResponse($instituciones, 'Lista de instituciones');

        //return $b;
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
        //$institucion = $this->institucion->create([
        //    'name' => $request->get('name')
        //]);
        
        $name = '';
        if($request->get('image'))
        {
            $image = $request->get('image');
            $name = time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            \Image::make($request->get('image'))->save(public_path('images/').$name);

            $institucion = $this->institucion->create([
                'nombre' => $request->get('nombre'),
                'codigo' => $request->get('codigo'),
                'telefono' => $request->get('telefono'),
                'extension' => $request->get('extension'),
                'logotipo' => $name
            ]);

        }else{

            $institucion = $this->institucion->create([
                'nombre' => $request->get('nombre'),
                'codigo' => $request->get('codigo'),
                'telefono' => $request->get('telefono'),
                'extension' => $request->get('extension'),
                'logotipo' => 'default_inst.png'
            ]);

        }
        
        

        //$image = new FileUpload();
        //$image->image_name = $name;
        //$image->save();

        return $this->sendResponse($institucion, 'Institucion creada con exito');
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
        $inst = $this->institucion->findOrFail($id);
        $name = '';
        if($request->get('image'))
        {
            $image = $request->get('image');
            $name = time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            \Image::make($request->get('image'))->save(public_path('images/').$name);

            $inst->update([
                'nombre' => $request->get('nombre'),
                'codigo' => $request->get('codigo'),
                'telefono' => $request->get('telefono'),
                'extension' => $request->get('extension'),
                'logotipo' => $name
            ]);

        }else{

            $inst->update([
                'nombre' => $request->get('nombre'),
                'codigo' => $request->get('codigo'),
                'telefono' => $request->get('telefono'),
                'extension' => $request->get('extension')
            ]);

        }
        
        

        return $this->sendResponse($inst, 'Informacion de la institucion actualizadad correctamente');
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

        $inst = Institucion::findOrFail($id);
        // delete the user

        $inst->delete();

        return $this->sendResponse([$inst], 'La institucion ha sido eliminada');
    }
}
