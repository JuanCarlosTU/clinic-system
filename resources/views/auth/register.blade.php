<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Role Selection -->
        <div class="mt-4">
            <x-input-label for="role" :value="__('Role')" />
            <select id="role" name="role" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required onchange="togglePatientFields()">
                <option value="patient" {{ old('role') == 'patient' ? 'selected' : '' }}>Paciente</option>
                <option value="doctor" {{ old('role') == 'doctor' ? 'selected' : '' }}>Doctor</option>
                <option value="secretary" {{ old('role') == 'secretary' ? 'selected' : '' }}>Secretaria</option>
            </select>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

<
        <!-- DNI (solo para pacientes) -->
        <div class="mt-4 hidden" id="dni-field">
            <x-input-label for="dni" :value="__('DNI')" />
            <x-text-input id="dni" class="block mt-1 w-full" type="text" name="dni" :value="old('dni')" />
            <x-input-error :messages="$errors->get('dni')" class="mt-2" />
        </div>

        <!-- Birth Date (solo para pacientes) -->
        <div class="mt-4 hidden" id="birth-date-field">
            <x-input-label for="birth_date" :value="__('Fecha de Nacimiento')" />
            <x-text-input id="birth_date" class="block mt-1 w-full" type="date" name="birth_date" :value="old('birth_date')" />
            <x-input-error :messages="$errors->get('birth_date')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

         <!-- Input de imagen -->
         <div class="mb-3">
            <label for="imagen" class="form-label">Seleccionar Imagen</label>
            <input type="file" id="imagen" class="form-control" accept="image/*">
        </div>

        <!-- Contenedor de la imagen recortada -->
        <div class="mb-3">
            <label class="form-label">Previsualización</label>
            <div>
                <img id="preview" style="max-width: 100%; display: none;">
            </div>
        </div>

        <!-- Campo oculto para almacenar la imagen recortada -->
        <input type="hidden" name="imagen" id="imagen_base64">

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>

    <script>
        function togglePatientFields() {
            var role = document.getElementById("role").value;
            var dniField = document.getElementById("dni-field");
            var birthDateField = document.getElementById("birth-date-field");

            if (role === "patient") {
                dniField.classList.remove("hidden");
                birthDateField.classList.remove("hidden");
            } else {
                dniField.classList.add("hidden");
                birthDateField.classList.add("hidden");
            }
        }

        document.addEventListener("DOMContentLoaded", togglePatientFields);
    </script>
    <!-- Estilos y scripts de Cropper.js -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    let cropper;
    const imagenInput = document.getElementById("imagen");
    const preview = document.getElementById("preview");
    const imagenBase64 = document.getElementById("imagen_base64");

    imagenInput.addEventListener("change", function (event) {
        const file = event.target.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.style.display = "block";

                if (cropper) {
                    cropper.destroy();
                }

                cropper = new Cropper(preview, {
                    aspectRatio: 1, // Mantiene la imagen cuadrada 1:1
                    viewMode: 2,
                    autoCropArea: 1,
                    crop(event) {
                        const canvas = cropper.getCroppedCanvas({
                            width: 400,
                            height: 400,
                        });

                        imagenBase64.value = canvas.toDataURL("image/jpeg");
                    }
                });
            };
            reader.readAsDataURL(file);
        }
    });
});
</script>
</x-guest-layout>
