@props(['tipo' => 'text', 'identificador' => '', 'label' => '', 'valor' => '', 'mensajeError' => '', 'requerido' => false])
<label class="form-label mt-3">{{ $label }}</label>
<input type="{{ $tipo }}" id="{{ $identificador }}" name="{{ $identificador }}" class="form-control" @required( $requerido ) value="{{ $valor }}">
<div class="invalid-tooltip">
    {{ $mensajeError }}
</div>
