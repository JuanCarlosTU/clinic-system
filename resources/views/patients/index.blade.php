@extends('layouts.app')

@section('content')
<h2 class="font-semibold text-xl text-white leading-tight">
    {{ __('Lista de Pacientes') }}
</h2>
<div class="container mx-auto p-6">

    @if (session('success'))
        <div class="bg-green-200 text-green-800 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-4">
        <a href="{{ route('patients.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">Nuevo Paciente</a>
    </div>

    <div class="mb-4">
        <form id="search-form" class="flex items-center">
            <label for="search" class="mr-2 text-gray-700 dark:text-gray-300">Buscar Paciente:</label>
            <input type="text" id="search" name="search" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="Nombre, DNI, Email">
            <button type="submit" class="ml-2 bg-green-500 text-white px-4 py-2 rounded hover:bg-green-700">Buscar</button>
        </form>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6">
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-200">
                    <th class="p-2 border">#</th>
                    <th class="p-2 border">Nombre</th>
                    <th class="p-2 border">Foto</th>
                    <th class="p-2 border">Identificación</th>
                    <th class="p-2 border">Correo Electrónico</th>
                    <th class="p-2 border">Teléfono</th>
                    <th class="p-2 border">Acciones</th>
                </tr>
            </thead>
            <tbody id="patients-body">
                @include('patients.partials.patients', ['patients' => $patients])
            </tbody>
        </table>
        <div class="mt-4 text-center" id="loading-indicator" style="display: none;">
            Cargando más pacientes...
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            var page = 1;
            var hasMorePages = true;
            var searchTerms = ''; // Variable para almacenar los términos de búsqueda

            // Función para cargar más pacientes (modificada para incluir la búsqueda)
            function loadMorePatients(page) {
                $('#loading-indicator').show();
                $.ajax({
                    url: '?page=' + page,
                    type: 'get',
                    dataType: 'json',
                    data: { search: searchTerms }, // Envía los términos de búsqueda al servidor
                    success: function(response) {
                        if (response.html) {
                            $('#patients-body').append(response.html);
                            $('#loading-indicator').hide();
                            hasMorePages = response.hasMorePages;
                        } else {
                            $('#loading-indicator').hide();
                            hasMorePages = false;
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error al cargar los pacientes:', error);
                        $('#loading-indicator').hide();
                        hasMorePages = false;
                    }
                });
            }

            // Evento para el scroll infinito (se mantiene igual)
            $(window).scroll(function() {
                if (hasMorePages && $(window).scrollTop() + $(window).height() >= $(document).height() - 200) {
                    page++;
                    loadMorePatients(page);
                }
            });

            // Evento para el formulario de búsqueda
            $('#search-form').on('submit', function(e) {
                e.preventDefault(); // Evita la recarga de la página
                searchTerms = $('#search').val(); // Obtiene el valor del campo de búsqueda
                $('#patients-body').html(''); // Limpia la lista actual de pacientes
                page = 1; // Reinicia la página a 1 para la nueva búsqueda
                hasMorePages = true; // Restablece la bandera de más páginas
                loadMorePatients(page); // Carga los pacientes con los nuevos términos de búsqueda
            });
        });
    </script>
@endpush