<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoltajeModeloD extends Model
{
    protected $table = 'voltaje';
    protected $primaryKey = 'id_voltaje';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id_voltaje',
        'tipo_voltaje',
    ];



     public function precios()
    {
        return $this->hasMany(Precio::class, 'id_voltaje', 'id_voltaje');
    }
}
