<?php

namespace App\Http\Controllers;

use App\Models\Appointment; // Asegúrate de importar el modelo Appointment
use Illuminate\Support\Facades\Auth; // Importa Auth facade
use Carbon\Carbon; // Importa Carbon para manejo de fechas

class DoctorDashboardController extends Controller
{
    public function todaysAppointments()
    {
        // Obtener el ID del doctor logueado
        $doctorId = Auth::user()->doctor->id; // Asumiendo relación user->doctor

        // Obtener la fecha de hoy
        $today = Carbon::today();

        // Consultar las citas para hoy del doctor, incluyendo información del paciente
        $appointments = Appointment::where('doctor_id', $doctorId)
            ->whereDate('appointment_date', $today) // Filtrar por fecha de hoy
            ->with('patient.user') // Cargar la relación paciente y luego user del paciente (para obtener el nombre)
            ->orderBy('appointment_date') // Ordenar por hora de cita
            ->get();

        // Pasar las citas a la vista
        return view('doctor.dashboard', ['todaysAppointments' => $appointments]);
    }
}