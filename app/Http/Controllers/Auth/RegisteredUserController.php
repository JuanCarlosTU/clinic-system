<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Patient;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Str;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
{
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
        'role' => ['required', 'string', 'in:secretary,doctor,patient'], // Validación del rol
        'dni' => ['required_if:role,patient', 'string', 'max:20', 'unique:patients,dni'],
        'birth_date' => ['required_if:role,patient', 'date'],
        'imagen' => 'required|string', // Base64 string de la imagen recortada
    ]);

 $user = User::create([
    'name' => $request->name,
    'email' => $request->email,
    'password' => Hash::make($request->password),
    'role' => $request->role,
]);
    // Si el usuario es un paciente, creamos su registro en la tabla patients con DNI y fecha de nacimiento
    if ($user->role === 'patient') {
        Patient::create([
            'user_id' => $user->id,
            'dni' => $request->dni,
            'birth_date' => $request->birth_date,
            'imagen' => null, // Se actualizará luego
        ]);
    }

    event(new Registered($user));

// Decodificar la imagen Base64
$image = $request->imagen;
$image = str_replace('data:image/jpeg;base64,', '', $image);
$image = str_replace(' ', '+', $image);
$imageData = base64_decode($image);

// Crear el nombre basado en el ID
$imageName = 'Auth/' . $user->id . '.jpg';

// Guardar la imagen en el almacenamiento
Storage::disk('public')->put($imageName, $imageData);

// Actualizar el bien con la ruta de la imagen
$user->update(['photo' => $imageName]);

    Auth::login($user);

    return redirect(route('dashboard', absolute: false));
}

}
