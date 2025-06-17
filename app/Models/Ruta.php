<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ruta extends Model
{
    protected $table = 'ruta';
    protected $primaryKey = 'id_ruta';
    public $incrementing = false; // PK tipo string
    protected $keyType = 'string';
    public $timestamps = false; // No hay timestamps en la tabla

    protected $fillable = [
        'id_ruta',
        'localizacion',
    ];

    // Relaciones

    public function sucursales()
    {
        return $this->hasMany(Sucursal::class, 'id_ruta', 'id_ruta');
    }
}
