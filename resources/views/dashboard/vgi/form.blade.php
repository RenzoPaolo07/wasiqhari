@extends('layouts.dashboard')

@section('title', 'Historia Clínica Geriátrica - VGI')

@section('content')
<div class="dashboard-container">
    <div class="dashboard-header">
        <div class="header-content">
            <h1><i class="fas fa-file-medical"></i> Valoración Geriátrica Integral (VGI)</h1>
            <p>Paciente: <strong>{{ $adulto->nombres }} {{ $adulto->apellidos }}</strong> | Edad: {{ $adulto->edad }} años</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('adultos') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Volver</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="vgi-container" style="background: white; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); overflow: hidden;">
        
        <div class="vgi-tabs">
            <button class="vgi-tab active" onclick="openTab(event, 'tab-social')">1. Social y Datos</button>
            <button class="vgi-tab" onclick="openTab(event, 'tab-clinica')">2. Clínica</button>
            <button class="vgi-tab" onclick="openTab(event, 'tab-funcional')">3. Funcional</button>
            <button class="vgi-tab" onclick="openTab(event, 'tab-mental')">4. Mental</button>
            <button class="vgi-tab" onclick="openTab(event, 'tab-fisica')">5. Física/Nutricional</button>
        </div>

        <form action="{{ route('adultos.vgi.store', $adulto->id) }}" method="POST" class="p-4">
            @csrf
            
            <div id="tab-social" class="vgi-tab-content" style="display: block;">
                <h4 class="mb-3 text-primary"><i class="fas fa-users"></i> I. Datos Sociodemográficos y Valoración Social</h4>
                
                <div class="form-grid">
                    <div class="form-group"><label>DNI</label><input type="text" value="{{ $adulto->dni }}" readonly style="background: #f0f0f0;"></div>
                    <div class="form-group"><label>Dirección</label><input type="text" value="{{ $adulto->direccion }}" readonly style="background: #f0f0f0;"></div>
                    <div class="form-group"><label>Teléfono</label><input type="text" value="{{ $adulto->telefono }}" readonly style="background: #f0f0f0;"></div>
                    
                    <div class="form-group"><label>Nombre del Cuidador</label><input type="text" name="nombre_cuidador" value="{{ $vgi->nombre_cuidador ?? '' }}"></div>
                    <div class="form-group"><label>Parentesco</label>
                        <select name="parentesco_cuidador">
                            <option value="">Seleccione...</option>
                            <option value="Hijo/a" {{ ($vgi->parentesco_cuidador ?? '') == 'Hijo/a' ? 'selected' : '' }}>Hijo/a</option>
                            <option value="Esposo/a" {{ ($vgi->parentesco_cuidador ?? '') == 'Esposo/a' ? 'selected' : '' }}>Esposo/a</option>
                            <option value="Nieto/a" {{ ($vgi->parentesco_cuidador ?? '') == 'Nieto/a' ? 'selected' : '' }}>Nieto/a</option>
                            <option value="Vecino/a" {{ ($vgi->parentesco_cuidador ?? '') == 'Vecino/a' ? 'selected' : '' }}>Vecino/a</option>
                        </select>
                    </div>
                    <div class="form-group"><label>DNI Cuidador</label><input type="text" name="dni_cuidador" value="{{ $vgi->dni_cuidador ?? '' }}"></div>
                </div>

                <hr class="my-4">
                <h5 class="text-secondary">Escala Socio-Familiar de Gijón (Riesgo Social)</h5>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label>Situación Familiar</label>
                        <select name="gijon_familiar" class="form-control">
                            <option value="1">Vive con familia, sin dependencia</option>
                            <option value="2">Vive con cónyuge de similar edad</option>
                            <option value="3">Vive con familia, presenta dependencia</option>
                            <option value="4">Vive solo, hijos próximos</option>
                            <option value="5">Vive solo, sin hijos o lejanos</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Situación Económica</label>
                        <select name="gijon_economica" class="form-control">
                            <option value="1">Más de 1.5 sueldos mínimos</option>
                            <option value="3">Sueldo mínimo vital</option>
                            <option value="5">Sin pensión ni ingresos</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Vivienda</label>
                        <select name="gijon_vivienda" class="form-control">
                            <option value="1">Adecuada</option>
                            <option value="3">Barreras arquitectónicas</option>
                            <option value="5">Mala conservación / Inadecuada</option>
                        </select>
                    </div>
                </div>
            </div>

            <div id="tab-clinica" class="vgi-tab-content">
                <h4 class="mb-3 text-primary"><i class="fas fa-notes-medical"></i> II. Valoración Clínica</h4>
                
                <h5 class="text-secondary">Antropometría</h5>
                <div class="form-grid mb-4">
                    <div class="form-group"><label>Peso (kg)</label><input type="number" step="0.1" name="peso" value="{{ $vgi->peso ?? '' }}"></div>
                    <div class="form-group"><label>Talla (m)</label><input type="number" step="0.01" name="talla" value="{{ $vgi->talla ?? '' }}"></div>
                    <div class="form-group"><label>IMC</label><input type="number" step="0.1" name="imc" value="{{ $vgi->imc ?? '' }}" placeholder="Auto"></div>
                    <div class="form-group"><label>P. Abdominal (cm)</label><input type="number" name="perimetro_abdominal" value="{{ $vgi->perimetro_abdominal ?? '' }}"></div>
                    <div class="form-group"><label>P. Pantorrilla (cm)</label><input type="number" name="perimetro_pantorrilla" value="{{ $vgi->perimetro_pantorrilla ?? '' }}"></div>
                </div>

                <h5 class="text-secondary">Comorbilidades</h5>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 10px; margin-bottom: 20px;">
                    <label><input type="checkbox" name="tiene_hta" value="1" {{ ($vgi->tiene_hta ?? 0) ? 'checked' : '' }}> Hipertensión</label>
                    <label><input type="checkbox" name="tiene_diabetes" value="1" {{ ($vgi->tiene_diabetes ?? 0) ? 'checked' : '' }}> Diabetes</label>
                    <label><input type="checkbox" name="tiene_epoc" value="1" {{ ($vgi->tiene_epoc ?? 0) ? 'checked' : '' }}> EPOC / Asma</label>
                    <label><input type="checkbox" name="tiene_icc" value="1" {{ ($vgi->tiene_icc ?? 0) ? 'checked' : '' }}> Insuficiencia Card.</label>
                    <label><input type="checkbox" name="tiene_demencia" value="1" {{ ($vgi->tiene_demencia ?? 0) ? 'checked' : '' }}> Demencia</label>
                    <label><input type="checkbox" name="tiene_artrosis" value="1" {{ ($vgi->tiene_artrosis ?? 0) ? 'checked' : '' }}> Artrosis</label>
                    <label><input type="checkbox" name="tiene_audicion_baja" value="1" {{ ($vgi->tiene_audicion_baja ?? 0) ? 'checked' : '' }}> Hipoacusia</label>
                    <label><input type="checkbox" name="tiene_vision_baja" value="1" {{ ($vgi->tiene_vision_baja ?? 0) ? 'checked' : '' }}> Disminución Visual</label>
                    <label><input type="checkbox" name="caidas_recientes" value="1" {{ ($vgi->caidas_recientes ?? 0) ? 'checked' : '' }}> Caídas (>2 último año)</label>
                    <label><input type="checkbox" name="tiene_incontinencia" value="1" {{ ($vgi->tiene_incontinencia ?? 0) ? 'checked' : '' }}> Incontinencia</label>
                </div>
                <div class="form-group">
                    <label>Otras Enfermedades</label>
                    <input type="text" name="otras_enfermedades" class="form-control" value="{{ $vgi->otras_enfermedades ?? '' }}">
                </div>

                <h5 class="text-secondary mt-4">Laboratorio (Últimos valores)</h5>
                <div class="form-grid">
                    <div class="form-group"><label>Hemoglobina</label><input type="text" name="lab_hemoglobina" value="{{ $vgi->lab_hemoglobina ?? '' }}"></div>
                    <div class="form-group"><label>Glucosa</label><input type="text" name="lab_glucosa" value="{{ $vgi->lab_glucosa ?? '' }}"></div>
                    <div class="form-group"><label>Creatinina</label><input type="text" name="lab_creatinina" value="{{ $vgi->lab_creatinina ?? '' }}"></div>
                    <div class="form-group"><label>Albúmina</label><input type="text" name="lab_albumina" value="{{ $vgi->lab_albumina ?? '' }}"></div>
                </div>
            </div>

            <div id="tab-funcional" class="vgi-tab-content">
                <h4 class="mb-3 text-primary"><i class="fas fa-running"></i> III. Valoración Funcional</h4>
                
                <div class="card p-3 mb-3 bg-light">
                    <h5>Índice de Barthel (Actividades Básicas)</h5>
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label>Comer</label>
                            <select name="barthel_comer" class="form-control">
                                <option value="10">Independiente (10)</option>
                                <option value="5">Necesita ayuda (5)</option>
                                <option value="0">Dependiente (0)</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label>Lavarse (Baño)</label>
                            <select name="barthel_lavarse" class="form-control">
                                <option value="5">Independiente (5)</option>
                                <option value="0">Dependiente (0)</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label>Vestirse</label>
                            <select name="barthel_vestirse" class="form-control">
                                <option value="10">Independiente (10)</option>
                                <option value="5">Necesita ayuda (5)</option>
                                <option value="0">Dependiente (0)</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label>Deambulación</label>
                            <select name="barthel_deambulacion" class="form-control">
                                <option value="15">Independiente >50m (15)</option>
                                <option value="10">Necesita ayuda (10)</option>
                                <option value="5">En silla de ruedas (5)</option>
                                <option value="0">Inmóvil (0)</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="card p-3 mb-3 bg-light">
                    <h5>Índice de Lawton & Brody (Instrumentales)</h5>
                    <div class="row">
                        <div class="col-md-4 mb-2"><label>Uso de Teléfono</label><input type="number" name="lawton_telefono" class="form-control" placeholder="1 o 0"></div>
                        <div class="col-md-4 mb-2"><label>Compras</label><input type="number" name="lawton_compras" class="form-control" placeholder="1 o 0"></div>
                        <div class="col-md-4 mb-2"><label>Preparación Comida</label><input type="number" name="lawton_comida" class="form-control" placeholder="1 o 0"></div>
                        <div class="col-md-4 mb-2"><label>Medicacion</label><input type="number" name="lawton_medicacion" class="form-control" placeholder="1 o 0"></div>
                    </div>
                </div>
            </div>

            <div id="tab-mental" class="vgi-tab-content">
                <h4 class="mb-3 text-primary"><i class="fas fa-brain"></i> IV. Valoración Mental</h4>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="card p-3 mb-3 border-warning" style="border-left: 5px solid #f1c40f;">
                            <h5>Test de Pfeiffer (SPMSQ)</h5>
                            <div class="form-group">
                                <label>Número de Errores (0-10)</label>
                                <input type="number" name="pfeiffer_errores" class="form-control" max="10" value="{{ $vgi->pfeiffer_errores ?? '' }}">
                                <small class="text-muted">0-2: Normal | 3-4: Leve | 5-7: Moderado | 8-10: Severo</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card p-3 mb-3 border-info" style="border-left: 5px solid #3498db;">
                            <h5>Escala de Depresión (Yesavage)</h5>
                            <div class="form-group">
                                <label>Puntaje Total</label>
                                <input type="number" name="yesavage_total" class="form-control" value="{{ $vgi->yesavage_total ?? '' }}">
                                <small class="text-muted">0-5: Normal | >5: Probable Depresión</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card p-3 mb-3 bg-light">
                    <h5>Minimental (MMSE)</h5>
                    <div class="row">
                        <div class="col-md-4"><label>Orientación (0-10)</label><input type="number" name="mmse_orientacion" class="form-control" max="10"></div>
                        <div class="col-md-4"><label>Memoria (0-3)</label><input type="number" name="mmse_memoria" class="form-control" max="3"></div>
                        <div class="col-md-4"><label>Total MMSE</label><input type="number" name="mmse_total" class="form-control" placeholder="Calculado"></div>
                    </div>
                    <div class="mt-2">
                         <label><input type="checkbox" name="test_reloj_anomalo" value="1" {{ ($vgi->test_reloj_anomalo ?? 0) ? 'checked' : '' }}> Test del Reloj Anómalo</label>
                    </div>
                </div>
            </div>

            <div id="tab-fisica" class="vgi-tab-content">
                <h4 class="mb-3 text-primary"><i class="fas fa-apple-alt"></i> V. Física y Nutricional</h4>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label>MNA (Nutrición - Puntaje)</label>
                        <input type="number" name="mna_puntaje" class="form-control" value="{{ $vgi->mna_puntaje ?? '' }}">
                         <small class="text-muted">< 7: Malnutrición | 8-11: Riesgo</small>
                    </div>
                    <div class="form-group">
                        <label>Velocidad de Marcha (m/s)</label>
                        <input type="number" step="0.1" name="velocidad_marcha" class="form-control" value="{{ $vgi->velocidad_marcha ?? '' }}">
                    </div>
                    <div class="form-group">
                        <label>Escala FRAIL (Fragilidad 0-5)</label>
                        <input type="number" name="frail_puntaje" class="form-control" max="5" value="{{ $vgi->frail_puntaje ?? '' }}">
                        <small class="text-muted">3-5: Frágil</small>
                    </div>
                </div>
                
                <div class="card p-3 mb-3 mt-3 bg-light">
                    <h5>SPPB (Desempeño Físico)</h5>
                    <div class="row">
                        <div class="col-md-3"><label>Balance</label><input type="number" name="sppb_balance" class="form-control"></div>
                        <div class="col-md-3"><label>Velocidad</label><input type="number" name="sppb_velocidad" class="form-control"></div>
                        <div class="col-md-3"><label>Silla</label><input type="number" name="sppb_silla" class="form-control"></div>
                        <div class="col-md-3"><label>Total</label><input type="number" name="sppb_total" class="form-control" readonly></div>
                    </div>
                </div>

                <hr>
                <div class="form-group">
                    <label><strong>Plan de Trabajo / Recomendaciones Médicas</strong></label>
                    <textarea name="plan_cuidados" class="form-control" rows="4" placeholder="Escriba aquí las indicaciones, tratamiento o derivaciones...">{{ $vgi->plan_cuidados ?? '' }}</textarea>
                </div>
            </div>

            <div class="form-actions mt-4 text-right">
                <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-save"></i> Guardar Historia Clínica</button>
            </div>

        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
    .vgi-tabs { display: flex; background: #f8f9fa; border-bottom: 1px solid #eee; overflow-x: auto; padding: 0 10px; }
    .vgi-tab { padding: 15px 20px; border: none; background: none; cursor: pointer; font-weight: 600; color: #7f8c8d; border-bottom: 3px solid transparent; transition: 0.3s; white-space: nowrap; }
    .vgi-tab:hover { background: #e9ecef; color: #333; }
    .vgi-tab.active { color: #e74c3c; border-bottom-color: #e74c3c; background: white; }
    .vgi-tab-content { display: none; animation: fadeIn 0.4s; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; }
    .card { border-radius: 8px; border: 1px solid #ddd; }
</style>
@endpush

@push('scripts')
<script>
    function openTab(evt, tabName) {
        var i, x, tablinks;
        x = document.getElementsByClassName("vgi-tab-content");
        for (i = 0; i < x.length; i++) { x[i].style.display = "none"; }
        tablinks = document.getElementsByClassName("vgi-tab");
        for (i = 0; i < tablinks.length; i++) { tablinks[i].className = tablinks[i].className.replace(" active", ""); }
        document.getElementById(tabName).style.display = "block";
        evt.currentTarget.className += " active";
    }
</script>
@endpush