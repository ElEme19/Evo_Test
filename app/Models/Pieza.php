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

    protected $fillable = [
        'id_pieza',
        'id_modelo',
        'id_colorM',
        'foto_pieza',
        'descripcion_general',
    ];

    public function modelo()
    {
        return $this->belongsTo(modelos_bici::class, 'id_modelo', 'id_modelo');
    }

    public function colorModelo()
    {
        return $this->belongsTo(ColorModelo::class, 'id_colorM', 'id_colorM');
    }
}
