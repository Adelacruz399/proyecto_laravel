<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User; // Importar el modelo User
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PhoneDniLoginController extends Controller
{
    public function login(Request $request)
    {
        // Validar los datos de entrada
        $request->validate([
            'telefono' => 'required|string',
            'dni' => 'required|string',
        ]);

        // Intentar encontrar al usuario por teléfono y DNI
        $user = User::where('telefono', $request->telefono)
                     ->where('dni', $request->dni)
                     ->first();

        // Verificar si el usuario existe y si las credenciales son correctas
        if ($user) {
            // Aquí puedes agregar lógica para verificar el DNI si es necesario
            // En este caso, se asume que ya has validado que el usuario existe

            // Generar un token de acceso
            $token = $user->createToken('YourAppName')->plainTextToken;

            // Devolver el token y el usuario
            return response()->json([
                'token' => $token,
                'user' => $user,
            ]);
        }

        // Si las credenciales son incorrectas
        return response()->json(['error' => 'Unauthorized'], 401);
    }
}
