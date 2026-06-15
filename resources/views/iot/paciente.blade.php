@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h3>📋 Detalles del Paciente: {{ $paciente->nombres }} {{ $paciente->apellidos }}</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h4>Información Personal</h4>
                    <table class="table">
                        <tr><th>DNI:</th><td>{{ $paciente->dni }}</td></tr>
                        <tr><th>Teléfono:</th><td>{{ $paciente->telefono }}</td></tr>
                        <tr><th>Dirección:</th><td>{{ $paciente->direccion }}</td></tr>
                        <tr><th>Riesgo:</th><td><span class="badge bg-danger">{{ $paciente->nivel_riesgo }}</span></td></tr>
                        <tr><th>Dispositivo:</th><td><code>{{ $paciente->dispositivo_id }}</code></td></tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h4>Historial de Alertas IoT</h4>
                    <div class="table-responsive" style="max-height: 400px;">
                        <table class="table table-sm">
                            <thead>
                                <tr><th>Fecha</th><th>Tipo</th><th>Fuerza G</th></tr>
                            </thead>
                            <tbody>
                                @foreach($alertas as $alerta)
                                <tr class="table-danger">
                                    <td>{{ $alerta->created_at->format('d/m/Y H:i:s') }}</td>
                                    <td>{{ json_decode($alerta->descripcion, true)['tipo_alerta'] ?? 'N/A' }}</td>
                                    <td>{{ json_decode($alerta->descripcion, true)['fuerza_g'] ?? 0 }} G</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection