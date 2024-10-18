<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calendar extends Model
{

    use HasFactory;
    //esto no es recomandable para produccion
    protected $guarded = []; //desactiva la funcion de mass assignment
    //tambien perimte craer y modificar todos los campos de un solo
}
