@extends('layouts.app')
@section('titulo', 'Registrar Cliente')
@section('content')
    <div class="ent">
    <h4 class="welcome" style="margin-left:10px;">Crear Usuario</h4>
    </div>
        <div class="">
<div class="mt-4" style="text-align:left;padding:20px;">
        <form  action="{{ url('/usuario') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if(count($errors)>0)
        <div class="alert alert-danger" role="danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{$error}}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <div class="d-flex justify-content-between w-100 flex-wrap">
            <div style="width: 40%;">
                <label>Nombres</label>
                <input type="text" class="form-control" name="name" required><br>
            </div>
            <div style="width: 40%;">
                <label>Apellidos</label>
                <input type="text" class="form-control" name="apellidos" required><br>
            </div>
            <div style="width: 40%;">
                <label>Correo</label>
                <input type="email" class="form-control" name="email" required><br>
            </div>
            <div style="width: 40%;">
                <label>Dni</label>
                <input type="number" class="form-control" name="dni" required><br>
            </div>
            <div style="width: 40%;">
                <label>Teléfono</label>
                <input type="tel" class="form-control" name="telefono" required><br>
            </div>
            <div style="width: 40%;">
                <label>Cita</label>
                <input type="date" class="form-control" name="cita" required><br>
            </div>
            <div style="width: 40%;">
                <label>Hora</label>
                <input type="time" class="form-control" name="hora"  required><br>
            </div>
            <div style="width: 40%;">
                <label>Link</label>
                <input type="text" class="form-control" name="link" required><br>
            </div>
        </div>
            <div class="form-group">
            <input type="submit" class="btn btn-success" style="border:none;margin-right:30px; " value="Registrar" ><br>
        </div>
    </form>

</div>
@endsection