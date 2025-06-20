<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;

    protected $table = 'area';               // Nombre real de la tabla
    protected $primaryKey = 'id_area';        // Clave primaria personalizada
    public $incrementing = false;             // ❌ No autoincrementa, se asigna manualmente
    protected $keyType = 'string';            // ✅ Es tipo string (por ejemplo: AR001)
    public $timestamps = false;               // Si no tienes created_at y updated_at

    protected $fillable = [
        'id_area',
        'nombre_area',
    ];

    // Relación: un área puede tener muchos empleados/personal
    public function personal()
    {
        return $this->hasMany(Personal::class, 'id_area', 'id_area');
    }
}
