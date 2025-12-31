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
                <div class="ficha-header mb-4">
                    <h4 class="m-0"><i class="fas fa-id-card"></i> I. DATOS SOCIODEMOGRÁFICOS</h4>
                </div>

                <div class="row mb-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label-sm">Fecha</label>
                        <input type="date" name="fecha_evaluacion" class="form-control" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label-sm">Hora</label>
                        <input type="time" name="hora_evaluacion" class="form-control" value="{{ \Carbon\Carbon::now()->format('H:i') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label-sm fw-bold bg-highlight">HCL (Historia Clínica)</label>
                        <input type="text" name="hcl" class="form-control border-warning" value="{{ $vgi->hcl ?? '' }}">
                    </div>
                </div>

                <hr>

                <div class="form-section">
                    <div class="row g-2 mb-2">
                        <div class="col-md-8">
                            <label class="form-label fw-bold bg-highlight">NOMBRES Y APELLIDOS:</label>
                            <input type="text" class="form-control-plaintext border-bottom" value="{{ $adulto->nombres }} {{ $adulto->apellidos }}" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">GÉNERO:</label>
                            <div class="d-flex gap-3 mt-2">
                                <label><input type="radio" name="sexo" value="F" {{ $adulto->sexo == 'F' ? 'checked' : '' }} disabled> 1) F</label>
                                <label><input type="radio" name="sexo" value="M" {{ $adulto->sexo == 'M' ? 'checked' : '' }} disabled> 2) M</label>
                            </div>
                        </div>
                    </div>

                    <div class="row g-2 mb-2">
                        <div class="col-md-2">
                            <label class="bg-highlight px-1">EDAD:</label>
                            <input type="text" class="form-control form-control-sm" value="{{ $adulto->edad }}" readonly>
                        </div>
                        <div class="col-md-3">
                            <label>DNI:</label>
                            <input type="text" class="form-control form-control-sm" value="{{ $adulto->dni }}" readonly>
                        </div>
                        <div class="col-md-3">
                            <label>Fecha Nacimiento:</label>
                            <input type="date" name="fecha_nacimiento" class="form-control form-control-sm" value="{{ $adulto->fecha_nacimiento ? \Carbon\Carbon::parse($adulto->fecha_nacimiento)->format('Y-m-d') : '' }}">
                        </div>
                        <div class="col-md-4">
                            <label>Lugar de Nacimiento:</label>
                            <input type="text" name="lugar_nacimiento" class="form-control form-control-sm border-bottom-only" value="{{ $vgi->lugar_nacimiento ?? '' }}">
                        </div>
                    </div>

                    <div class="row g-2 mb-2">
                        <div class="col-md-4">
                            <label class="bg-highlight px-1">Procedencia:</label>
                            <input type="text" name="procedencia" class="form-control form-control-sm border-bottom-only" value="{{ $vgi->procedencia ?? $adulto->distrito }}">
                        </div>
                        <div class="col-md-4">
                            <label class="bg-highlight px-1">Religión:</label>
                            <input type="text" name="religion" class="form-control form-control-sm border-bottom-only" value="{{ $vgi->religion ?? '' }}">
                        </div>
                        <div class="col-md-4">
                            <label class="bg-highlight px-1">Ocupación:</label>
                            <input type="text" name="ocupacion" class="form-control form-control-sm border-bottom-only" value="{{ $vgi->ocupacion ?? '' }}">
                        </div>
                    </div>

                    <div class="row g-2 mb-2">
                        <div class="col-md-6">
                            <label>Grupo Sanguíneo y FRH:</label>
                            <input type="text" name="grupo_sanguineo" class="form-control form-control-sm border-bottom-only" value="{{ $vgi->grupo_sanguineo ?? '' }}">
                        </div>
                        <div class="col-md-6">
                            <label>DIRECCIÓN:</label>
                            <input type="text" class="form-control form-control-sm" value="{{ $adulto->direccion }}" readonly>
                        </div>
                    </div>

                    <div class="row g-2 mb-4">
                        <div class="col-md-12">
                            <label class="bg-highlight px-1">TELÉFONOS (CASA / CELULARES):</label>
                            <input type="text" name="telefonos_referencia" class="form-control form-control-sm border-bottom-only" value="{{ $vgi->telefonos_referencia ?? $adulto->telefono }}">
                        </div>
                    </div>
                </div>

                <div class="form-section mb-4">
                    <label class="fw-bold bg-highlight px-2 mb-2">GRADO DE INSTRUCCIÓN:</label>
                    <div class="instruction-grid p-2 border rounded bg-light">
                        <label class="d-block"><input type="radio" name="grado_instruccion" value="Sin instrucción" {{ ($vgi->grado_instruccion ?? '') == 'Sin instrucción' ? 'checked' : '' }}> 1) Sin instrucción</label>
                        
                        <div class="d-flex gap-3">
                            <span>2) Primaria:</span>
                            <label><input type="radio" name="grado_instruccion" value="Primaria I" {{ ($vgi->grado_instruccion ?? '') == 'Primaria I' ? 'checked' : '' }}> I (Incompleta)</label>
                            <label><input type="radio" name="grado_instruccion" value="Primaria C" {{ ($vgi->grado_instruccion ?? '') == 'Primaria C' ? 'checked' : '' }}> C (Completa)</label>
                        </div>

                        <div class="d-flex gap-3">
                            <span>3) Secundaria:</span>
                            <label><input type="radio" name="grado_instruccion" value="Secundaria I" {{ ($vgi->grado_instruccion ?? '') == 'Secundaria I' ? 'checked' : '' }}> I</label>
                            <label><input type="radio" name="grado_instruccion" value="Secundaria C" {{ ($vgi->grado_instruccion ?? '') == 'Secundaria C' ? 'checked' : '' }}> C</label>
                        </div>

                        <div class="d-flex gap-3">
                            <span>4) Superior:</span>
                            <label><input type="radio" name="grado_instruccion" value="Superior Univ I" {{ ($vgi->grado_instruccion ?? '') == 'Superior Univ I' ? 'checked' : '' }}> Univ I</label>
                            <label><input type="radio" name="grado_instruccion" value="Superior Univ C" {{ ($vgi->grado_instruccion ?? '') == 'Superior Univ C' ? 'checked' : '' }}> Univ C</label>
                        </div>

                        <div class="d-flex gap-3">
                            <span>5) No Universitario:</span>
                            <label><input type="radio" name="grado_instruccion" value="No Univ I" {{ ($vgi->grado_instruccion ?? '') == 'No Univ I' ? 'checked' : '' }}> I</label>
                            <label><input type="radio" name="grado_instruccion" value="No Univ C" {{ ($vgi->grado_instruccion ?? '') == 'No Univ C' ? 'checked' : '' }}> C</label>
                        </div>
                        
                        <div class="mt-2">
                            <label>5) N° de años de estudio: <input type="number" name="anios_estudio" class="d-inline-block form-control form-control-sm w-auto" style="width: 80px;" value="{{ $vgi->anios_estudio ?? '' }}"> años</label>
                        </div>
                    </div>
                </div>

                <div class="form-section mb-4">
                    <label class="fw-bold bg-highlight px-2 mb-2">ESTADO CIVIL:</label>
                    <div class="d-flex flex-wrap gap-3 p-2 border rounded">
                        <label><input type="radio" name="estado_civil" value="Soltero" {{ ($vgi->estado_civil ?? '') == 'Soltero' ? 'checked' : '' }}> 1) Soltero</label>
                        <label><input type="radio" name="estado_civil" value="Casado" {{ ($vgi->estado_civil ?? '') == 'Casado' ? 'checked' : '' }}> 2) Casado</label>
                        <label><input type="radio" name="estado_civil" value="Conviviente" {{ ($vgi->estado_civil ?? '') == 'Conviviente' ? 'checked' : '' }}> 3) Conviviente</label>
                        <label><input type="radio" name="estado_civil" value="Viudo" {{ ($vgi->estado_civil ?? '') == 'Viudo' ? 'checked' : '' }}> 4) Viudo</label>
                        <label><input type="radio" name="estado_civil" value="Divorciado" {{ ($vgi->estado_civil ?? '') == 'Divorciado' ? 'checked' : '' }}> 5) Divorciado</label>
                    </div>
                </div>

                <div class="form-section mb-4 p-3 border border-primary rounded" style="background-color: #f0f8ff;">
                    <label class="fw-bold bg-highlight px-2">CUIDADOR: (En pacientes adulto mayores dependientes)</label>
                    <p class="text-muted small fst-italic mb-2">Nota: Solo llenar si la PAM es dependiente funcional o tiene deterioro cognitivo.</p>
                    
                    <div class="d-flex gap-4 mb-3">
                        <div>
                            <strong>¿Aplica?</strong>
                            <label class="ms-2"><input type="radio" name="cuidador_aplica" value="1" id="cuidador_si" onchange="toggleCuidador(true)" {{ ($vgi->cuidador_aplica ?? 0) == 1 ? 'checked' : '' }}> SI</label>
                            <label class="ms-2"><input type="radio" name="cuidador_aplica" value="0" id="cuidador_no" onchange="toggleCuidador(false)" {{ ($vgi->cuidador_aplica ?? 0) == 0 ? 'checked' : '' }}> NO</label>
                        </div>
                    </div>

                    <div id="bloque_cuidador" style="display: {{ ($vgi->cuidador_aplica ?? 0) == 1 ? 'block' : 'none' }}; border-top: 1px dashed #999; padding-top: 15px;">
                        <div class="mb-3">
                            <label class="fw-bold">1. Relación con el cuidador:</label>
                            <div class="d-flex flex-wrap gap-3">
                                <label><input type="radio" name="parentesco_cuidador" value="Esposa(o)" {{ ($vgi->parentesco_cuidador ?? '') == 'Esposa(o)' ? 'checked' : '' }}> Esposa(o)</label>
                                <label><input type="radio" name="parentesco_cuidador" value="Hijas(os)" {{ ($vgi->parentesco_cuidador ?? '') == 'Hijas(os)' ? 'checked' : '' }}> Hijas(os)</label>
                                <label><input type="radio" name="parentesco_cuidador" value="Sobrinos(as)" {{ ($vgi->parentesco_cuidador ?? '') == 'Sobrinos(as)' ? 'checked' : '' }}> Sobrinos(as)</label>
                                <label><input type="radio" name="parentesco_cuidador" value="Nietos(as)" {{ ($vgi->parentesco_cuidador ?? '') == 'Nietos(as)' ? 'checked' : '' }}> Nietos(as)</label>
                                <label><input type="radio" name="parentesco_cuidador" value="Nuera" {{ ($vgi->parentesco_cuidador ?? '') == 'Nuera' ? 'checked' : '' }}> Nuera</label>
                                <label><input type="radio" name="parentesco_cuidador" value="Otros" {{ ($vgi->parentesco_cuidador ?? '') == 'Otros' ? 'checked' : '' }}> Otros</label>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-8">
                                <label>2. Nombre del cuidador:</label>
                                <input type="text" name="nombre_cuidador" class="form-control form-control-sm border-bottom-only" value="{{ $vgi->nombre_cuidador ?? '' }}">
                            </div>
                            <div class="col-md-4">
                                <label>3. DNI:</label>
                                <input type="text" name="dni_cuidador" class="form-control form-control-sm border-bottom-only" value="{{ $vgi->dni_cuidador ?? '' }}">
                            </div>
                        </div>

                        <div class="row g-3 mt-1 align-items-center">
                            <div class="col-md-6">
                                <label>4. Sexo:</label>
                                <label class="ms-2"><input type="radio" name="cuidador_sexo" value="F" {{ ($vgi->cuidador_sexo ?? '') == 'F' ? 'checked' : '' }}> F</label>
                                <label class="ms-2"><input type="radio" name="cuidador_sexo" value="M" {{ ($vgi->cuidador_sexo ?? '') == 'M' ? 'checked' : '' }}> M</label>
                            </div>
                            <div class="col-md-6">
                                <label>Edad:</label>
                                <input type="number" name="cuidador_edad" class="d-inline-block form-control form-control-sm w-auto" style="width: 80px;" value="{{ $vgi->cuidador_edad ?? '' }}"> años
                            </div>
                        </div>
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
    /* Estilos Generales VGI */
    .vgi-container { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
    
    /* Pestañas */
    .vgi-tabs { display: flex; background: #f8f9fa; border-bottom: 2px solid #ddd; overflow-x: auto; padding: 0 10px; }
    .vgi-tab { padding: 15px 25px; border: none; background: none; cursor: pointer; font-weight: 600; color: #666; border-bottom: 4px solid transparent; transition: 0.3s; white-space: nowrap; font-size: 1rem; }
    .vgi-tab:hover { background: #e9ecef; color: #333; }
    .vgi-tab.active { color: #0056b3; border-bottom-color: #0056b3; background: white; }
    .vgi-tab-content { display: none; padding-top: 20px; animation: fadeIn 0.4s; }
    
    /* Simulación de Papel Médico */
    .bg-highlight { background-color: #ffff00; padding: 2px 5px; font-weight: bold; border-radius: 2px; display: inline-block; }
    .form-control-plaintext { padding: 0; outline: none; }
    .border-bottom-only { border: none !important; border-bottom: 1px solid #000 !important; border-radius: 0 !important; background: transparent; padding: 0 5px; }
    .border-bottom-only:focus { border-bottom: 2px solid #0056b3 !important; background: #f0f8ff; }
    
    /* Inputs Pequeños */
    .form-control-sm { font-size: 0.9rem; }
    .form-label { margin-bottom: 2px; font-weight: 500; font-size: 0.9rem; }
    
    /* Checkbox y Radios más grandes para tablet */
    input[type="radio"], input[type="checkbox"] { transform: scale(1.2); margin-right: 5px; cursor: pointer; }
    label { cursor: pointer; }

    @keyframes fadeIn { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }
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
    // NUEVA FUNCIÓN PARA EL CUIDADOR
    function toggleCuidador(show) {
        const bloque = document.getElementById('bloque_cuidador');
        if(show) {
            bloque.style.display = 'block';
            // Animación simple
            bloque.style.opacity = 0;
            setTimeout(() => bloque.style.opacity = 1, 50);
        } else {
            bloque.style.display = 'none';
        }
    }
    }
</script>
@endpush