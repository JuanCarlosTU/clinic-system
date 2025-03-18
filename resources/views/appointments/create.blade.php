@extends('layouts.app')

@section('content')
    <h1>{{ isset($appointment) ? 'Editar Cita' : 'Crear Cita' }}</h1>

    <form action="{{ isset($appointment) ? route('appointments.update', $appointment) : route('appointments.store') }}" method="POST">
        @csrf
        @if (isset($appointment))
            @method('PUT')
        @endif

        <div class="form-group">
            <label for="patient_id">Paciente</label>
            <select name="patient_id" id="patient_id" class="form-control">
                @foreach ($patients as $patient)
                    <option value="{{ $patient->id }}" {{ isset($appointment) && $appointment->patient_id == $patient->id ? 'selected' : '' }}>
                        {{ $patient->user->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="doctor_id">Doctor</label>
            <select name="doctor_id" id="doctor_id" class="form-control">
                @foreach ($doctors as $doctor)
                    <option value="{{ $doctor->id }}" {{ isset($appointment) && $appointment->doctor_id == $doctor->id ? 'selected' : '' }}>
                        {{ $doctor->user->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="appointment_date">Fecha y Hora</label>
            <input type="datetime-local" name="appointment_date" id="appointment_date" class="form-control" value="{{ isset($appointment) ? $appointment->appointment_date : '' }}" required>
        </div>

        <div class="form-group">
            <label for="status">Estado</label>
            <select name="status" id="status" class="form-control">
                <option value="scheduled" {{ isset($appointment) && $appointment->status == 'scheduled' ? 'selected' : '' }}>Programada</option>
                <option value="completed" {{ isset($appointment) && $appointment->status == 'completed' ? 'selected' : '' }}>Completada</option>
                <option value="canceled" {{ isset($appointment) && $appointment->status == 'canceled' ? 'selected' : '' }}>Cancelada</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">{{ isset($appointment) ? 'Actualizar' : 'Guardar' }}</button>
    </form>
@endsection