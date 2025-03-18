<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\MedicalRecord;
use Hamcrest\Core\IsNot;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $query = Patient::with('user');

        // Si hay un término de búsqueda, aplicamos el filtro
        if ($request->has('search')) {
            $searchTerm = $request->get('search');
            $query->whereHas('user', function ($userQuery) use ($searchTerm) {
                $userQuery->where('name', 'like', '%' . $searchTerm . '%')
                          ->orWhere('email', 'like', '%' . $searchTerm . '%');
            })->orWhere('dni', 'like', '%' . $searchTerm . '%');
        }

        $patients = $query->paginate(10); // 10 pacientes por página

        if ($request->ajax()) {
            return response()->json([
                'html' => view('patients.partials.patients', ['patients' => $patients])->render(),
                'hasMorePages' => $patients->hasMorePages(),
            ]);
        }

        return view('patients.index', compact('patients'));
    }


    public function create()
    {
        return view('patients.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'dni' => 'required|string|unique:patients,dni',
        'birth_date' => 'required|date',
        'phone' => 'nullable|string|max:20',
        'address' => 'nullable|string',
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt('password123'), // Se puede cambiar a algo más seguro
        'role' => 'patient',
    ]);

    Patient::create([
        'user_id' => $user->id,
        'dni' => $request->dni,
        'birth_date' => $request->birth_date,
        'phone' => $request->phone,
        'address' => $request->address,
    ]);

    return redirect()->route('patients.index')->with('success', 'Paciente registrado correctamente');
}

public function show(Patient $patient)
{
    $patient->load('medicalRecords.doctor', 'medicalRecords.appointment', 'medicalRecords.prescriptions', 'medicalRecords.examResults.exam');
    return view('patients.show', compact('patient'));
}

    public function edit(Patient $patient)
{
    return view('patients.edit', compact('patient'));
}

public function update(Request $request, Patient $patient)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $patient->user_id,
        'dni' => 'required|string|unique:patients,dni,' . $patient->id,
        'birth_date' => 'required|date',
        'phone' => 'nullable|string|max:20',
        'address' => 'nullable|string',
        'imagen_base64' => 'nullable|string',
    ]);

    // Actualizar los datos del usuario
    $patient->user->update([
        'name' => $request->name,
        'email' => $request->email,
    ]);

    // Actualizar los datos del paciente
    $patient->update([
        'dni' => $request->dni,
        'birth_date' => $request->birth_date,
        'phone' => $request->phone,
        'address' => $request->address,
    ]);

    // Procesar la imagen solo si se subió una nueva
    if ($request->filled('imagen_base64')) {
        Log::info('Procesando imagen Base64');
        // Decodificar la imagen Base64
        $image = str_replace('data:image/jpeg;base64,', '', $request->imagen_base64);
        $image = str_replace(' ', '+', $image);
        $imageData = base64_decode($image);

        // Nombre del archivo basado en el ID del bien
        $imageName = 'Auth/' . $patient->user_id . '.jpg';

        // Guardar la imagen en el almacenamiento público
        Storage::disk('public')->put($imageName, $imageData);

        // Guardar la ruta en la base de datos
        $patient->user->update(['imagen' => $imageName]);

        Log::info('Imagen procesada y guardada');
    }

    session()->flash('success', 'Bien actualizado con éxito.');
    Log::info('Actualización completada y redirigiendo');

    return redirect()->route('patients.index')->with('success', 'Bien actualizado con éxito.');

}


public function destroy(Patient $patient)
{
    $user = $patient->user; // Obtener usuario asociado
    $patient->delete(); // Eliminar paciente

    if ($user) {
        $user->delete(); // Eliminar usuario solo si existe
    }

    return redirect()->route('patients.index')->with('success', 'Paciente y usuario eliminados correctamente.');
}

public function createMedicalRecord(Patient $patient, Request $request)
{
    $appointment_id = $request->query('appointment_id'); // Obtiene la ID desde la query string
    return view('patients.medical-records.create', compact('patient', 'appointment_id'));
}

public function storeMedicalRecord(Request $request, Patient $patient)
{
    $request->validate([
        'diagnosis' => 'required|string',
        'treatment' => 'nullable|string',
        'observaciones'=> 'nullable|string',
        'consultation_time' => 'nullable|date_format:Y-m-d\TH:i', // Valida el formato datetime-local
        'appointment_id' => 'nullable|exists:appointments,id', // Valida que el appointment_id exista en la tabla appointments
    ]);

    $medicalRecord = new MedicalRecord();
    $medicalRecord->patient_id = $patient->id;
    $medicalRecord->doctor_id = Auth::id();
    $medicalRecord->diagnosis = $request->diagnosis;
    $medicalRecord->treatment = $request->treatment;
    $medicalRecord->observaciones = $request->observaciones;
    $medicalRecord->consultation_time = $request->consultation_time; // Guarda la fecha y hora de la consulta
    $medicalRecord->appointment_id = $request->appointment_id; // Guarda el appointment_id
    $medicalRecord->save();
// Actualizar el estado de la cita si se proporcionó un appointment_id
if ($request->filled('appointment_id')) {
   // Log::info('Appointment ID recibido: ' . $request->appointment_id); // <-- Agrega esta línea
    $appointment = \App\Models\Appointment::find($request->appointment_id);
    // Log::info('Appointment encontrado: ', (array) $appointment->toArray()); // <-- Agrega esta línea
    if ($appointment) {
        // Log::info('Estado actual de la cita: ' . $appointment->status); // <-- Agrega esta línea
        $appointment->status = "completed";
        // Log::info('Nuevo estado de la cita: ' . $appointment->status); // <-- Agrega esta línea
        $appointment->save();
        // Log::info('Cita guardada con éxito.'); // <-- Agrega esta línea
    } else {
        Log::warning('No se encontró la cita con ID: ' . $request->appointment_id); // <-- Agrega esta línea
    }
}


    return redirect()->route('patients.show', $patient->id)->with('success', 'Registro médico creado correctamente.');
}

}
