<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Prestamo;
use Illuminate\Support\Facades\DB;

class PrestamoController extends BaseController
{
    protected $prestamo = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Prestamo $prestamo)
    {
        //$this->middleware('auth:api');
        $this->prestamo = $prestamo;
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
        $prestamos = DB::select('SELECT prestamo.*, users.name as nombreUsuario, users.last_name as apellidoUsuario, informacionescolar.codigo as matricula
        FROM prestamo 
        INNER JOIN users 
        ON prestamo.id_usuario = users.id
        INNER JOIN usuario_infoescolar 
        ON usuario_infoescolar.id_usuario = users.id
        INNER JOIN informacionescolar 
        ON usuario_infoescolar.id_infoescolar = informacionescolar.id

        ');

        return $prestamos;
    }

    public function obtenerUsuarios()
    {
        $users = DB::select('SELECT users.id, users.name, users.last_name, users.email, users.type, informacionescolar.codigo as matricula
        FROM users
        INNER JOIN usuario_infoescolar
        ON usuario_infoescolar.id_usuario = users.id

        INNER JOIN informacionescolar
        ON informacionescolar.id = usuario_infoescolar.id_infoescolar

        WHERE users.type = "profesor" or users.type = "alumno" ');

        return $this->sendResponse($users, 'Lista de alumnos y profesores');

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
        
        $nuevoprestamo = $this->prestamo->create([
            'id_usuario' => $request->get('id_usuario'),
            'fecha' => date('Y-m-d'),
            'fecha_devolucion' => $request->get('fecha_devolucion'),
            'estatus' => 'Activo'
        ]);

        $libros = $request->get('libros');
        $totalLibros = sizeof($libros);
            
        //print_r($libros);
        for($i=0; $i < $totalLibros; $i++){

            $id = DB::table('prestamo_libro')->insertGetId(
                [ 'id_prestamo' => $nuevoprestamo->id, 'id_copia' => $libros[$i]['id_copia'] ]
            );

        }

        return $this->sendResponse($libros, 'Información del prestamo almacenada con exito');



    }

    public function verLibrosPrestamo($id){

        $libros = DB::select('SELECT libro.*, editorial.nombre as nombreEditorial, copia.id as id_copia,copia.codigo as codigoCopia, prestamo.id as prestamoLibro
        FROM libro 
        INNER JOIN libro_editorial 
        ON libro.id = libro_editorial.id_libro

        INNER JOIN editorial
        ON editorial.id = libro_editorial.id_editorial
        
        INNER JOIN copia
        ON copia.id_libro = libro.id
        
        INNER JOIN prestamo_libro
        ON prestamo_libro.id_copia = copia.id

        INNER JOIN prestamo
        ON prestamo.id = prestamo_libro.id_prestamo

        WHERE prestamo.id = ?
        
        ', [$id]);

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


        return $this->sendResponse($libros, 'Lista de copias del libro con id ');

    }

    public function obtenerCopias($id){

        $copias = DB::select('SELECT *
        FROM copia
        WHERE copia.id_libro = ?', [$id]);

        return $this->sendResponse($copias, 'Lista de copias del libro con id ');


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
        $prestamo = $this->prestamo->findOrFail($id);
        $prestamo->update([
            'id_usuario' => $request->get('id_usuario'),
            'fecha_devolucion' => $request->get('fecha_devolucion')
        ]);
        DB::table('prestamo_libro')->where('id_prestamo', '=', $id)->delete();
        $libros = $request->get('libros');
        $totalLibros = sizeof($libros);
        for($i=0; $i < $totalLibros; $i++){
            print_r($libros[$i]['id_copia'] . " - " . $id . " \n ");
            DB::table('prestamo_libro')->insertGetId(
                ['id_prestamo' => $id, 'id_copia' => $libros[$i]['id_copia']]
            );
        }
        return $this->sendResponse($request->get('id_usuario'), 'Información del prestamo actualizada');
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
        $prestamo = Prestamo::findOrFail($id);
        $prestamo->delete();
        return $this->sendResponse($id,'Información del prestamo eliminada');
    }
}
