<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Libro;
use Illuminate\Support\Facades\DB;

use App\Models\Autor;
use App\Models\Editorial;

class LibrosController extends BaseController
{
    protected $libro = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Libro $libro)
    {
        //$this->middleware('auth:api');
        $this->libro = $libro;
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
        $libros = DB::select('SELECT libro.*, editorial.id as editorial, editorial.nombre as nombreEditorial
        FROM libro 
        INNER JOIN libro_editorial 
        ON libro.id = libro_editorial.id_libro
        INNER JOIN editorial
        ON editorial.id = libro_editorial.id_editorial');

       // print_r($libros);

        forEach ($libros as $libro) {
            

            $id_libro = $libro->id;
            $autores = DB::select('SELECT autor.id as id_autor, autor.nombres as nombreAutor, autor.apellidos as apellidoAutor
            FROM autor 
            INNER JOIN libro_autor 
            ON libro_autor.id_autor = autor.id
            WHERE libro_autor.id_libro = ?', [$id_libro]);

            $libro->autores = $autores;
        }

        /*for( $i = 0; $i < count($libros); $i++ ){

            $id_libro = $libros[$i]['id'];
            $autores = DB::select('SELECT autor.id as id_autor, autor.nombres as nombreAutor, autor.apellidos as apellidoAutor
            FROM autor 
            INNER JOIN libro_autor 
            ON libro_autor.id_autor = autor.id');

            $libros[$i]->autores = $autores;
            
        }*/


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
        $name = '';
        $foto = 'defaultlibro.png';
        $id_autor  = 0;
        $id_editorial = 0;
        
        if($request->get('image')) {
            $image = $request->get('image');
            $name = time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            \Image::make($request->get('image'))->save(public_path('images/libros/').$name);
            $foto = $name;
        }
        
        $libro = $this->libro->create([
            'isbn' => $request->get('isbn'),
            'titulo' => $request->get('titulo'),
            'idioma' => $request->get('idioma'),
            'edicion' => $request->get('edicion'),
            'anio' => $request->get('anio'),
            'genero' => $request->get('genero'),
            'descripcion' => $request->get('descripcion'),
            'fecha_lanzamiento' => $request->get('fecha_lanzamiento'),
            'region' => $request->get('region'),
            'foto1' => $foto
        ]);

        $autor = $request->get('autor');
        $editorial = $request->get('editorial');  
        
        $totalAutores = count($autor);


        for($i=0; $i < count( $autor ); $i++){
            $id = DB::table('libro_autor')->insertGetId(
                [ 'id_autor' => $autor[$i]['id'], 'id_libro' => $libro->id ]
            );
        }
        
        $id = DB::table('libro_editorial')->insertGetId(
            [ 'id_editorial' => $editorial, 'id_libro' => $libro->id ]
        );

        return $this->sendResponse($totalAutores, 'Libro guardado correctamente');
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
        $libro = $this->libro->findOrFail($id);

        $id_autor  = 0;
        $id_editorial = 0;
        
        if($request->get('image')) {

            $image = $request->get('image');
            $foto  = time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            \Image::make($request->get('image'))->save(public_path('images/libros/').$foto);
            
            $libro->update([
                'foto1' => $foto
            ]);
        }
        
        $libro->update([
            'isbn' => $request->get('isbn'),
            'titulo' => $request->get('titulo'),
            'idioma' => $request->get('idioma'),
            'edicion' => $request->get('edicion'),
            'anio' => $request->get('anio'),
            'genero' => $request->get('genero'),
            'descripcion' => $request->get('descripcion'),
            'fecha_lanzamiento' => $request->get('fecha_lanzamiento'),
            'region' => $request->get('region'),
        ]);

        $autor = $request->get('autor');
        $editorial = $request->get('editorial');  

        DB::table('libro_autor')->where('id_libro', '=', $id)->delete();

        for($i=0; $i < count( $autor ); $i++){
            $id = DB::table('libro_autor')->insertGetId(
                [ 'id_autor' => $autor[$i]['id'], 'id_libro' => $libro->id ]
            );  
        }
        
        $affected = DB::update(
            'update libro_editorial set id_editorial = ? where id_libro = ?',
            [  $editorial , $id ]
        );

        return $this->sendResponse($affected, 'Libro actualizado correctamente');

    }

    public function obtenerAutores()
    {
        $autores = DB::select('SELECT * FROM autor' );
        return $this->sendResponse($autores, 'Lista de los autores');
    }

    public function obtenerEditoriales()
    {
        $editoriales = DB::select('SELECT * FROM editorial');
        return $this->sendResponse($editoriales, 'Lista de los editoriales');
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

        $libro = Libro::findOrFail($id);
        // delete the user

        $libro->delete();

        return $this->sendResponse([$libro], 'Libro eliminado correctamente');

    }
}
