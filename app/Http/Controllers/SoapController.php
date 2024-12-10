<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class SoapController extends Controller
{
    // Método para obtener datos de un usuario por DNI
    public function getUserByDni($dni)
    {
        $user = User::where('dni', $dni)->first();

        if (!$user) {
            return "Usuario con DNI $dni no encontrado.";
        }

        // Convertir el objeto usuario a JSON
        return json_encode([
            'name' => $user->name,
            'email' => $user->email,
            'apellidos' => $user->apellidos,
            'telefono' => $user->telefono,
            'diagnostico' => $user->diagnostico,
        ]);
    }

    // Método para eliminar un usuario por DNI
    public function deleteUserByDni($dni)
    {
        $user = User::where('dni', $dni)->first();

        if (!$user) {
            return "Usuario con DNI $dni no encontrado.";
        }

        $user->delete();

        return "Usuario con DNI $dni eliminado correctamente.";
    }

    // Método para devolver el archivo WSDL
    public function getWsdl()
    {
        $wsdlPath = public_path('wsdl/service.wsdl');

        if (file_exists($wsdlPath)) {
            return response()->file($wsdlPath, [
                'Content-Type' => 'application/xml',
            ]);
        } else {
            return response()->json(['error' => 'Archivo WSDL no encontrado'], 404);
        }
    }
}
