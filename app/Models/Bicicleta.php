<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bicicleta extends Model
{
    protected $table = 'bicicleta';
    protected $primaryKey = 'num_chasis';
    public $incrementing = false; // Porque la PK no es un número autoincremental
    protected $keyType = 'string';
     public $timestamps = true;

    protected $fillable = [
        'num_chasis',
        'id_modelo',
        'id_color',
        'id_lote',
        'id_tipoStock',
        'voltaje',
        'num_motor',
        'error_iden_produccion',
        'descripcion_general',
    ];

    // Relaciones

    public function modelo()
    {
        return $this->belongsTo(modelos_bici::class, 'id_modelo', 'id_modelo');
    }

    public function color()
    {
        return $this->belongsTo(ColorModelo::class, 'id_color', 'id_colorM');
    }

    public function lote()
    {
        return $this->belongsTo(Lote::class, 'id_lote', 'id_lote');
    }

    public function tipoStock()
    {
        return $this->belongsTo(TipoStock::class, 'id_tipoStock', 'id_tipoStock');
    }

    public function pedido()
{
    return $this->belongsTo(Pedidos::class, 'id_pedido', 'id_pedido');
}

}
