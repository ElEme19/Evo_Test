<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Precio extends Model
{
    protected $table = 'precio'; // Nombre exacto de la tabla

    protected $primaryKey = 'id_precio'; // Clave primaria personalizada
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false; // Asumimos que no tiene created_at ni updated_at

    protected $fillable = [
        'id_precio',
        'id_membresia',
        'id_modelo',
        'id_voltaje',
        'precio',
    ];

    // Relación con Membresia
    public function membresia()
    {
        return $this->belongsTo(Membresia::class, 'id_membresia', 'id_membresia');
    }

    public function voltaje()
    {
        return $this->belongsTo(VoltajeModeloD::class, 'id_voltaje', 'id_voltaje');
    }

    // Relación con Modelo
    public function modelo()
    {
        return $this->belongsTo(modelos_bici::class, 'id_modelo', 'id_modelo');
    }
}
