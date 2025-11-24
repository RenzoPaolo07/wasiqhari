@forelse($adultos as $adulto)
    <tr id="fila-adulto-{{ $adulto->id }}">
        <td>{{ $adulto->nombres }} {{ $adulto->apellidos }}</td>
        <td>{{ $adulto->dni ?? 'N/A' }}</td>
        <td>{{ $adulto->distrito }}</td>
        <td>
            <span class="badge badge-{{ strtolower($adulto->estado_salud) }}">
                {{ $adulto->estado_salud }}
            </span>
        </td>
        <td>
            <span class="badge badge-riesgo-{{ strtolower($adulto->nivel_riesgo) }}">
                {{ $adulto->nivel_riesgo }}
            </span>
        </td>
        <td>{{ $adulto->fecha_registro->format('d/m/Y') }}</td>
        <td>
            @if($adulto->telefono)
                <a href="https://wa.me/51{{ preg_replace('/[^0-9]/', '', $adulto->telefono) }}?text=Hola {{ $adulto->nombres }}, le escribo de WasiQhari." 
                   target="_blank" class="btn-action btn-whatsapp" title="Chat">
                    <i class="fab fa-whatsapp"></i>
                </a>
            @endif

            <a href="{{ route('adultos.credencial', $adulto->id) }}" target="_blank" class="btn-action btn-credencial" title="Credencial">
                <i class="fas fa-id-card"></i>
            </a>
            
            <a href="{{ route('adultos.evolucion', $adulto->id) }}" class="btn-action" style="color: #3498db;" title="Ver Evolución">
                <i class="fas fa-chart-line"></i>
            </a>

            <button class="btn-action btn-ver" data-id="{{ $adulto->id }}" title="Editar">
                <i class="fas fa-eye"></i>
            </button>
            
            <button class="btn-action btn-eliminar" data-id="{{ $adulto->id }}" data-name="{{ $adulto->nombres }}" title="Eliminar">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="7" class="text-center" style="padding: 20px; color: #999;">
            No hay adultos mayores registrados.
        </td>
    </tr>
@endforelse

<tr style="display:none;"><td colspan="7"><div id="pagination-links-hidden">{{ $adultos->links() }}</div></td></tr>