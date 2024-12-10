<?php

namespace App\Console;
use Illuminate\Support\Facades\Http;
use SoapClient;
use App\Models\Historiales;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            
            try {
                $wsdl = env('ENDPOINT_SERVICE_GET');
                $client = new SoapClient($wsdl, [
                    'trace' => 1, // Para habilitar el seguimiento de solicitudes y respuestas
                    'exceptions' => true, // Lanzar excepciones en caso de error
                ]);
            
                // Llamar al método SOAP
                $response_som = $client->get_historial_somnolencia();
                $historiales = $response_som->get_historial_somnolenciaResult->HistorialSomnolencia;
            
                // Obtener los registros actuales de la base de datos
                $registrosExistentes = Historiales::all();
            
                // Crear un arreglo con los IDs de los registros que están en el web service
                $idsWebService = array_map(function($historial) {
                    return $historial->id;
                }, $historiales);
            
                // Verificar y actualizar o crear nuevos registros
                foreach ($historiales as $historial) {
                    $registroExistente = Historiales::where('id_registro', $historial->id)->first();
            
                    if (!$registroExistente) {
                        // Crear un nuevo registro si no existe
                        $registro = new Historiales();
                        $registro->id_registro = $historial->id;
                        $registro->dni = $historial->dni;
                        $registro->fecha_detencion = $historial->fecha_detencion;
                        $registro->hora_detencion = $historial->hora_detencion;
                        $registro->motivo = $historial->motivo;
                        $registro->cantidad_veces = $historial->cantidad_veces;
                        $registro->save();
                    } else {
                        // Actualizar el registro si existe pero ha cambiado algún campo
                        if ($registroExistente->fecha_detencion != $historial->fecha_detencion ||
                            $registroExistente->hora_detencion != $historial->hora_detencion ||
                            $registroExistente->motivo != $historial->motivo ||
                            $registroExistente->cantidad_veces != $historial->cantidad_veces) {
            
                            $registroExistente->fecha_detencion = $historial->fecha_detencion;
                            $registroExistente->hora_detencion = $historial->hora_detencion;
                            $registroExistente->motivo = $historial->motivo;
                            $registroExistente->cantidad_veces = $historial->cantidad_veces;
                            $registroExistente->save();
                        }
                    }
                }
            
                // Eliminar registros que ya no están en el web service
                foreach ($registrosExistentes as $registroExistente) {
                    if (!in_array($registroExistente->id_registro, $idsWebService)) {
                        // Eliminar los registros que no existen en la respuesta del web service
                        $registroExistente->delete();
                    }
                }
            } catch (\Exception $e) {
                \Log::error('Error al procesar historial de somnolencia: ' . $e->getMessage());
            }
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
