<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Autor extends Model
{
    use HasFactory;

    protected $table = 'autor';
    public $timestamps = false;

    protected $fillable = ['id','nombres', 'apellidos', 'nacionalidad', 'genero', 'fecha_nacimiento'];
    public $incrementing = true;
}
