@extends('layouts.app') {{-- Asumiendo que usas un layout base 'app.blade.php' --}}

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        Panel de Citas de Hoy
                    </div>
                    <div class="card-body">
                        @if ($todaysAppointments->isEmpty())
                            <p>No hay citas programadas para hoy.</p>
                        @else
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Paciente</th>
                                        <th>Hora</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($todaysAppointments as $appointment)
                                        <tr>
                                            <td>{{ $appointment->patient->user->name }}</td> {{-- Accedemos al nombre del paciente a través de las relaciones --}}
                                            <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('H:i') }}</td> {{-- Formateamos la hora --}}
                                            <td>{{ ucfirst($appointment->status) }}</td> {{-- Capitalizamos el estado --}}
                                            <td>
                                                <a href="{{ route('patients.medical-records.create', ['patient' => $appointment->patient_id, 'appointment_id' => $appointment->id]) }}" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-700">{{ __('Iniciar Consulta') }}</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection