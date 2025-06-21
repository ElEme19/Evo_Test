<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personal extends Model
{
    use HasFactory;

    protected $table = 'personal';
    protected $primaryKey = 'id_personal';
    public $incrementing = false; // Asumimos que es autoincremental
    protected $keyType = 'string';  // Tipo de clave primaria
    public $timestamps = false;  // No usamos created_at ni updated_at

    protected $fillable = [
        'id_area',
        'nombre',
        'apellido',
        'telefono',
        'foto_personal',
        'direccion',
        'antiguedad',
        'salario',
    ];
}
