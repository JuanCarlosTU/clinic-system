@extends('layouts.app')

@section('content')
    <h1>Detalles de la Cita</h1>
    <p><strong>Paciente:</strong> {{ $appointment->patient->user->name }}</p>
    <p><strong>Doctor:</strong> {{ $appointment->doctor->user->name }}</p>
    <p><strong>Fecha:</strong> {{ $appointment->appointment_date }}</p>
    <p><strong>Estado:</strong> {{ $appointment->status }}</p>
    <a href="{{ route('appointments.index') }}" class="btn btn-secondary">Volver</a>
@endsection