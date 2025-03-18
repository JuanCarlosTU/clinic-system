<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Agregado para almacenar el rol del usuario
        'photo', //Agregado para foto
        'is_administrator',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relación con la tabla de pacientes (si el usuario es un paciente)
     */
    public function patient()
    {
        return $this->hasOne(Patient::class);
    }

    /**
     * Relación con la tabla de doctores (si el usuario es un doctor)
     */
    public function doctor()
    {
        return $this->hasOne(Doctor::class);
    }

    /**
     * Método para verificar si el usuario tiene un rol específico.
     */
    public function isRole(string $role): bool
    {
        return $this->role === $role;
    }

    //public function clinics()
   // {
     //   return $this->belongsToMany(Clinic::class)->withPivot('role');
//    }
}
