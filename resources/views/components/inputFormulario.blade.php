@props(['tipo' => 'text', 'identificador' => '', 'nombre' => '', 'label' => '', 'valor' => '', 'mensajeError' => '', 'requerido' => false,
'clases' => '', 'deshabilitar' => false])
<div class="col">
    <label class="form-label mt-3">{{ $label }}</label>
    <input type="{{ $tipo }}" id="{{ $identificador }}" name="{{ $nombre }}" class="form-control {{$clases}}" @disabled($deshabilitar) @required( $requerido ) value="{{ $valor }}">
    {{-- <div class="invalid-tooltip">
        {{ $mensajeError }}
    </div> --}}
</div>
