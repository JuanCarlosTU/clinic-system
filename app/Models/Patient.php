<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'dni',
        'birth_date',
        'phone',
        'address',
        'photo',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class);
    }

    public function prescriptions()
    {
        return $this->hasManyThrough(Prescription::class, MedicalRecord::class);
    }

    public function examResults()
    {
        return $this->hasManyThrough(ExamResult::class, MedicalRecord::class);
    }
}
