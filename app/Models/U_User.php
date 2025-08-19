<?php

namespace App\Models;

use App\Enums\UserStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class U_user extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // protected $primaryKey = 'nro_documento';
    protected $table = "u_users";
    protected $fillable = [
        'genero',
        'nombre',
        'apellidos',
        'foto',
        'fecha_nacimiento',
        'direccion',
        'nro_documento',
        'celular',
        'email',
        'password',
        'estado',
    ];
    protected $casts = [
        'fecha_nacimiento' => 'date',
        'estado' => UserStatus::class,
    ];
}
