<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Biblioteca extends Model
{
    use HasFactory;
    protected $table = 'biblioteca';
    public $timestamps = false;

    protected $fillable = ['id','codigo','nombre','facultad','edificio','aula','piso', 'id_institucion'];
    public $incrementing = true;
}
