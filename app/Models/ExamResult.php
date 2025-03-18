<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'medical_record_id',
        'exam_id',
        'result',
        'notes',
        'attachments',
    ];

    protected $casts = [
        'attachments' => 'array',
    ];

    public function medicalRecord()
    {
        return $this->belongsTo(MedicalRecord::class);
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }
}