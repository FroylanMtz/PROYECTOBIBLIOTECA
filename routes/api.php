<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('version', function () {
    return response()->json(['version' => config('app.version')]);
});


Route::middleware('auth:api')->get('/user', function (Request $request) {
    Log::debug('User:' . serialize($request->user()));
    return $request->user();
});

Route::namespace('App\\Http\\Controllers\\API\V1')->group(function () {

    Route::get('profile', 'ProfileController@profile');
    Route::put('profile', 'ProfileController@updateProfile');
    Route::post('change-password', 'ProfileController@changePassword');
    Route::get('tag/list', 'TagController@list');
    Route::get('category/list', 'CategoryController@list');
    Route::post('product/upload', 'ProductController@upload');

    Route::post('/institucion/update/{id}', 'IntitucionController@update');

    Route::post('/libro/update/{id}', 'LibrosController@update');

    //Alumno
    Route::get('/user/alumnos', 'UserController@alumnos');
    Route::post('/alumno/crear', 'UserController@storeAlumno');
    Route::put('/actualizar/alumno/{id}', 'UserController@updateAlumno');
    Route::get('/alumno/informacion/{id}', 'UserController@infoAlumno');
    Route::post('/alumno/informacionPersonal', 'UserController@updatePersonalData');
    Route::get('/alumno/informacionEscolar/{id}', 'UserController@infoEscolarAlumno');
    Route::post('/alumno/informacionEscolar', 'UserController@updateEscolarData');
    Route::get('/alumno/informacionContacto/{id}', 'UserController@infoContactoAlumno');
    Route::post('/alumno/informacionContacto', 'UserController@updateContactoData');

    //Profesor
    Route::get('/user/profesores', 'UserController@profesores');
    Route::post('/profesor/crear', 'UserController@storeProfesor');
    Route::put('/actualizar/profesor/{id}', 'UserController@updateProfesor');

    //Personal
    Route::get('/user/personal', 'UserController@personal');
    Route::post('/personal/crear', 'UserController@storePersonal');
    Route::put('/actualizar/personal/{id}', 'UserController@updatePersonal');

    Route::get('/libro/autores',     'LibrosController@obtenerAutores');
    Route::get('/libro/editoriales', 'LibrosController@obtenerEditoriales');

    Route::get('/copia/libros', 'CopiasController@obtenerLibros');

    Route::get('/prestamo/usuarios', 'PrestamoController@obtenerUsuarios');


    Route::get('/prestamo/copias/{id}', 'PrestamoController@obtenerCopias');

    Route::get('/prestamo/librosPrestamo/{id}', 'PrestamoController@verLibrosPrestamo');

    Route::post('/prestamo/actualizar/{id}', 'PrestamoController@update');

    Route::apiResources([
        'user' => 'UserController',
        'product' => 'ProductController',
        'category' => 'CategoryController',
        'tag' => 'TagController',
        'institucion' => 'IntitucionController',
        'biblioteca' => 'BibliotecaController',
        'prestamo' => 'PrestamoController',
        'libro' => 'LibrosController',
        'copia' => 'CopiasController',
        'autor' => 'AutoresController',
        'editorial' => 'EditorialController'
    ]);


});
