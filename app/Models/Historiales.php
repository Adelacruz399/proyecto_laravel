<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Historiales extends Model
{
    use HasFactory;

    // Define la tabla asociada al modelo (si no sigue la convención pluralizada de Laravel)
    protected $table = 'historiales';

    // Define los atributos que pueden ser asignados masivamente
    protected $fillable = [
        'id_registro',
        'dni',
        'fecha_detencion',
        'hora_detencion',
        'motivo',
        'cantidad_veces',
    ];

    // Relación con el modelo User
    public function user()
    {
        return $this->belongsTo(User::class, 'dni', 'dni');
    }
}