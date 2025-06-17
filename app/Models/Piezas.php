<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Piezas extends Model
{
    use HasFactory;
    protected $table ='piezas';
    protected $primaryKey = 'id_piezas';
    protected $fillable = ['nombre_pieza', 'descripcion_pieza'];

    public $timestamps=false;
}
