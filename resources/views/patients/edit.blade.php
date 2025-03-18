@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Editar Paciente</h1>

    <div class="bg-white shadow-md rounded-lg p-6">
        <form action="{{ route('patients.update', $patient->id) }}" method="POST" enctype="multipart/form-data" > 
            @csrf
            @method('PUT')

            <!-- Nombre -->
            <div class="mb-4">
                <label for="name" class="block text-gray-700 font-bold">Nombre</label>
                <input type="text" name="name" id="name" value="{{ old('name', $patient->user->name) }}" required
                    class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-300">
                @error('name') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            <!-- Email -->
            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-bold">Correo Electrónico</label>
                <input type="email" name="email" id="email" value="{{ old('email', $patient->user->email) }}" required
                    class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-300">
                @error('email') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            <!-- DNI -->
            <div class="mb-4">
                <label for="dni" class="block text-gray-700 font-bold">DNI</label>
                <input type="text" name="dni" id="dni" value="{{ old('dni', $patient->dni) }}" required
                    class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-300">
                @error('dni') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            <!-- Fecha de nacimiento -->
            <div class="mb-4">
                <label for="birth_date" class="block text-gray-700 font-bold">Fecha de Nacimiento</label>
                <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date', $patient->birth_date) }}" required
                    class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-300">
                @error('birth_date') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            <!-- Teléfono -->
            <div class="mb-4">
                <label for="phone" class="block text-gray-700 font-bold">Teléfono</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone', $patient->phone) }}"
                    class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-300">
                @error('phone') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            <!-- Dirección -->
            <div class="mb-4">
                <label for="address" class="block text-gray-700 font-bold">Dirección</label>
                <textarea name="address" id="address"
                    class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-300">{{ old('address', $patient->address) }}</textarea>
                @error('address') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

             <!-- Foto Actual -->
             <div class="mt-4">
                <label class="block text-gray-700 font-bold" for="current_photo"  >Foto Actual</label>
                <div class="mt-2">
                    @if ($patient->user->photo)
                        <img src="{{ asset('storage/' . $patient->user->photo) }}" alt="Foto del paciente" class="w-32 h-32 rounded-md">
                    @else
                        <p class="text-gray-500">No hay foto disponible.</p>
                    @endif
                </div>
            </div>
            <p class="text-red-800">Si quieres cambiar la Imagen selecciona una</p>
            <!-- Cambio de imagen -->
            <div class="mb-3">
                <label for="imagen" class="form-label">Imagen</label>
                <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*">
                <small class="text-muted">Seleccione una nueva imagen solo si desea cambiarla.</small>
            
           
            </div>
        <!-- Contenedor de la imagen recortada -->
        <div class="mb-3">
           <p class="form-label">{{ 'Previsualizacion' }}</p>
           <div>
               <img id="preview" style="max-width: 100%; display: none;">
           </div>
       </div>

       <!-- Campo oculto para almacenar la imagen recortada -->
       <input type="hidden" name="imagen_base64" id="imagen_base64">
   

   <button type="submit" class="btn btn-primary">Actualizar</button>
   <a href="{{ route('patients.index') }}" class="btn btn-secondary">Cancelar</a>
</form>
</div>
<x-cropper />

    </div>
</div>
@endsection
