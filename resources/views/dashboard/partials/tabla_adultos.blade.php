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
            <a href="{{ route('adultos.credencial', $adulto->id) }}" target="_blank" class="btn-action btn-credencial" title="Descargar Credencial">
                <i class="fas fa-id-card"></i>
            </a>
            
            <button class="btn-action btn-ver" 
                    data-id="{{ $adulto->id }}" 
                    title="Ver / Editar">
                <i class="fas fa-eye"></i>
            </button>
            
            <button class="btn-action btn-eliminar" 
                    data-id="{{ $adulto->id }}" 
                    data-name="{{ $adulto->nombres }} {{ $adulto->apellidos }}" 
                    title="Eliminar">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="7" class="text-center" style="padding: 30px;">
            <div style="display: flex; flex-direction: column; align-items: center; gap: 10px; color: #95a5a6;">
                <i class="fas fa-search" style="font-size: 2rem;"></i>
                <p>No se encontraron resultados.</p>
            </div>
        </td>
    </tr>
@endforelse

<tr style="display:none;">
    <td colspan="7">
        <div id="pagination-links-hidden">
            {{ $adultos->links() }}
        </div>
    </td>
</tr>