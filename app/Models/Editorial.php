<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Editorial extends Model
{
    use HasFactory;

    protected $table = 'editorial';
    public $timestamps = false;

    protected $fillable = ['id','nombre','empresa','localizacion','anio'];
    public $incrementing = true;

}
