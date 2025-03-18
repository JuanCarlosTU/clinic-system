<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Perfil del Paciente') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-400 dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">{{ __('Información del Paciente') }}</h3>
                    @if (session('success'))
                    <div class="bg-green-200 text-green-800 p-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p><strong>{{ __('Nombre:') }}</strong> {{ $patient->user->name }} {{ $patient->user->last_name ?? '' }}</p>
                            <p><strong>{{ __('Correo Electrónico:') }}</strong> {{ $patient->user->email }}</p>
                            <p><strong>{{ __('DNI:') }}</strong> {{ $patient->dni }}</p>
                            <p><strong>{{ __('Fecha de Nacimiento:') }}</strong> {{ \Carbon\Carbon::parse($patient->birth_date)->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <p><strong>{{ __('Teléfono:') }}</strong> {{ $patient->phone ?? 'No registrado' }}</p>
                            <p><strong>{{ __('Dirección:') }}</strong> {{ $patient->address ?? 'No registrada' }}</p>
                            <p><strong>{{ __('Registrado el:') }}</strong> {{ $patient->created_at->format('d/m/Y H:i') }}</p>
                            <p><strong>{{ __('Última Actualización:') }}</strong> {{ $patient->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('patients.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-700">{{ __('Volver a la Lista de Pacientes') }}</a>
                        <a href="{{ route('patients.edit', $patient->id) }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700 ml-2">{{ __('Editar Paciente') }}</a>
                        <div class="mt-6">
                            
                            <a href="{{ route('patients.medical-records.create', $patient->id) }}" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-700 ml-2">{{ __('Nuevo Registro Médico') }}</a>
                        </div>
                        <div class="mt-8">
                            <h4 class="text-lg font-semibold mb-2">{{ __('Historial Médico') }}</h4>
                            @if ($patient->medicalRecords->isEmpty())
                                <p>{{ __('No hay registros médicos para este paciente.') }}</p>
                            @else
                                <ul class="space-y-4">
                                    @foreach ($patient->medicalRecords as $record)
                                        <li class="bg-gray-400 dark:bg-gray-700 shadow rounded-md p-4">
                                            <p><strong>{{ __('Fecha de la Consulta:') }}</strong>
                                                @if ($record->consultation_time)
                                                    {{ \Carbon\Carbon::parse($record->consultation_time)->format('d(d) M Y') }} y {{ \Carbon\Carbon::parse($record->consultation_time)->format('H:i') }}
                                                @else
                                                    {{ __('No registrada') }}
                                                @endif
                                            </p>                                            @if ($record->appointment)
                                                <p><strong>{{ __('Cita:') }}</strong> {{ $record->appointment->appointment_time ? \Carbon\Carbon::parse($record->appointment->appointment_time)->format('d/m/Y H:i') : 'N/A' }}</p>
                                            @endif
                                            <p><strong>{{ __('Diagnóstico:') }}</strong> {{ $record->diagnosis }}</p>
                                            @if ($record->treatment)
                                                <p><strong>{{ __('Tratamiento:') }}</strong> {{ $record->treatment }}</p>
                                            @endif
    
                                            @if ($record->prescriptions->isNotEmpty())
                                                <h5 class="font-semibold mt-2">{{ __('Prescripciones:') }}</h5>
                                                <ul>
                                                    @foreach ($record->prescriptions as $prescription)
                                                        <li>{{ $prescription->medication }} - {{ $prescription->dosage }}</li>
                                                    @endforeach
                                                </ul>
                                            @endif
    
                                            @if ($record->examResults->isNotEmpty())
                                                <h5 class="font-semibold mt-2">{{ __('Resultados de Exámenes:') }}</h5>
                                                <ul>
                                                    @foreach ($record->examResults as $result)
                                                        <li>{{ $result->exam->name }}: {{ $result->result }}</li>
                                                    @endforeach
                                                </ul>
                                            @endif
    
                                            {{-- Aquí podrías agregar un enlace para ver el registro médico en detalle --}}
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
</x-app-layout>