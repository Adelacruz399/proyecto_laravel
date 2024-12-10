<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Historiales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Auth;
class AnalisisController extends Controller
{
    public function index(Request $request)
    {
        $historiales=Historiales::with('user')->get();
        $motivos = $historiales->pluck('motivo')->toArray();
        $diagnosticos = $historiales->pluck('user.diagnostico')->toArray();
        $cantidades = $historiales->pluck('cantidad_veces')->toArray();
        
        // Organizar los datos por motivos
        $motivos_count = array_count_values($motivos);
        
        // Organizar los datos por diagnóstico
        $diagnosticos_count = array_count_values($diagnosticos);
        return view('analisis',compact('historiales','motivos_count', 'diagnosticos_count', 'cantidades'));
    }
    public function create()
    {
    }
    public function store(Request $request)
    {
        
    }
    public function show(Servicios $servicios)
    {
    }
    public function edit($id)
    {
        
    }
    public function update(Request $request, $id)
    {
        $historial=Historiales::find($id);
        $response_update = Http::post(env('ENDPOINT_API_UPDATE').$historial->id_registro, [
            'motivo' => $request->motivo,
        ]);
        
        if ($response_update->successful()) {
            $historial->motivo=$request->motivo;
            $historial->save();
            return redirect('analisis')->with('success','Se editó el motivo correctamente');
        } else {
            return redirect('analisis')->with('denied','No se pudo actualizar el registro');
        }
    }
    public function destroy($id)
    {
        $historial=Historiales::find($id);
        $response_delete = Http::post(env('ENDPOINT_API_DELETE').$historial->id_registro);
        if ($response_delete->successful()) {
            Historiales::destroy($id);
            return redirect('analisis')->with('success','Se eliminó el registro correctamente');
        } else {
            return redirect('analisis')->with('denied','No se pudo eliminar el registro');
        }
    }
}