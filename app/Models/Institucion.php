<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Institucion extends Model
{
    use HasFactory;
    protected $table = 'institucion';
    public $timestamps = false;

    protected $fillable = ['id','codigo','nombre','telefono','extension','logotipo'];
    public $incrementing = true;

}
