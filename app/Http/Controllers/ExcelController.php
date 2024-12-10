<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CsvImport;
use App\Exports\UsersExport;
use App\Models\User;
use Auth;
class ExcelController extends Controller
{
    public function importar(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);
    
        $file = $request->file('csv_file');
        $import = new CsvImport();
        
        Excel::import($import, $file);
    
        $failedImports = $import->getFailedImports();
    
        if (!empty($failedImports)) {
            $failedUsers = User::whereIn('name', $failedImports)->get(['name', 'apellidos', 'dni']);
            return redirect()->back()->with('error', 'Algunos registros no se importaron debido a la validación. Registros no importados:')->with('failedUsers', $failedUsers);
        }
    
        return redirect()->back()->with('success', 'CSV importado exitosamente.');
    }
    public function exportar()
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }

}