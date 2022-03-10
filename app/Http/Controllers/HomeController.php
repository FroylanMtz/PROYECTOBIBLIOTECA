<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function configSesion(Request $request)
    {
        session(['prueba' => 'dato de prueba']);
        session(['anios' => 20]);

        /*$results = DB::select('SELECT *, direccion.id as id_direccionn FROM informacion_personal 
        INNER JOIN usuario_informacion 
        ON informacion_personal.id = usuario_informacion.id_informacion 
        INNER JOIN usuario_direccion
        ON usuario_direccion.id_usuario = usuario_informacion.id_usuario
        INNER JOIN direccion
        ON direccion.id = usuario_direccion.id_direccion 
        WHERE usuario_informacion.id_usuario = :id ', ['id' =>$id]);*/


        #return $data = $request->session()->all();
        #return Auth::user();
        return redirect('/dashboard');
    }
}
