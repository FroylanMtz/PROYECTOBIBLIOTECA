<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prestamo extends Model
{
    use HasFactory;
    protected $table = 'prestamo';
    public $timestamps = false;

    protected $fillable = ['id','id_usuario','fecha','fecha_devolucion','estatus'];
}
