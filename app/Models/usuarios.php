<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
use Illuminate\Contracts\Auth\Authenticatable;
use Carbon\Carbon;

class usuarios extends Model implements Authenticatable
{
    use HasFactory, AuthenticatableTrait;  // Asegúrate de agregar este trait

    protected $table = 'usuarios';

    protected $fillable = [
        'user_name',
        'user_pass',
        'user_tipo',
        'nombre_user',
        
    ];

    protected $hidden = [
        'user_pass',
    ];

    // El método que Laravel usará para obtener la contraseña
    public function getAuthPassword() {
        return $this->user_pass;  // ==> Cambie este 
    }

    public $timestamps = false;


     public function getTipoTextoAttribute()
{
    $tipos = [
    '0'  => 'Super Administrador',
    '1'  => 'Administrador General',
    '2'  => 'Administrador-Almacén',
    '3'  => 'Secretario-Almacén',
    '4'  => 'Almacén-1',
    '5'  => 'Almacén-Producción',
    '6'  => 'Administrador-Pedidos',
    '7'  => 'Gerente-Pedidos',
    '8'  => 'Secretario-Pedidos',
    '9'  => 'Ayudante',
    '10' => 'Administrador-Produccion',
    '11' => 'Capturista 1',
    '12' => 'Capturista 2',
    '13' => 'Ayudante (Errores)',
    '14' => 'Administrador-Reparaciones',
    '15' => 'Reparador',
    '16' => 'Administrador-R.R.H.H',
];


    return $tipos[$this->user_tipo] ?? 'Desconocido';
}

        public function getTipoDiaAttribute()
{
    $ahora = \Carbon\Carbon::now('America/Mexico_City');
    $hora = $ahora->hour;

    if ($hora >= 4 && $hora < 12) {
        $saludo = 'Buenos días ';
    } elseif ($hora >= 12 && $hora < 19) {
        $saludo = 'Buenas tardes';
    } else {
        $saludo = 'Buenas noches';
    }

    return $saludo ;
}





}
