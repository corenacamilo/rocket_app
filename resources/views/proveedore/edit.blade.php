@extends('template')

@section('title', 'Editar proveedores')

@push('css')

@endpush

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Crear Proveedores</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item "><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item "><a href="{{ route('proveedores.index') }}">Proveedores</a></li>
        <li class="breadcrumb-item active">Editar Proveedores</li>
        <li></li>
    </ol>

    <!-- Contenedor de formulario --->
    <div class="container w-100 border border-3 border-primary rounded p-4 mt-3">
        <form action="{{route('proveedores.update', ['proveedore'=>$proveedore])}}" method="post">
            @method('PATCH')
            @csrf
            <!-- Tipo de Persona -->
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="tipo_persona" class="form-label">Tipo de persona <span class="fw-bold">{{ strtoupper($proveedore->persona->tipo_persona) }}</span></label>
                </div>

                <!-- Razón Social -->

                <div class="col-md-12 mb-2" id="box-razon-social">
                    @if ($proveedore->persona->tipo_persona == 'natural')
                        <label id="label-natural" for="razon_social" class="form-label">Nombre completo</label>
                    @else
                        <label id="label-juridica" for="razon_social" class="form-label">Razón social</label>
                    @endif
                
                    <input type="text" name="razon_social" id="razon_social" class="form-control" value="{{old('razon_social', $proveedore->persona->razon_social)}}">
                    @error('razon_social')
                        <small class="text-danger">{{'*'.$message}}</small>
                    @enderror
                </div>

                <!-- Dirección -->

                <div class="col-md-8 mb-2">
                    <label for="" class="form-label">Dirección</label>
                    <input type="text" name="direccion" id="direccion" class="form-control" value="{{old('direccion', $proveedore->persona->direccion)}}">
                    @error('direccion')
                        <small class="text-danger">{{'*'.$message}}</small>
                    @enderror
                </div>

                <!-- Documento -->
                <div class="col-md-6">
                    <label for="documento_id" class="form-label">Tipo de documento</label>
                    <select class="form-select" name="documento_id" id="documento_id">
                        @foreach ($documentos as $item)
                            @if ($proveedore->persona->documento_id == $item->id)
                                <option selected value="{{$item->id}}" {{old('documento_id' == $item->id ? 'selected' : '')}}>{{$item->tipo_documento}}</option>
                            @else
                                <option value="{{$item->id}}" {{old('documento_id' == $item->id)}}>{{$item->tipo_documento}}</option>
                            @endif
                        
                        @endforeach
                    </select>
                    @error('documento_id')
                        <small class="text-danger">{{'*'.$message}}</small>
                    @enderror
                </div>

                 <!-- Número de Documento -->

                 <div class="col-md-8 mb-2">
                    <label for="" class="form-label">Número de documento</label>
                    <input type="text" name="numero_documento" id="numero_documento" class="form-control" value="{{old('numero_documento', $proveedore->persona->numero_documento)}}">
                    @error('numero_documento')
                        <small class="text-danger">{{'*'.$message}}</small>
                    @enderror
                </div>

                

                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('js')

@endpush