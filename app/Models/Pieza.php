<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pieza extends Model
{
    use HasFactory;

    protected $table = 'piezas';
    protected $primaryKey = 'id_pieza';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id_pieza',
        'id_modelo',
        'nombre_pieza',
        'color',
        'Unidad',
        'cantidad',
        'descripcion_general',
        'foto_pieza',
        
    ];

    public function modelo()
    {
        return $this->belongsTo(modelos_bici::class, 'id_modelo', 'id_modelo');
    }
}
