<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoStock extends Model
{
    use HasFactory;

    protected $table = 'tipo_stock';
    protected $primaryKey = 'id_tipoStock';
    public $incrementing = false; 
    protected $keyType = 'string'; 
    public $timestamps = false;

    protected $fillable = [
        'id_tipoStock',
        'nombre_stock',
    ];

    public function lotes()
    {
        return $this->hasMany(Lote::class, 'tipo_stock_id', 'id_tipoStock');
    }
}

