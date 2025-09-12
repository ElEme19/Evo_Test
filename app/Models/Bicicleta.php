<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bicicleta extends Model
{
     protected $table = 'bicicleta';
    protected $primaryKey = 'id_fake'; // ← un campo entero autoincremental
    public $incrementing = true;
    protected $keyType = 'int';
     public $timestamps = true;

    protected $fillable = [
        'num_chasis',
        'id_modelo',
        'id_color',
        'id_lote',
        'id_tipoStock',
        'id_voltaje',
        'num_motor',
        'error_iden_produccion',
        'descripcion_general',
    ];

    protected $with = ['modelo', 'color', 'tipoStock'];

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

  
    public function Voltaje()
    {
        return $this->belongsTo(VoltajeModeloD::class, 'id_voltaje', 'id_voltaje');
    }


}
