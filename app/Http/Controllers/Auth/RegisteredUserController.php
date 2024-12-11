<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Enums\Status;
use App\Enums\Role;
use Illuminate\View\View;
use App\Enums\Departments;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Registered;

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
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'department' => ['required', Rule::enum(Departments::class)],
            'rol' => ['required', Rule::enum(Role::class)],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no puede tener más de 255 caracteres.',
            'surname.required' => 'El apellido es obligatorio.',
            'surname.string' => 'El apellido debe ser una cadena de texto.',
            'surname.max' => 'El apellido no puede tener más de 255 caracteres.',
            'department.required' => 'El departamento es obligatorio.',
            'department.enum' => 'El departamento seleccionado no es válido.',
            'rol.required' => 'El perfil son obligatorios.',
            'rol.enum' => 'El perfil seleccionados no son válidos.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.string' => 'El correo electrónico debe ser una cadena de texto.',
            'email.lowercase' => 'El correo electrónico debe estar en minúsculas.',
            'email.email' => 'El correo electrónico debe tener un formato válido.',
            'email.max' => 'El correo electrónico no puede tener más de 255 caracteres.',
            'email.unique' => 'El correo electrónico ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);


        $user = User::create([
            'name' => $request->name,
            'surname' => $request->surname,
            'department' => $request->department,
            'rol' => $request->rol,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);


        return redirect(route('user.index', absolute: false));
    }
}
