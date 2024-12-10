@extends('layouts.app')
@section('titulo', 'Registrar Cliente')
@section('content')
    <div class="ent">
    <h4 class="welcome" style="margin-left:10px;">Editar Usuario</h4>
    </div>
        <div class="">
<div class="mt-4" style="text-align:left;padding:20px;">
        <form  action="{{ url('/usuario/'.$user->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        {{ csrf_field() }}
        {{method_field('PATCH')}}
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
                <input type="text" class="form-control" name="name" value="{{isset($user->name)?$user->name:old('name')}}" required><br>
            </div>
            <div style="width: 40%;">
                <label>Apellidos</label>
                <input type="text" class="form-control" name="apellidos" value="{{isset($user->apellidos)?$user->apellidos:old('apellidos')}}" required><br>
            </div>
            <div style="width: 40%;">
                <label>Correo</label>
                <input type="email" class="form-control" name="email" value="{{isset($user->email)?$user->email:old('email')}}" required><br>
            </div>
            <div style="width: 40%;">
                <label>Dni</label>
                <input type="number" class="form-control" name="dni" value="{{isset($user->dni)?$user->dni:old('dni')}}" required><br>
            </div>
            <div style="width: 40%;">
                <label>Teléfono</label>
                <input type="tel" class="form-control" name="telefono" value="{{isset($user->telefono)?$user->telefono:old('telefono')}}" required><br>
            </div>
            <div style="width: 40%;">
                <label>Cita</label>
                <input type="date" class="form-control" name="cita" value="{{$user->cita}}" required><br>
            </div>
            <div style="width: 40%;">
                <label>Hora</label>
                <input type="time" class="form-control" name="hora" value="{{isset($user->hora)?$user->hora:old('hora')}}" required><br>
            </div>
            <div style="width: 40%;">
                <label>Link</label>
                <input type="text" class="form-control" name="link" value="{{isset($user->link)?$user->link:old('link')}}" required><br>
            </div>
        </div>
            <div class="form-group">
            <input type="submit" class="btn btn-success" style="border:none;margin-right:30px; " value="Guardar Cambios" ><br>
        </div>
    </form>

</div>
@endsection