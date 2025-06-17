<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Envio extends Model
{
    protected $table = 'envios';
    protected $primaryKey = 'id_envio';
    public $incrementing = false; // Usamos ENAC156Q6....
    protected $keyType = 'string';
    public $timestamps = false; // Puede y lo usemos despues

    protected $fillable = [
        'id_envio',
        'id_sucursal',
        'id_personal',
        'fecha_envio',
    ];

    // Relaciones

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class, 'id_sucursal', 'id_sucursal');
    }

    /* public function personal() // Falta lo de Eme
    {
        return $this->belongsTo(Personal::class, 'id_personal', 'id_personal');
    } */
}
