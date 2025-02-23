@extends('template')

@section('title', 'Editar categorias')

@push('css')
    
@endpush

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Editar Marcas</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item active"><a href="{{ route('presentaciones.index') }}">Presentaciones</a></li>
        <li class="breadcrumb-item active">Editar Presentaciones</li>
    </ol>

    <!-- Contenedor de formulario --->
    <div class="container w-100 border border-3 border-primary rounded p-4 mt-3">
        <form action="{{route('presentaciones.update',['presentacione'=>$presentacione])}}" method="post">
            @method('PATCH')
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" name="nombre" id="nombre" class="form-control" value="{{old('nombre',$presentacione->caracteristica->nombre)}}">
                    @error('nombre')
                        <small class="text-danger">{{'*'.$message}}</small>
                    @enderror
                </div>

                <div class="col-md-12">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea name="descripcion" id="descripcion" rows="3" class="form-control">{{old('descripcion',$presentacione->caracteristica->descripcion)}}</textarea>
                </div>

                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                    <button type="reset" class="btn btn-secondary">Reiniciar</button>
                </div>
            </div>
        </form>
    </div>

</div>
@endsection

@push('js')
    
@endpush

