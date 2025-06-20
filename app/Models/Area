<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;

    protected $table = 'area';
    protected $primaryKey = 'id_area';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'nombre_area',
    ];

    // Relación con el modelo Personal (si aplica)
    public function personal()
    {
        return $this->hasMany(Personal::class, 'id_area', 'id_area');
    }
}
