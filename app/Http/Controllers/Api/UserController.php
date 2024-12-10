<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getUserData()
    {
        // Obtener todos los registros de la tabla `users`
        $users = User::select('name', 'email', 'apellidos', 'dni', 'telefono','diagnostico')->where('name','<>','admin')->get();

        // Retornar la lista de usuarios en formato JSON
        return response()->json($users);
    }
    public function register(Request $request)
    {
        // Validar los datos de entrada
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'apellidos' => 'required|string|max:255',
            'dni' => 'required|string|max:255|unique:users,dni',
            'telefono' => 'required|string|max:255',
            'diagnostico' => 'nullable|string|max:1000',
        ]);

        // Crear el usuario
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'apellidos' => $validatedData['apellidos'],
            'dni' => $validatedData['dni'],
            'telefono' => $validatedData['telefono'],
            'diagnostico' => $validatedData['diagnostico'],
            'password' => Hash::make('defaultpassword'), // Cambiar si deseas un campo de contraseña
        ]);

        return response()->json(['message' => 'Usuario registrado exitosamente', 'user' => $user], 201);
    }
}
