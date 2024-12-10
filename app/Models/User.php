<?php

namespace App\Models;

// Importa las clases necesarias
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Define los atributos que pueden ser asignados masivamente
    protected $fillable = [
        'name',
        'email',
        'apellidos',
        'diagnostico',
        'dni',
        'telefono',
        'cita',
        'hora',
        'link',
        'email_verified_at',
        'password',
        'remember_token',
    ];

    // Relación con el modelo Historial
    public function historiales()
    {
        return $this->hasMany(Historial::class, 'dni', 'dni');
    }
}
