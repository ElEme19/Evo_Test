<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PedidosPiezas extends Model
{
  
    protected $table = 'pedidos_piezas';

   
    protected $primaryKey = 'id_control';
    public $incrementing = false; 
    protected $keyType = 'string';
    

  
    protected $fillable = [
        'id_control',
        'id_pedido',
        'id_pieza',
        'cantidad',
    ];

    
    public $timestamps = false;

    public function pedido()
    {
        return $this->belongsTo(Pedidos::class, 'id_pedido', 'id_pedido');
    }

    public function pieza()
    {
        return $this->belongsTo(Pieza::class, 'id_pieza', 'id_pieza');
    }
}
