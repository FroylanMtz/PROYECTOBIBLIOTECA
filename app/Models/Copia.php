<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Copia extends Model
{
    use HasFactory;
    protected $table = 'copia';
    public $timestamps = false;

    protected $fillable = ['id','id_libro','codigo', 'fecha_adquisicion', 'obtencion'];

}
