<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Doctor;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
  public function index()
  {
      $appointments = Appointment::orderByRaw("IF(appointment_date >= NOW(), 1, 0) DESC")
          ->orderByRaw("CASE WHEN appointment_date >= NOW() THEN appointment_date END ASC")
          ->orderByRaw("CASE WHEN appointment_date < NOW() THEN appointment_date END DESC")
          ->paginate(10);

      return view('appointments.index', compact('appointments'));
  }

    public function create()
    {
        $patients = Patient::with('user')->get();
        $doctors = Doctor::with('user')->get();
        return view('appointments.create', compact('patients', 'doctors'));
    }

    public function store(Request $request)
{
    // Validamos los datos; usamos 'date' para aceptar distintos formatos válidos.
    $validated = $request->validate([
        'patient_id'       => 'required|exists:patients,id',
        'doctor_id'        => 'required|exists:doctors,id',
        'appointment_date' => 'required|date',
        'status'           => 'required|in:scheduled,completed,canceled',
    ]);

    // Convertimos la fecha recibida al formato "Y-m-d H:i:s"
    $validated['appointment_date'] = \Carbon\Carbon::parse($validated['appointment_date'])
                                                   ->format('Y-m-d H:i:s');

    // Creamos el registro utilizando los datos validados y formateados.
    Appointment::create($validated);

    return redirect()->route('appointments.index')
                     ->with('success', 'Cita creada correctamente.');
}

    public function show(Appointment $appointment)
    {
        $appointment->load(['patient.user', 'doctor.user']);
        return view('appointments.show', compact('appointment'));
    }

    public function edit(Appointment $appointment)
    {
        $patients = Patient::with('user')->get();
        $doctors = Doctor::with('user')->get();
        return view('appointments.edit', compact('appointment', 'patients', 'doctors'));
    }

    public function update(Request $request, Appointment $appointment)
{
    // Validamos los datos de entrada.
    $validated = $request->validate([
        'patient_id'       => 'required|exists:patients,id',
        'doctor_id'        => 'required|exists:doctors,id',
        'appointment_date' => 'required|date',
        'status'           => 'required|in:scheduled,completed,canceled',
    ]);

    // Formateamos la fecha para que se guarde en el formato "Y-m-d H:i:s"
    $validated['appointment_date'] = \Carbon\Carbon::parse($validated['appointment_date'])
                                                   ->format('Y-m-d H:i:s');

    // Actualizamos el registro con los datos procesados.
    $appointment->update($validated);

    return redirect()->route('appointments.index')
                     ->with('success', 'Cita actualizada correctamente.');
}


    public function destroy(Appointment $appointment)
    {
        $appointment->delete();
        return redirect()->route('appointments.index')->with('success', 'Cita eliminada correctamente.');
    }
}