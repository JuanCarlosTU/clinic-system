<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Nuevo Registro Médico para') }} {{ $patient->user->name }}
        </h2>
    </x-slot>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // alert('Script ejecutado');  Agrega esta línea
            const consultationTimeInput = document.getElementById('consultation_time');
            if (consultationTimeInput) {
                const now = new Date();
                const year = now.getFullYear();
                const month = String(now.getMonth() + 1).padStart(2, '0');
                const day = String(now.getDate()).padStart(2, '0');
                const hour = String(now.getHours()).padStart(2, '0');
                const minute = String(now.getMinutes()).padStart(2, '0');
                const formattedDateTime = `${year}-${month}-${day}T${hour}:${minute}`;
                consultationTimeInput.value = formattedDateTime;
            }

            console.log('Timer script running'); // <-- Agrega esta línea

            // Cronómetro
            const timerElement = document.createElement('div');
            timerElement.id = 'consultation-timer';
            timerElement.textContent = 'Tiempo de Consulta: 00:00:00';
            const form = document.querySelector('#medical-record-form'); // <-- Cambia el selector aquí
            console.log('Form element:', form);
            if (form) {
                form.parentNode.insertBefore(timerElement, form);
            } else {
                console.log('Form element not found.');
            }

            let startTime;
            let intervalId;

            function startTimer() {
                startTime = new Date().getTime();
                intervalId = setInterval(updateTimer, 1000);
            }

            function updateTimer() {
                const currentTime = new Date().getTime();
                const elapsedTime = currentTime - startTime;
                const hours = Math.floor((elapsedTime % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((elapsedTime % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((elapsedTime % (1000 * 60)) / 1000);

                const formattedTime = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
                timerElement.textContent = `Tiempo de Consulta: ${formattedTime}`;
            }

            startTimer(); // Iniciar el cronómetro al cargar la página

        });
    </script>
@endpush
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-400 dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" id="medical-record-form" action="{{ route('patients.medical-records.store', $patient->id) }}">
                        @csrf

                        @if ($appointment_id)
                            <input type="hidden" name="appointment_id" value="{{ $appointment_id }}">
                        @endif

                        <div class="mb-4">
                            <label for="diagnosis" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">{{ __('Diagnóstico') }}</label>
                            <textarea id="diagnosis" name="diagnosis" rows="3" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white"></textarea>
                            @error('diagnosis') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="treatment" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">{{ __('Tratamiento (Opcional)') }}</label>
                            <textarea id="treatment" name="treatment" rows="3" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white"></textarea>
                            @error('treatment') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="observaciones" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">{{ __('Observaciones (Opcional)') }}</label>
                            <textarea id="observaciones" name="observaciones" rows="3" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white"></textarea>
                            @error('observaciones') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="consultation_time" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">{{ __('Fecha y Hora de la Consulta') }}</label>
                            <input type="datetime-local" id="consultation_time" name="consultation_time" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            @error('consultation_time') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-500 dark:bg-blue-700 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-100 uppercase tracking-widest hover:bg-blue-700 dark:hover:bg-blue-500 focus:bg-blue-700 dark:focus:bg-blue-500 active:bg-blue-900 dark:active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                {{ __('Guardar Registro Médico') }}
                            </button>
                        </div>
                    </form>

                    <div class="mt-6">
                        <a href="{{ route('patients.show', $patient->id) }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-700">{{ __('Volver al Perfil del Paciente') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
