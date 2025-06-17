<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ColorModelo extends Model
{
    use HasFactory;

    
    
    protected $table = 'color_modelo';
    protected $primaryKey = 'id_colorM';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id_colorM',
        'id_modelo',
        'nombre_color',
    ];

    public function modelo()
    {
        return $this->belongsTo(modelos_bici::class, 'id_modelo', 'id_modelo');
    }

    public function piezas()
    {
        return $this->hasMany(Pieza::class, 'id_colorM', 'id_colorM');
    }
}
