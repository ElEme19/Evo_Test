<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lote extends Model
{
    use HasFactory;

    protected $table = 'lote';
    protected $primaryKey = 'id_lote';
    public $incrementing = false; // Usamos claves LOT321
    public $timestamps = false;

    protected $fillable = [
        'id_lote',
        'fecha_produccion',
       
    ];

    
    
}
