@foreach ($patients as $patient)
    <tr>
        <td class="p-2 border">{{ $loop->iteration }}</td>
        <td class="p-2 border">{{ $patient->user->name }}</td>
        <td class="p-2 border">
            @if ($patient->user->photo)
                <img src="{{ asset('storage/' . $patient->user->photo) }}" width="70px" alt="Foto de {{ $patient->user->name }}" class="w-10 h-10 rounded-full">
            @else
                Sin foto
            @endif
        </td>
        <td class="p-2 border">{{ $patient->dni }}</td>
        <td class="p-2 border">{{ $patient->user->email }}</td>
        <td class="p-2 border">{{ $patient->phone }}</td>
        <td class="p-2 border">
            <a href="{{ route('patients.show', $patient->id) }}" class="text-blue-500 hover:underline">Ver</a>
            <a href="{{ route('patients.edit', $patient->id) }}" class="text-green-500 hover:underline">Editar</a>
            <form action="{{ route('patients.destroy', $patient->id) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-500 hover:underline" onclick="return confirm('¿Estás seguro de eliminar este paciente?')">Eliminar</button>
            </form>
        </td>
    </tr>
@endforeach