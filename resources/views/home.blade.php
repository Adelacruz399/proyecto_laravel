@extends('layouts.app')

@section('content')
<div class="w-100" style="padding:20px;">
    <div class="d-flex" style="gap:15px;">
        <h3>Pacientes</h3>
        <div class="d-flex" style="gap:10px;">
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#excel">Subir Excel</button>
            <a onclick="exportToExcel()" class="btn btn-primary">Exportar Excel</a>
            <a href="{{url('usuario/create')}}" class="btn btn-primary">Crear cita de usuario</a>
        </div>
    </div><br>
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
            @if(session('failedUsers'))
                <ul>
                    @foreach(session('failedUsers') as $user)
                        <li>{{ $user->name }} {{ $user->apellidos }} - DNI: {{ $user->dni }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
    @endif
    <div>
        <table id="miTabla" class="display nowrap">
            <thead>
                <tr>
                    <th>Nombres</th>
                    <th>Apellidos</th>
                    <th>Correo</th>
                    <th>Dni</th>
                    <th>Teléfono</th>
                    <th>Cita</th>
                    <th>Hora</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>{{$user->name}}</td>
                    <td>{{$user->apellidos}}</td>
                    <td>{{$user->email}}</td>
                    <td>{{$user->dni}}</td>
                    <td>{{$user->telefono}}</td>
                    <td>{{date("d/m/Y", strtotime($user->cita))}}</td>
                    <td>{{$user->hora}}</td>
                    <td>
                        <div class="d-flex" style="gap:10px;">
                            <a href="{{url('usuario/'.$user->id.'/edit')}}" class="btn btn-warning">Editar cita</a>
                            <form method="post" action="{{url('usuario/'.$user->id)}}" class="d-inline">
                                @csrf
                                {{method_field('DELETE')}}
                                <input type="submit" onclick="return confirm('Quieres borrar?')" value="Cancelar cita" class="btn btn-danger">
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="excel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Seleccione el archivo csv</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="POST" action="{{url('importar')}}" enctype="multipart/form-data">
          @csrf
          <div class="modal-body">
            <input class="form-control" type="file" accept=".csv" name="csv_file">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary">Importar</button>
          </div>
      </form>
    </div>
  </div>
</div>
<script src="https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.5/xlsx.full.min.js"></script>
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
        XLSX.writeFile(wb, "lista_usuarios_{{date('d-m-Y')}}.xlsx");
    }
</script>
@endsection
