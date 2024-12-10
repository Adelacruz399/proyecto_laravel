@extends('layouts.app')
@section('content')
<div class="w-100" style="padding:20px;">
    <div class="d-flex" style="gap:15px;">
        <h3>Historial de Somnolencia en pacientes</h3>
        <div class="d-flex" style="gap:10px;">
            <a onclick="exportToExcel()" class="btn btn-success">Exportar Excel</a>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#estadisticas">Ver Gráficos Estadísticos</button>
        </div>
    </div><br>
    <div>
        <table id="miTabla" class="display nowrap">
            <thead>
                <tr>
                    <th>Nombres</th>
                    <th>Apellidos</th>
                    <th>DNI</th>
                    <th>Diagnóstico</th>
                    <th>Fecha Detección</th>
                    <th>Motivo</th>
                    <th>Cant. Veces</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($historiales as $historial)
                <tr>
                    <td>{{$historial->user->name}}</td>
                    <td>{{$historial->user->apellidos}}</td>
                    <td>{{$historial->dni}}</td>
                    <td>{{$historial->user->diagnostico}}</td>
                    <td>{{date("d/m/Y", strtotime($historial->fecha_detencion))}} {{$historial->hora_detencion}}</td>
                    <td>{{$historial->motivo}}</td>
                    <td>{{$historial->cantidad_veces}}</td>
                    <td>
                        <div class="d-flex" style="gap:10px;">
                            <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editarMotivo{{$historial->id}}">Editar motivo</button>
                            <form method="post" action="{{url('analisis/'.$historial->id)}}" class="d-inline">
                                @csrf
                                {{method_field('DELETE')}}
                                <input type="submit" onclick="return confirm('Quieres eliminar este hsitorial?')" value="Eliminar historial" class="btn btn-danger">
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@foreach($historiales as $historial)
<!-- Modal -->
<div class="modal fade" id="editarMotivo{{$historial->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Seleccione el motivo a editar</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="POST" action="{{url('analisis/'.$historial->id)}}" enctype="multipart/form-data">
          @csrf
          {{method_field('PATCH')}}
          <div class="modal-body">
            <select class="form-select" name="motivo">
                <option>-- Seleccione motivo --</option>
                <option value="Cabeceo">Cabeceo</option>
                <option value="Ojos cerrados">Ojos cerrados</option>
                <option value="Frote de ojos">Frote de ojos</option>
                <option value="Bostezo">Bostezo</option>
            </select>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-success">Editar motivo</button>
          </div>
      </form>
    </div>
  </div>
</div>
@endforeach
<!-- Modal -->
<div class="modal fade" id="estadisticas" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Gráficos estadísticos</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
        <div class="modal-body">
            <div>
                <!-- Gráfico de barras para los motivos -->
                <canvas id="motivosChart" width="400" height="200"></canvas>
            </div>
            
            <!-- Línea de separación -->
            <hr style="border: 1px solid #777; margin: 20px 0;">
            
            <div class="text-center">
                <!-- Gráfico de torta para los diagnósticos -->
                <canvas id="diagnosticosChart" width="300"></canvas>
            </div>
        </div>
    </div>
  </div>
</div>
<script src="https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.5/xlsx.full.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
<script>
    $(document).ready(function() {
        $('#miTabla').DataTable({
            responsive: true,
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            }
        });
    });
</script>
<script>
    function exportToExcel() {
        // Obtener datos de la tabla
        var table = document.getElementById("miTabla");
        var rows = table.querySelectorAll("tbody tr");
        var headers = table.querySelectorAll("thead th:not(:last-child)"); // Excluir la última columna
        var data = [];

        // Agregar encabezados
        var headerRow = [];
        headers.forEach(function(header) {
            headerRow.push(header.innerText);
        });
        data.push(headerRow);

        // Iterar sobre las filas y excluir la última columna
        rows.forEach(function(row) {
            var rowData = [];
            var cells = row.querySelectorAll("td:not(:last-child)");

            cells.forEach(function(cell) {
                rowData.push(cell.innerText);
            });

            data.push(rowData);
        });

        // Crear libro de Excel
        var ws = XLSX.utils.aoa_to_sheet(data);
        var wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, "Hoja1");

        // Descargar el archivo Excel
        XLSX.writeFile(wb, "lista_historial_{{date('d-m-Y')}}.xlsx");
    }
</script>
<script>
    // Datos para el gráfico de barras de motivos
    var motivosData = @json($motivos_count);
    var motivosLabels = Object.keys(motivosData);
    var motivosValues = Object.values(motivosData);
    
    // Calculando el porcentaje para cada valor
    var totalMotivos = motivosValues.reduce((a, b) => a + b, 0);
    var motivosPercentages = motivosValues.map(value => (value / totalMotivos * 100).toFixed(2));
    
    var ctxMotivos = document.getElementById('motivosChart').getContext('2d');
    var motivosChart = new Chart(ctxMotivos, {
        type: 'bar',
        data: {
            labels: motivosLabels,
            datasets: [{
                label: 'Cantidad de veces por motivo',
                data: motivosValues,
                backgroundColor: 'rgba(54, 162, 235, 0.2)', // Color de las barras
                borderColor: 'rgba(54, 162, 235, 1)', // Color del borde de las barras
                borderWidth: 1,
                hoverBackgroundColor: 'rgba(54, 162, 235, 0.4)', // Color al pasar el mouse
                hoverBorderColor: 'rgba(54, 162, 235, 1)' // Color al pasar el mouse en el borde
            }]
        },
        options: {
            responsive: true, // El gráfico es responsive
            plugins: {
                legend: {
                    position: 'top', // Posición de la leyenda
                    labels: {
                        font: {
                            size: 14, // Tamaño de la fuente de la leyenda
                            family: 'Arial, sans-serif' // Fuente de la leyenda
                        }
                    }
                },
                title: {
                    display: true, // Muestra el título
                    text: 'Frecuencia de Motivos de Somnolencia', // Título del gráfico
                    font: {
                        size: 18, // Tamaño del título
                        weight: 'bold', // Peso de la fuente del título
                        family: 'Arial, sans-serif' // Fuente del título
                    },
                    padding: {
                        top: 10, // Espaciado superior
                        bottom: 30 // Espaciado inferior
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            // Personaliza el texto del tooltip
                            return tooltipItem.label + ': ' + tooltipItem.raw + ' veces';
                        }
                    }
                },
                datalabels: {
                    display: true, // Muestra las etiquetas de datos
                    color: '#000', // Color del texto de las etiquetas
                    font: {
                        weight: 'bold', // Peso de la fuente
                        size: 14 // Tamaño de la fuente
                    },
                    formatter: function(value, context) {
                        // Muestra el porcentaje sobre cada barra
                        var index = context.dataIndex;
                        var percentage = motivosPercentages[index]; // Aquí se usa la variable 'percentage'
                        return percentage + '%'; // Muestra el porcentaje
                    }
                }
            },
            scales: {
                x: {
                    beginAtZero: true, // Comienza desde cero en el eje X
                    ticks: {
                        font: {
                            size: 12, // Tamaño de la fuente de las etiquetas del eje X
                        }
                    }
                },
                y: {
                    beginAtZero: true, // Comienza desde cero en el eje Y
                    ticks: {
                        font: {
                            size: 12, // Tamaño de la fuente de las etiquetas del eje Y
                        }
                    }
                }
            },
            barThickness: 30, // Grosor de las barras
            layout: {
                padding: {
                    left: 10,
                    right: 10,
                    top: 10,
                    bottom: 10
                }
            }
        },
        plugins: [ChartDataLabels] // Asegúrate de incluir el plugin datalabels
    });



    // Datos para el gráfico de torta de diagnósticos
    var diagnosticosData = @json($diagnosticos_count);
    var diagnosticosLabels = Object.keys(diagnosticosData);
    var diagnosticosValues = Object.values(diagnosticosData);
    
    // Calculando el porcentaje para cada valor
    var total = diagnosticosValues.reduce((a, b) => a + b, 0);
    var diagnosticosPercentages = diagnosticosValues.map(value => (value / total * 100).toFixed(2));
    
    var ctxDiagnosticos = document.getElementById('diagnosticosChart').getContext('2d');
    var diagnosticosChart = new Chart(ctxDiagnosticos, {
        type: 'pie',
        data: {
            labels: diagnosticosLabels,
            datasets: [{
                data: diagnosticosValues,
                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#FF5733', '#C70039'], // Puedes agregar más colores si lo deseas
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true, // Hace que el gráfico se ajuste al tamaño de su contenedor
            plugins: {
                legend: {
                    position: 'top', // Posición de la leyenda
                    labels: {
                        font: {
                            size: 14, // Tamaño de la fuente de la leyenda
                            family: 'Arial, sans-serif' // Fuente de la leyenda
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            // Personaliza el texto del tooltip
                            return tooltipItem.label + ': ' + tooltipItem.raw + ' pacientes';
                        }
                    }
                },
                title: {
                    display: true, // Muestra el título
                    text: 'Distribución de Diagnósticos', // Título del gráfico
                    font: {
                        size: 18, // Tamaño del título
                        weight: 'bold', // Peso de la fuente del título
                        family: 'Arial, sans-serif' // Fuente del título
                    },
                    padding: {
                        top: 10, // Espaciado superior del título
                        bottom: 30 // Espaciado inferior del título
                    }
                },
                datalabels: {
                    display: true, // Muestra las etiquetas de datos
                    color: '#ffffff', // Color del texto de las etiquetas
                    font: {
                        weight: 'bold', // Peso de la fuente
                        size: 14 // Tamaño de la fuente
                    },
                    formatter: function(value, context) {
                        // Muestra el nombre y el porcentaje sobre cada sección
                        var index = context.dataIndex;
                        var percentage = diagnosticosPercentages[index];
                        return diagnosticosLabels[index] + ': ' + percentage + '%'; // Muestra el nombre y el porcentaje
                    }
                }
            },
            cutout: 0, // Deja la torta completa, puedes cambiarlo si deseas agregar un agujero en el centro
            borderWidth: 2, // Ancho del borde de cada sección
            borderColor: '#ffffff', // Color del borde de las secciones
        },
        plugins: [ChartDataLabels] // Asegúrate de incluir el plugin datalabels
    });



</script>
    @if(session('success'))
        <script>
            toastr.success("{{ session('success') }}");
        </script>
    @endif
    @if(session('denied'))
        <script>
            toastr.error("{{ session('denied') }}");
        </script>
    @endif
@endsection