<?php

namespace App\Imports;

use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\User;

class CsvImport implements ToModel, WithHeadingRow
{
    protected $failedImports = [];

    public function model(array $row)
    {
        // Ajusta los nombres de las columnas según la estructura de tu CSV
        $dni = $row['dni'];
    
        // Verifica si ya existe un usuario con el mismo DNI
        $existingUser = User::where('dni', $dni)->first();
    
        // Si no existe un usuario con el mismo DNI, crea un nuevo objeto User
        if (!$existingUser) {
            return new User([
                'name' => $row['name'],
                'apellidos' => $row['apellidos'],
                'email' => $row['email'],
                'dni' => $dni,
                'password' => Hash::make($dni), // Hasheo del DNI y almacenamiento en la columna 'password'
                'telefono' => $row['telefono'],
                'cita' => $row['cita'],
                'hora' => $row['hora'],
                'link' => $row['link'],
                // Añade más campos según sea necesario
            ]);
        }
    
        // Si ya existe un usuario con el mismo DNI, agrega el nombre a la lista de fallidos
        $this->failedImports[] = $row['name'];
    
        return null; // Devuelve null para indicar que no se debe crear el usuario.
    }

    public function getFailedImports()
    {
        return $this->failedImports;
    }
}