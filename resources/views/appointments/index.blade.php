@extends('layouts.app')

@section('content')
    <h1>Citas</h1>
    @if (session('success'))
    <div class="bg-green-200 text-green-800 p-3 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

<div class="mb-4">
    <a href="{{ route('appointments.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">Nueva Cita</a>
</div>
    <table class="table" id="appointmentsTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Paciente</th>
                <th>Doctor</th>
                <th>Fecha de Cita</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($appointments as $appointment)
    @php
        $appointmentTime = \Carbon\Carbon::parse($appointment->getOriginal('appointment_date'));
    @endphp
    <tr 
        data-appointment-timestamp="{{ $appointmentTime->timestamp }}" 
        data-status="{{ $appointment->status }}"
        data-appointment-id="{{ $appointment->id }}"
        data-patient-id="{{ $appointment->patient_id }}"
    >
        <td>{{ $appointment->id }}</td>
        <td>{{ $appointment->patient->user->name ?? 'N/A' }}</td>
        <td>{{ $appointment->doctor->user->name ?? 'N/A' }}</td>
        <td class="appointment-date">
            {{ $appointmentTime->format('d/m/Y H:i') }}
        </td>
        <td>{{ ucfirst($appointment->translated_status) }}</td>
        <td class="opciones">
            <a href="{{ route('appointments.edit', $appointment) }}" class="btn btn-sm btn-primary">Editar</a>
            <form action="{{ route('appointments.destroy', $appointment) }}" method="POST" style="display:inline-block;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro?')">Eliminar</button>
            </form>
        </td>
    </tr>
@endforeach
        </tbody>
    </table>

    <!-- Paginación -->
    <div class="d-flex justify-content-center">
        {{ $appointments->links() }}
    </div>

    <!-- Script para aplicar estilos basado en la hora local del cliente -->
    <script>
        document.addEventListener("DOMContentLoaded", function(){
            const now = new Date().getTime();
        
            document.querySelectorAll("#appointmentsTable tbody tr").forEach(function(row) {
                const status = row.getAttribute("data-status");
        
                if(status !== 'scheduled'){
                    return;
                }
        
                const appointmentTimestamp = parseInt(row.getAttribute("data-appointment-timestamp")) * 1000;
                const diff = appointmentTimestamp - now;
        
                if(diff < 0){
                    row.classList.add("table-danger");
                    const dateCell = row.querySelector(".appointment-date");
                    dateCell.innerHTML = '<i class="fas fa-exclamation-triangle"></i> ' + dateCell.innerHTML;
        
                    // Recuperar los IDs desde los atributos data
                    const appointmentId = row.getAttribute("data-appointment-id");
                    const patientId = row.getAttribute("data-patient-id");
        
                    // Construir la URL para iniciar la consulta
                    // Suponiendo que la ruta es: patients/{patient}/medical-records/create?appointment_id={appointmentId}
                    const url = `/patients/${patientId}/medical-records/create?appointment_id=${appointmentId}`;
        
                    const optionCell = row.querySelector(".opciones");
                    // Agregar el botón de "Iniciar Consulta" al inicio de la celda de opciones
                    optionCell.innerHTML = '<a class="btn btn-success btn-sm" href="' + url + '">Iniciar Consulta</a>' + optionCell.innerHTML;
                }
                else if(diff < 3600000){
                    row.classList.add("table-warning");
                }
            });
        });
        </script>
@endsection
