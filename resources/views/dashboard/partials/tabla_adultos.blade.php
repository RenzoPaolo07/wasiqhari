@forelse($adultos as $adulto)
    <tr id="fila-adulto-{{ $adulto->id }}">
        <td>
            <span style="font-weight: 600; color: #2c3e50;">{{ $adulto->nombres }} {{ $adulto->apellidos }}</span>
        </td>
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
            <div style="display: flex; gap: 5px; align-items: center;">
                
                @if($adulto->telefono)
                    @php 
                        // Limpiamos el número para evitar errores en los links
                        $telefonoLimpio = preg_replace('/[^0-9]/', '', $adulto->telefono);
                    @endphp

                    <a href="tel:+51{{ $telefonoLimpio }}" 
                       class="btn-icon btn-llamar" 
                       title="Llamar a {{ $adulto->nombres }}">
                        <i class="fas fa-phone"></i>
                    </a>

                    <a href="sms:+51{{ $telefonoLimpio }}?body=Hola {{ $adulto->nombres }}, le saludamos de WasiQhari para saber cómo se encuentra." 
                       class="btn-icon btn-sms" 
                       title="Enviar SMS">
                        <i class="fas fa-comment-alt"></i>
                    </a>

                    <a href="https://wa.me/51{{ $telefonoLimpio }}?text=Hola {{ $adulto->nombres }}, le escribo de WasiQhari." 
                       target="_blank" 
                       class="btn-icon btn-whatsapp" 
                       title="Chat WhatsApp">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                    
                    <div style="width: 1px; height: 20px; background: #e0e0e0; margin: 0 5px;"></div>
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
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="7" class="text-center" style="padding: 40px; color: #999;">
            <i class="fas fa-users" style="font-size: 2rem; margin-bottom: 10px; opacity: 0.5;"></i><br>
            No hay adultos mayores registrados con esos criterios.
        </td>
    </tr>
@endforelse

<tr style="display:none;">
    <td colspan="7">
        <div id="pagination-links-hidden">{{ $adultos->links() }}</div>
    </td>
</tr>