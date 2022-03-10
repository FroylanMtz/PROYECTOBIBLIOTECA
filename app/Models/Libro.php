<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Libro extends Model
{
    use HasFactory;
    protected $table = 'libro';
    public $timestamps = false;

    protected $fillable = ['id','isbn','titulo','idioma','edicion','anio','genero','descripcion','fecha_lanzamiento', 'region','foto1','foto2'];
}
