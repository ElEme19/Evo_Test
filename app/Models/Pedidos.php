<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedidos extends Model
{
    use HasFactory;

    protected $table = 'pedidos';              // Nombre de la tabla
    protected $primaryKey = 'id_pedido';       // Clave primaria
    public $incrementing = false;               // ✅ Autoincremental
    protected $keyType = 'string';                // ✅ Tipo entero
    public $timestamps = false;                // No usa created_at ni updated_at

protected $casts = [
    'fecha_envio' => 'datetime',
];
    protected $fillable = [
    'id_pedido',
    'id_sucursal',
    'num_chasis',
    'fecha_envio',
];



    // Relación: un pedido pertenece a una sucursal
    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class, 'id_sucursal', 'id_sucursal');
    }

    // Relación: un pedido tiene una bicicleta (por su número de chasis)
    public function bicicletas()
{
    return $this->hasMany(Bicicleta::class, 'id_pedido', 'id_pedido');
}

}
