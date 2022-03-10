<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Copia;
use App\Models\Libro;
use Illuminate\Support\Facades\DB;

class CopiasController extends BaseController
{
    protected $copias = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Copia $copia)
    {
        //$this->middleware('auth:api');
        $this->copias = $copia;
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
        
        $copias = Copia::all();
        
        forEach ($copias as $copia) {
            $id_libro = $copia->id_libro;
            $libro = DB::select('SELECT libro.id as id_libro, libro.titulo, libro.foto1, libro.edicion
            FROM libro 
            WHERE libro.id = ?', [$id_libro]);
            $copia->libro = $libro;
        }


        forEach ($copias as $copia) {
            $id_libro = $copia->id_libro;
            $autores = DB::select('SELECT autor.nombres as nombreAutor, autor.apellidos as apellidoAutor
            FROM autor 
            INNER JOIN libro_autor 
            ON libro_autor.id_autor = autor.id
            WHERE libro_autor.id_libro = ?', [$id_libro]);
            $copia->autores = $autores;
        }

        return $this->sendResponse($copias, 'Lista de libros');
    }

    public function obtenerLibros()
    {
        $libros = DB::select('SELECT * FROM libro ');

        forEach ($libros as $libro) {
            $id_libro = $libro->id;
            $autores = DB::select('SELECT autor.nombres as nombreAutor, autor.apellidos as apellidoAutor
            FROM autor 
            INNER JOIN libro_autor 
            ON libro_autor.id_autor = autor.id
            WHERE libro_autor.id_libro = ?', [$id_libro]);
            $libro->autores = $autores;
        }


        return $this->sendResponse($libros, 'Lista de libros');
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
        $copia = $this->copias->create([
            'id_libro' => $request->get('id_libro'),
            'codigo' => $request->get('codigo'),
            'fecha_adquisicion' => $request->get('fecha_adquisicion'),
            'obntecion' => $request->get('obtencion')
        ]);
        
        return $this->sendResponse($copia, 'Información de copia del libro guardada correctamente');

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
        $copia = $this->copias->findOrFail($id);
        $copia->update($request->all());

        return $this->sendResponse($copia, 'Informacion de la copia actualizada correctamente');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //$this->authorize('isAdmin');

        $copia = Copia::findOrFail($id);

        $copia->delete();

        return $this->sendResponse($copia, 'La información de la copia ha sido eliminada');
    }
}
