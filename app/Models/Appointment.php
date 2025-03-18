<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    

    protected $fillable = ['patient_id', 'doctor_id', 'appointment_date', 'status'];


    
    public function getTranslatedStatusAttribute()
    {
        switch ($this->status) {
            case 'scheduled':
                return 'Programada';
            case 'completed':
                return 'Completada';
            case 'canceled':
                return 'Cancelada';
            default:
                return ucfirst($this->status);
        }
    }

    public function patient()
{
    return $this->belongsTo(Patient::class, 'patient_id');
}

public function doctor()
{
    return $this->belongsTo(Doctor::class, 'doctor_id');
}

}
