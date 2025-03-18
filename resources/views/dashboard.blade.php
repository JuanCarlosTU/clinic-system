<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Panel de Bienvenida') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-400 dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("¡Hola, Espero que estés teniendo un excelente día!") }}
                </div>
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("¿Que necesitas hacer?") }}
                </div>
            </div>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-400 dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <a href="{{ route('patients.index') }}" class="bg-green-900 text-white px-4 py-2 rounded hover:bg-green-500">
                    {{ __("Ver Pacientes") }}
                </a>
                </div>
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <a href="{{ route('appointments.index') }}" class="bg-blue-900 text-white px-4 py-2 rounded hover:bg-blue-500">
                        {{ __("Ver Citas") }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
