<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class VoltajeModelo extends Pivot
{
    protected $table = 'voltaje_modelo';
    public $incrementing = false;      // tu PK es varchar
    protected $primaryKey = 'id_mVoltaje';
    protected $keyType = 'string';
    public $timestamps = false;        // no tienes created_at ni updated_at

    protected $fillable = [
        'id_mVoltaje',
        'id_modelo',
        'id_voltaje',
    ];
}
