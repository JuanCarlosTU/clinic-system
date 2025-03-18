@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Registrar Paciente</h1>

    <div class="bg-white shadow-md rounded-lg p-6">
        <form action="{{ route('patients.store') }}" method="POST">
            @csrf

            <!-- Nombre -->
            <div class="mb-4">
                <label for="name" class="block text-gray-700 font-bold">Nombre</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                    class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-300">
                @error('name') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            <!-- Email -->
            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-bold">Correo Electrónico</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                    class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-300">
                @error('email') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            <!-- DNI -->
            <div class="mb-4">
                <label for="dni" class="block text-gray-700 font-bold">DNI</label>
                <input type="text" name="dni" id="dni" value="{{ old('dni') }}" required
                    class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-300">
                @error('dni') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            <!-- Fecha de nacimiento -->
            <div class="mb-4">
                <label for="birth_date" class="block text-gray-700 font-bold">Fecha de Nacimiento</label>
                <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date') }}" required
                    class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-300">
                @error('birth_date') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            <!-- Teléfono -->
            <div class="mb-4">
                <label for="phone" class="block text-gray-700 font-bold">Teléfono</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                    class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-300">
                @error('phone') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            <!-- Dirección -->
            <div class="mb-4">
                <label for="address" class="block text-gray-700 font-bold">Dirección</label>
                <textarea name="address" id="address"
                    class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-300">{{ old('address') }}</textarea>
                @error('address') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            <!-- Botón de envío -->
            <div class="flex justify-end">
                <a href="{{ route('patients.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-700 mr-2">
                    Cancelar
                </a>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
