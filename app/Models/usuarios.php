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
        'correo',
        'user_pass',
        'user_tipo',
        'nombre_user',
        'apellido_usuario',
    ];

    protected $hidden = [
        'user_pass',
    ];

    // El método que Laravel usará para obtener la contraseña
    public function getAuthPassword() {
        return $this->user_pass;  // ==> Cambie este 
    }

    public $timestamps = false;





}