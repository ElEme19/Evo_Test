<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class modelos_bici extends Model
{
    use HasFactory;

    
    protected $table = 'modelos';
    protected $primaryKey = 'id_modelo';
    public $incrementing = false;
    protected $keyType = 'string';
     public $timestamps = false;

    protected $fillable = [
        'id_modelo',
        'nombre_modelo',
        'foto_modelo',
    ];

    public function colores()
    {
        return $this->hasMany(ColorModelo::class, 'id_modelo', 'id_modelo');
    }

    public function piezas()
    {
        return $this->hasMany(Pieza::class, 'id_modelo', 'id_modelo');
    }
}

