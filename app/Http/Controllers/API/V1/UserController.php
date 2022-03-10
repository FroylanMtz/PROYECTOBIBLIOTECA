<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Http\Requests\Users\UserRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends BaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!\Gate::allows('isAdmin')) {
            return $this->unauthorizedResponse();
        }
        // $this->authorize('isAdmin');

        $users = User::latest()->paginate(10);

        return $this->sendResponse($users, 'Users list');
    }

    public function infoContactoAlumno($id){
        $results = DB::select('SELECT *, direccion.id as id_direccionn
                FROM ususario_contacto 
                INNER JOIN contacto 
                ON ususario_contacto.id_contacto = contacto.id 
                INNER JOIN contacto_direccion
                ON contacto_direccion.id_contacto = ususario_contacto.id_contacto
                INNER JOIN direccion
                ON direccion.id = contacto_direccion.id_direccion 
                WHERE ususario_contacto.id_usuario = :id', ['id' =>$id]);

        return $this->sendResponse($results, 'Datos del contacto');
    }

    public function infoAlumno($id){
        $results = DB::select('SELECT *, direccion.id as id_direccionn FROM informacion_personal 
        INNER JOIN usuario_informacion 
        ON informacion_personal.id = usuario_informacion.id_informacion 
        INNER JOIN usuario_direccion
        ON usuario_direccion.id_usuario = usuario_informacion.id_usuario
        INNER JOIN direccion
        ON direccion.id = usuario_direccion.id_direccion 
        WHERE usuario_informacion.id_usuario = :id ', ['id' =>$id]);

        return $this->sendResponse($results, 'Lista de alumnos');
    }

    public function infoEscolarAlumno($id){
        $results = DB::select('SELECT * 
                              FROM 
                              usuario_infoescolar 
                              INNER JOIN informacionescolar 
                              ON informacionescolar.id = usuario_infoescolar.id_infoescolar 
                              WHERE usuario_infoescolar.id_usuario = :id', ['id' =>$id]);

        return $this->sendResponse($results, 'Información escolar de el alumno');
    }

    public function alumnos()
    {
        if (!\Gate::allows('isAdmin')) {
            return $this->unauthorizedResponse();
        }
        $users = DB::select('SELECT users.id, users.name, users.last_name, users.email, users.type, biblioteca.nombre as nombre_biblioteca, biblioteca.id as id_biblioteca, institucion.nombre as nombre_institucion, institucion.id as id_institucion
        FROM users 
        INNER JOIN usuario_biblioteca 
        ON usuario_biblioteca.id_usuario = users.id
        INNER JOIN biblioteca
        ON usuario_biblioteca.id_biblioteca = biblioteca.id
        INNER JOIN institucion
        ON biblioteca.id_institucion = institucion.id 
        WHERE users.type = "alumno" ');

        return $this->sendResponse($users, 'Lista de alumnos ');
    }



    


    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Users\UserRequest  $request
     *
     * @param $id
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(UserRequest $request)
    {
        $user = User::create([
            'name' => $request['name'],
            'last_name' => $request['last_name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'type' => $request['type'],
        ]);

        return $this->sendResponse($user, 'User Created Successfully');
    }

    public function updateEscolarData (Request $request){

        $affected = DB::update(
            'update informacionescolar set ingreso = ?, finalizacion = ?, semestre = ?, carrera = ?, facultad = ?, codigo = ?, creditos = ?, estado = ? where id = ?',
            [$request['ingreso'], $request['finalizacion'], $request['semestre'], $request['carrera'], $request['facultad'],$request['codigo'],$request['creditos'], $request['estado'], $request['id'] ]
        );

        return $this->sendResponse($affected, 'Información escolar guardada con exito');

    }

    public function updatePersonalData(Request $request){

        $affected = DB::update(
            'update informacion_personal set fecha_nacimiento = ?, telefono = ?, detalles = ? where id = ?',
            [$request['fecha_nacimiento'], $request['telefono'], $request['detalles'], $request['id'] ]
        );

        /*
            id_direccion: this.infoAlumno.id_direccion,
            calle: this.infoAlumno.calle ,
            numero: this.infoAlumno.numero  ,
            colonia: this.infoAlumno.colonia ,
            codigopostal: this.infoAlumno.codigopostal ,
            poblado: this.infoAlumno.poblado ,
            municipio: this.infoAlumno.municipio  ,
            estado: this.infoAlumno.estado  ,
            pais: this.infoAlumno.pais
        */

        $affected = DB::update(
           'UPDATE direccion 
            SET calle = ?, numero = ?, colonia = ?, codigopostal = ?, poblado = ?, municipio = ?, estado = ?, pais = ? 
            WHERE id = ?',
            [$request['calle'],$request['numero'],$request['colonia'],$request['codigopostal'],$request['poblado'],$request['municipio'],$request['estado'], $request['pais'], $request['id_direccion'] ]
        );

        return $this->sendResponse($affected, 'Información  guardada con exito');


    }

    public function updateContactoData(Request $request){

        $affected = DB::update(
            'update contacto set nombres = ?, apellidos = ?, edad = ?, parentesco = ?, correo = ?, telefono = ? where id = ?',
            [$request['nombres'], $request['apellidos'], $request['edad'], $request['parentesco'], $request['correo'], $request['telefono'], $request['id'] ]
        );

        $affected = DB::update(
            'UPDATE direccion 
             SET calle = ?, numero = ?, colonia = ?, codigopostal = ?, poblado = ?, municipio = ?, estado = ?, pais = ? 
             WHERE id = ?',
             [$request['calle'],$request['numero'],$request['colonia'],$request['codigopostal'],$request['poblado'],$request['municipio'],$request['estado'], $request['pais'], $request['id_direccion'] ]
         );

        return $this->sendResponse($affected, 'Información  del contacto guardada con exito');

    }

    public function storeAlumno(UserRequest $request)
    {
        $user = User::create([
            'name' => $request['name'],
            'last_name' => $request['last_name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'type' => 'alumno',
        ]);

        //DB::insert('insert into users (id, name) values (?, ?)', [1, 'Marc']);

        $id = DB::table('informacion_personal')->insertGetId(
            [ 'estatus' => 'activo']
        );

        DB::table('usuario_informacion')->insertGetId(
            [ 'id_usuario' => $user->id, 'id_informacion' => $id ]
        );

        $id = DB::table('informacionescolar')->insertGetId(
            [ 'estado' => 'activo']
        );

        DB::table('usuario_infoescolar')->insertGetId(
            [ 'id_usuario' => $user->id, 'id_infoescolar' => $id ]
        );


        $id_contacto = DB::table('contacto')->insertGetId(
            [ 'nombres' => '']
        );

        DB::table('ususario_contacto')->insertGetId(
            [ 'id_usuario' => $user->id, 'id_contacto' => $id_contacto ]
        );


        $id = DB::table('direccion')->insertGetId(
            [ 'calle' => '']
        );

        DB::table('usuario_direccion')->insertGetId(
            [ 'id_usuario' => $user->id, 'id_direccion' => $id ]
        );


        $id = DB::table('direccion')->insertGetId(
            [ 'calle' => '']
        );

        DB::table('contacto_direccion')->insertGetId(
            [ 'id_contacto' => $id_contacto, 'id_direccion' => $id ]
        );

        DB::table('usuario_biblioteca')->insertGetId(
            [ 'id_usuario' => $user->id, 'id_biblioteca' => $request['id_biblioteca'] ]
        );

        return $this->sendResponse($user, 'Alumno creado correctamente');
    }



    


    /**
     * Update the resource in storage
     *
     * @param  \App\Http\Requests\Users\UserRequest  $request
     * @param $id
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(UserRequest $request, $id)
    {
        $user = User::findOrFail($id);

        if (!empty($request->password)) {
            $request->merge(['password' => Hash::make($request['password'])]);
        }

        $user->update($request->all());

        return $this->sendResponse($user, 'User Information has been updated');
    }

    public function updateAlumno(UserRequest $request, $id)
    {
        $user = User::findOrFail($id);

        if (!empty($request->password)) {
            $request->merge(['password' => Hash::make($request['password'])]);
        }

        $request->type="alumno";
        $request['type'] = "alumno";
        $user->type = "alumno";
        $user->update($request->all());

        $affected = DB::update(
            'UPDATE usuario_biblioteca 
             SET id_biblioteca = ?
             WHERE id_usuario = ?',
             [$request['id_biblioteca'], $id]
         );
         

        return $this->sendResponse($user, 'La información del alumno ha sido actualizada');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $this->authorize('isAdmin');

        $user = User::findOrFail($id);
        // delete the user

        $user->delete();

        return $this->sendResponse([$user], 'User has been Deleted');
    }






    public function profesores()
    {
        if (!\Gate::allows('isAdmin')) {
            return $this->unauthorizedResponse();
        }
        
        $users = DB::select('SELECT users.id, users.name, users.last_name, users.email, users.type, biblioteca.nombre as nombre_biblioteca, biblioteca.id as id_biblioteca, institucion.nombre as nombre_institucion, institucion.id as id_institucion
        FROM users 
        INNER JOIN usuario_biblioteca 
        ON usuario_biblioteca.id_usuario = users.id
        INNER JOIN biblioteca
        ON usuario_biblioteca.id_biblioteca = biblioteca.id
        INNER JOIN institucion
        ON biblioteca.id_institucion = institucion.id 
        WHERE users.type = "profesor" ');

        return $this->sendResponse($users, 'Lista de profesores');
    }
    
    public function storeProfesor(UserRequest $request)
    {
        $user = User::create([
            'name' => $request['name'],
            'last_name' => $request['last_name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'type' => 'profesor',
        ]);

        //DB::insert('insert into users (id, name) values (?, ?)', [1, 'Marc']);

        $id = DB::table('informacion_personal')->insertGetId(
            [ 'estatus' => 'activo']
        );

        DB::table('usuario_informacion')->insertGetId(
            [ 'id_usuario' => $user->id, 'id_informacion' => $id ]
        );

        $id = DB::table('informacionescolar')->insertGetId(
            [ 'estado' => 'activo']
        );

        DB::table('usuario_infoescolar')->insertGetId(
            [ 'id_usuario' => $user->id, 'id_infoescolar' => $id ]
        );


        $id_contacto = DB::table('contacto')->insertGetId(
            [ 'nombres' => '']
        );

        DB::table('ususario_contacto')->insertGetId(
            [ 'id_usuario' => $user->id, 'id_contacto' => $id_contacto ]
        );


        $id = DB::table('direccion')->insertGetId(
            [ 'calle' => '']
        );

        DB::table('usuario_direccion')->insertGetId(
            [ 'id_usuario' => $user->id, 'id_direccion' => $id ]
        );


        $id = DB::table('direccion')->insertGetId(
            [ 'calle' => '']
        );

        DB::table('contacto_direccion')->insertGetId(
            [ 'id_contacto' => $id_contacto, 'id_direccion' => $id ]
        );

        DB::table('usuario_biblioteca')->insertGetId(
            [ 'id_usuario' => $user->id, 'id_biblioteca' => $request['id_biblioteca'] ]
        );

        return $this->sendResponse($user, 'Profesor creado correctamente');
    }


    public function updateProfesor(UserRequest $request, $id)
    {
        $user = User::findOrFail($id);

        if (!empty($request->password)) {
            $request->merge(['password' => Hash::make($request['password'])]);
        }

        $request->type="profesor";
        $request['type'] = "profesor";
        $user->type = "profesor";
        $user->update($request->all());

        $affected = DB::update(
            'UPDATE usuario_biblioteca 
             SET id_biblioteca = ?
             WHERE id_usuario = ?',
             [$request['id_biblioteca'], $id]
         );

        return $this->sendResponse($user, 'La información del profesor ha sido actualizada');
    }






















    public function personal()
    {
        if (!\Gate::allows('isAdmin')) {
            return $this->unauthorizedResponse();
        }
        $users = DB::select('SELECT users.id, users.name, users.last_name, users.email, users.type, biblioteca.nombre as nombre_biblioteca, biblioteca.id as id_biblioteca, institucion.nombre as nombre_institucion, institucion.id as id_institucion
        FROM users 
        INNER JOIN usuario_biblioteca 
        ON usuario_biblioteca.id_usuario = users.id
        INNER JOIN biblioteca
        ON usuario_biblioteca.id_biblioteca = biblioteca.id
        INNER JOIN institucion
        ON biblioteca.id_institucion = institucion.id 
        WHERE users.type = "bibliotecario" ');

        return $this->sendResponse($users, 'Lista de peronal de biblioteca');
    }
    
    public function storePersonal(UserRequest $request)
    {
        $user = User::create([
            'name' => $request['name'],
            'last_name' => $request['last_name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'type' => 'bibliotecario',
        ]);

        //DB::insert('insert into users (id, name) values (?, ?)', [1, 'Marc']);

        $id = DB::table('informacion_personal')->insertGetId(
            [ 'estatus' => 'activo']
        );

        DB::table('usuario_informacion')->insertGetId(
            [ 'id_usuario' => $user->id, 'id_informacion' => $id ]
        );

        $id = DB::table('informacionescolar')->insertGetId(
            [ 'estado' => 'activo']
        );

        DB::table('usuario_infoescolar')->insertGetId(
            [ 'id_usuario' => $user->id, 'id_infoescolar' => $id ]
        );


        $id_contacto = DB::table('contacto')->insertGetId(
            [ 'nombres' => '']
        );

        DB::table('ususario_contacto')->insertGetId(
            [ 'id_usuario' => $user->id, 'id_contacto' => $id_contacto ]
        );


        $id = DB::table('direccion')->insertGetId(
            [ 'calle' => '']
        );

        DB::table('usuario_direccion')->insertGetId(
            [ 'id_usuario' => $user->id, 'id_direccion' => $id ]
        );


        $id = DB::table('direccion')->insertGetId(
            [ 'calle' => '']
        );

        DB::table('contacto_direccion')->insertGetId(
            [ 'id_contacto' => $id_contacto, 'id_direccion' => $id ]
        );



        DB::table('usuario_biblioteca')->insertGetId(
            [ 'id_usuario' => $user->id, 'id_biblioteca' => $request['id_biblioteca'] ]
        );


        return $this->sendResponse($user, 'Personal creado correctamente');
    }


    public function updatePersonal(UserRequest $request, $id)
    {
        $user = User::findOrFail($id);

        if (!empty($request->password)) {
            $request->merge(['password' => Hash::make($request['password'])]);
        }

        $request->type="bibliotecario";
        $request['type'] = "bibliotecario";
        $user->type = "bibliotecario";
        $user->update($request->all());


        $affected = DB::update(
            'UPDATE usuario_biblioteca 
             SET id_biblioteca = ?
             WHERE id_usuario = ?',
             [$request['id_biblioteca'], $id]
         );


        return $this->sendResponse($user, 'La información del personal ha sido actualizada');
    }





}
