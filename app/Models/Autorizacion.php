<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Autorizacion extends Model
{
    use HasFactory;

    protected $table = 'autorizaciones'; // <-- especificamos el nombre correcto de la tabla

    protected $fillable = [
        'num_chasis',
        'usuario_solicita',
        'estado',
        'token',
    ];
}

