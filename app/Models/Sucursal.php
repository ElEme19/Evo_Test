<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
    protected $table = 'sucursales';
    protected $primaryKey = 'id_sucursal';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id_sucursal',
        'nombre_sucursal',
        'localizacion',
        'foto_fachada',
    ];

    // Relaciones (descomenta sólo si existen esas otras tablas/models)
    // public function ruta()
    // {
    //     return $this->belongsTo(Ruta::class, 'id_ruta', 'id_ruta');
    // }

    // public function cliente()
    // {
    //     return $this->belongsTo(Cliente::class, 'id_cliente', 'id_cliente');
    // }

    public function envios()
    {
        return $this->hasMany(Envio::class, 'id_sucursal', 'id_sucursal');
    }
}
