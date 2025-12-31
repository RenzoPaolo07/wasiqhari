@extends('layouts.dashboard')

@section('title', 'Historia Clínica Geriátrica - VGI')

@section('content')
<div class="dashboard-container">
    <div class="dashboard-header mb-4">
        <div class="header-content">
            <h1 class="text-primary"><i class="fas fa-file-medical-alt"></i> Valoración Geriátrica Integral (VGI)</h1>
            <p class="text-muted">
                Paciente: <strong class="text-dark">{{ $adulto->nombres }} {{ $adulto->apellidos }}</strong> 
                <span class="mx-2">|</span> 
                Edad: <span class="badge bg-secondary">{{ $adulto->edad }} años</span>
            </p>
        </div>
        <div class="header-actions">
            <a href="{{ route('adultos') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Volver al listado
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-lg overflow-hidden">
        
        <div class="vgi-tabs-container bg-light border-bottom">
            <div class="vgi-tabs d-flex overflow-auto">
                <button class="vgi-tab active" onclick="openTab(event, 'tab-social')">
                    <i class="fas fa-id-card me-2"></i>1. Datos Sociodemográficos
                </button>
                <button class="vgi-tab" onclick="openTab(event, 'tab-clinica')">
                    <i class="fas fa-stethoscope me-2"></i>2. Clínica
                </button>
                <button class="vgi-tab" onclick="openTab(event, 'tab-funcional')">
                    <i class="fas fa-walking me-2"></i>3. Funcional
                </button>
                <button class="vgi-tab" onclick="openTab(event, 'tab-mental')">
                    <i class="fas fa-brain me-2"></i>4. Mental
                </button>
                <button class="vgi-tab" onclick="openTab(event, 'tab-fisica')">
                    <i class="fas fa-apple-alt me-2"></i>5. Física/Nutricional
                </button>
            </div>
        </div>

        <form action="{{ route('adultos.vgi.store', $adulto->id) }}" method="POST" class="p-4 bg-white">
            @csrf
            
            <div id="tab-social" class="vgi-tab-content" style="display: block;">
                
                <h5 class="text-primary border-bottom pb-2 mb-4">I. Información General de la Atención</h5>
                
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <label class="form-label small text-muted">Fecha de Evaluación</label>
                        <input type="date" name="fecha_evaluacion" class="form-control" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small text-muted">Hora</label>
                        <input type="time" name="hora_evaluacion" class="form-control" value="{{ \Carbon\Carbon::now()->format('H:i') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small text-muted font-weight-bold">N° Historia Clínica (HCL)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-primary text-white"><i class="fas fa-file-alt"></i></span>
                            <input type="text" name="hcl" class="form-control fw-bold" placeholder="Ingrese N° HCL" value="{{ $vgi->hcl ?? '' }}">
                        </div>
                    </div>
                </div>

                <h5 class="text-primary border-bottom pb-2 mb-4">II. Datos del Paciente</h5>

                <div class="bg-light p-3 rounded mb-4 border">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label small text-muted">Nombres y Apellidos</label>
                            <input type="text" class="form-control bg-white" value="{{ $adulto->nombres }} {{ $adulto->apellidos }}" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small text-muted">Género</label>
                            <div class="d-flex gap-3 align-items-center mt-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="sexo" value="F" {{ $adulto->sexo == 'F' ? 'checked' : '' }} disabled>
                                    <label class="form-check-label">Femenino</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="sexo" value="M" {{ $adulto->sexo == 'M' ? 'checked' : '' }} disabled>
                                    <label class="form-check-label">Masculino</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label small text-muted">DNI</label>
                            <input type="text" class="form-control bg-white" value="{{ $adulto->dni }}" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small text-muted">Fecha Nacimiento</label>
                            <input type="date" class="form-control bg-white" value="{{ $adulto->fecha_nacimiento ? \Carbon\Carbon::parse($adulto->fecha_nacimiento)->format('Y-m-d') : '' }}" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small text-muted">Lugar de Nacimiento</label>
                            <input type="text" name="lugar_nacimiento" class="form-control" value="{{ $vgi->lugar_nacimiento ?? '' }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small text-muted">Grupo Sanguíneo y RH</label>
                            <input type="text" name="grupo_sanguineo" class="form-control" placeholder="Ej: O+" value="{{ $vgi->grupo_sanguineo ?? '' }}">
                        </div>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="form-label small text-muted">Procedencia</label>
                        <input type="text" name="procedencia" class="form-control" value="{{ $vgi->procedencia ?? $adulto->distrito }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small text-muted">Religión</label>
                        <input type="text" name="religion" class="form-control" value="{{ $vgi->religion ?? '' }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small text-muted">Ocupación Anterior</label>
                        <input type="text" name="ocupacion" class="form-control" value="{{ $vgi->ocupacion ?? '' }}">
                    </div>
                    <div class="col-md-8">
                        <label class="form-label small text-muted">Dirección Actual</label>
                        <input type="text" class="form-control bg-white" value="{{ $adulto->direccion }}" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small text-muted">Teléfonos de Referencia</label>
                        <input type="text" name="telefonos_referencia" class="form-control" value="{{ $vgi->telefonos_referencia ?? $adulto->telefono }}">
                    </div>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-header bg-light py-2">
                                <h6 class="mb-0 text-dark"><i class="fas fa-graduation-cap text-muted"></i> Grado de Instrucción</h6>
                            </div>
                            <div class="card-body">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="grado_instruccion" value="Sin instrucción" {{ ($vgi->grado_instruccion ?? '') == 'Sin instrucción' ? 'checked' : '' }}>
                                    <label class="form-check-label">Sin instrucción</label>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-4">Primaria:</div>
                                    <div class="col-4"><label><input type="radio" name="grado_instruccion" value="Primaria I" {{ ($vgi->grado_instruccion ?? '') == 'Primaria I' ? 'checked' : '' }}> Incompleta</label></div>
                                    <div class="col-4"><label><input type="radio" name="grado_instruccion" value="Primaria C" {{ ($vgi->grado_instruccion ?? '') == 'Primaria C' ? 'checked' : '' }}> Completa</label></div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-4">Secundaria:</div>
                                    <div class="col-4"><label><input type="radio" name="grado_instruccion" value="Secundaria I" {{ ($vgi->grado_instruccion ?? '') == 'Secundaria I' ? 'checked' : '' }}> Incompleta</label></div>
                                    <div class="col-4"><label><input type="radio" name="grado_instruccion" value="Secundaria C" {{ ($vgi->grado_instruccion ?? '') == 'Secundaria C' ? 'checked' : '' }}> Completa</label></div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-4">Superior Univ:</div>
                                    <div class="col-4"><label><input type="radio" name="grado_instruccion" value="Superior Univ I" {{ ($vgi->grado_instruccion ?? '') == 'Superior Univ I' ? 'checked' : '' }}> Incompleta</label></div>
                                    <div class="col-4"><label><input type="radio" name="grado_instruccion" value="Superior Univ C" {{ ($vgi->grado_instruccion ?? '') == 'Superior Univ C' ? 'checked' : '' }}> Completa</label></div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-4">No Univ:</div>
                                    <div class="col-4"><label><input type="radio" name="grado_instruccion" value="No Univ I" {{ ($vgi->grado_instruccion ?? '') == 'No Univ I' ? 'checked' : '' }}> Incompleta</label></div>
                                    <div class="col-4"><label><input type="radio" name="grado_instruccion" value="No Univ C" {{ ($vgi->grado_instruccion ?? '') == 'No Univ C' ? 'checked' : '' }}> Completa</label></div>
                                </div>
                                <div class="mt-3 pt-2 border-top">
                                    <label class="small text-muted">Años de estudio:</label>
                                    <input type="number" name="anios_estudio" class="form-control form-control-sm d-inline-block w-25 ms-2" value="{{ $vgi->anios_estudio ?? '' }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-header bg-light py-2">
                                <h6 class="mb-0 text-dark"><i class="fas fa-rings-wedding text-muted"></i> Estado Civil</h6>
                            </div>
                            <div class="card-body">
                                <div class="d-flex flex-wrap gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="estado_civil" value="Soltero" {{ ($vgi->estado_civil ?? '') == 'Soltero' ? 'checked' : '' }}>
                                        <label class="form-check-label">Soltero/a</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="estado_civil" value="Casado" {{ ($vgi->estado_civil ?? '') == 'Casado' ? 'checked' : '' }}>
                                        <label class="form-check-label">Casado/a</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="estado_civil" value="Conviviente" {{ ($vgi->estado_civil ?? '') == 'Conviviente' ? 'checked' : '' }}>
                                        <label class="form-check-label">Conviviente</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="estado_civil" value="Viudo" {{ ($vgi->estado_civil ?? '') == 'Viudo' ? 'checked' : '' }}>
                                        <label class="form-check-label">Viudo/a</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="estado_civil" value="Divorciado" {{ ($vgi->estado_civil ?? '') == 'Divorciado' ? 'checked' : '' }}>
                                        <label class="form-check-label">Divorciado/a</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-warning mb-4">
                    <div class="card-header bg-warning bg-opacity-10 py-2 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 text-dark font-weight-bold"><i class="fas fa-hand-holding-heart text-warning"></i> Datos del Cuidador</h6>
                        <span class="badge bg-warning text-dark">Solo si aplica</span>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label me-3">¿El paciente tiene cuidador o es dependiente?</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="cuidador_aplica" value="1" id="cuidador_si" onchange="toggleCuidador(true)" {{ ($vgi->cuidador_aplica ?? 0) == 1 ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="cuidador_si">SI</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="cuidador_aplica" value="0" id="cuidador_no" onchange="toggleCuidador(false)" {{ ($vgi->cuidador_aplica ?? 0) == 0 ? 'checked' : '' }}>
                                <label class="form-check-label" for="cuidador_no">NO (Aplica)</label>
                            </div>
                        </div>

                        <div id="bloque_cuidador" style="display: {{ ($vgi->cuidador_aplica ?? 0) == 1 ? 'block' : 'none' }}; transition: all 0.3s ease;">
                            <hr class="text-muted">
                            <div class="mb-3">
                                <label class="form-label small text-muted">Relación / Parentesco</label>
                                <div class="d-flex flex-wrap gap-3">
                                    <label class="form-check-label"><input type="radio" name="parentesco_cuidador" value="Esposa(o)" {{ ($vgi->parentesco_cuidador ?? '') == 'Esposa(o)' ? 'checked' : '' }}> Esposa(o)</label>
                                    <label class="form-check-label"><input type="radio" name="parentesco_cuidador" value="Hijas(os)" {{ ($vgi->parentesco_cuidador ?? '') == 'Hijas(os)' ? 'checked' : '' }}> Hijas(os)</label>
                                    <label class="form-check-label"><input type="radio" name="parentesco_cuidador" value="Sobrinos(as)" {{ ($vgi->parentesco_cuidador ?? '') == 'Sobrinos(as)' ? 'checked' : '' }}> Sobrinos(as)</label>
                                    <label class="form-check-label"><input type="radio" name="parentesco_cuidador" value="Nietos(as)" {{ ($vgi->parentesco_cuidador ?? '') == 'Nietos(as)' ? 'checked' : '' }}> Nietos(as)</label>
                                    <label class="form-check-label"><input type="radio" name="parentesco_cuidador" value="Nuera" {{ ($vgi->parentesco_cuidador ?? '') == 'Nuera' ? 'checked' : '' }}> Nuera</label>
                                    <label class="form-check-label"><input type="radio" name="parentesco_cuidador" value="Otros" {{ ($vgi->parentesco_cuidador ?? '') == 'Otros' ? 'checked' : '' }}> Otros</label>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small text-muted">Nombre Completo del Cuidador</label>
                                    <input type="text" name="nombre_cuidador" class="form-control" value="{{ $vgi->nombre_cuidador ?? '' }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small text-muted">DNI Cuidador</label>
                                    <input type="text" name="dni_cuidador" class="form-control" value="{{ $vgi->dni_cuidador ?? '' }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label small text-muted">Edad</label>
                                    <input type="number" name="cuidador_edad" class="form-control" value="{{ $vgi->cuidador_edad ?? '' }}">
                                </div>
                                <div class="col-md-1">
                                    <label class="form-label small text-muted">Sexo</label>
                                    <select name="cuidador_sexo" class="form-select">
                                        <option value="F" {{ ($vgi->cuidador_sexo ?? '') == 'F' ? 'selected' : '' }}>F</option>
                                        <option value="M" {{ ($vgi->cuidador_sexo ?? '') == 'M' ? 'selected' : '' }}>M</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="tab-clinica" class="vgi-tab-content">
                <h4 class="mb-3 text-primary"><i class="fas fa-notes-medical"></i> II. Valoración Clínica</h4>
                
                <h5 class="text-secondary">Antropometría</h5>
                <div class="form-grid mb-4">
                    <div class="form-group"><label>Peso (kg)</label><input type="number" step="0.1" name="peso" class="form-control" value="{{ $vgi->peso ?? '' }}"></div>
                    <div class="form-group"><label>Talla (m)</label><input type="number" step="0.01" name="talla" class="form-control" value="{{ $vgi->talla ?? '' }}"></div>
                    <div class="form-group"><label>IMC</label><input type="number" step="0.1" name="imc" class="form-control" value="{{ $vgi->imc ?? '' }}" placeholder="Auto"></div>
                    <div class="form-group"><label>P. Abdominal (cm)</label><input type="number" name="perimetro_abdominal" class="form-control" value="{{ $vgi->perimetro_abdominal ?? '' }}"></div>
                    <div class="form-group"><label>P. Pantorrilla (cm)</label><input type="number" name="perimetro_pantorrilla" class="form-control" value="{{ $vgi->perimetro_pantorrilla ?? '' }}"></div>
                </div>

                <h5 class="text-secondary">Comorbilidades</h5>
                <div class="row g-3 mb-3">
                    <div class="col-md-3"><label><input type="checkbox" name="tiene_hta" value="1" {{ ($vgi->tiene_hta ?? 0) ? 'checked' : '' }}> Hipertensión</label></div>
                    <div class="col-md-3"><label><input type="checkbox" name="tiene_diabetes" value="1" {{ ($vgi->tiene_diabetes ?? 0) ? 'checked' : '' }}> Diabetes</label></div>
                    <div class="col-md-3"><label><input type="checkbox" name="tiene_epoc" value="1" {{ ($vgi->tiene_epoc ?? 0) ? 'checked' : '' }}> EPOC / Asma</label></div>
                    <div class="col-md-3"><label><input type="checkbox" name="tiene_icc" value="1" {{ ($vgi->tiene_icc ?? 0) ? 'checked' : '' }}> Insuficiencia Card.</label></div>
                    <div class="col-md-3"><label><input type="checkbox" name="tiene_demencia" value="1" {{ ($vgi->tiene_demencia ?? 0) ? 'checked' : '' }}> Demencia</label></div>
                    <div class="col-md-3"><label><input type="checkbox" name="tiene_artrosis" value="1" {{ ($vgi->tiene_artrosis ?? 0) ? 'checked' : '' }}> Artrosis</label></div>
                    <div class="col-md-3"><label><input type="checkbox" name="tiene_audicion_baja" value="1" {{ ($vgi->tiene_audicion_baja ?? 0) ? 'checked' : '' }}> Hipoacusia</label></div>
                    <div class="col-md-3"><label><input type="checkbox" name="tiene_vision_baja" value="1" {{ ($vgi->tiene_vision_baja ?? 0) ? 'checked' : '' }}> Dism. Visual</label></div>
                    <div class="col-md-3"><label><input type="checkbox" name="caidas_recientes" value="1" {{ ($vgi->caidas_recientes ?? 0) ? 'checked' : '' }}> Caídas (>2)</label></div>
                    <div class="col-md-3"><label><input type="checkbox" name="tiene_incontinencia" value="1" {{ ($vgi->tiene_incontinencia ?? 0) ? 'checked' : '' }}> Incontinencia</label></div>
                </div>
                <div class="form-group">
                    <label>Otras Enfermedades</label>
                    <input type="text" name="otras_enfermedades" class="form-control" value="{{ $vgi->otras_enfermedades ?? '' }}">
                </div>

                <h5 class="text-secondary mt-4">Laboratorio (Últimos valores)</h5>
                <div class="form-grid">
                    <div class="form-group"><label>Hemoglobina</label><input type="text" name="lab_hemoglobina" class="form-control" value="{{ $vgi->lab_hemoglobina ?? '' }}"></div>
                    <div class="form-group"><label>Glucosa</label><input type="text" name="lab_glucosa" class="form-control" value="{{ $vgi->lab_glucosa ?? '' }}"></div>
                    <div class="form-group"><label>Creatinina</label><input type="text" name="lab_creatinina" class="form-control" value="{{ $vgi->lab_creatinina ?? '' }}"></div>
                    <div class="form-group"><label>Albúmina</label><input type="text" name="lab_albumina" class="form-control" value="{{ $vgi->lab_albumina ?? '' }}"></div>
                </div>
            </div>

            <div id="tab-funcional" class="vgi-tab-content">
                <h4 class="mb-3 text-primary"><i class="fas fa-running"></i> III. Valoración Funcional</h4>
                
                <div class="card p-3 mb-3 bg-light border">
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
                            <label>Lavarse</label>
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
                                <option value="5">Silla de ruedas (5)</option>
                                <option value="0">Inmóvil (0)</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="card p-3 mb-3 bg-light border">
                    <h5>Índice de Lawton & Brody (Instrumentales)</h5>
                    <div class="row">
                        <div class="col-md-3 mb-2"><label>Teléfono</label><input type="number" name="lawton_telefono" class="form-control" placeholder="1 o 0"></div>
                        <div class="col-md-3 mb-2"><label>Compras</label><input type="number" name="lawton_compras" class="form-control" placeholder="1 o 0"></div>
                        <div class="col-md-3 mb-2"><label>Comida</label><input type="number" name="lawton_comida" class="form-control" placeholder="1 o 0"></div>
                        <div class="col-md-3 mb-2"><label>Medicación</label><input type="number" name="lawton_medicacion" class="form-control" placeholder="1 o 0"></div>
                    </div>
                </div>
            </div>

            <div id="tab-mental" class="vgi-tab-content">
                <h4 class="mb-3 text-primary"><i class="fas fa-brain"></i> IV. Valoración Mental</h4>
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="card p-3 border-warning h-100" style="border-left: 5px solid #f1c40f;">
                            <h5>Test de Pfeiffer</h5>
                            <div class="form-group">
                                <label>Errores (0-10)</label>
                                <input type="number" name="pfeiffer_errores" class="form-control" max="10" value="{{ $vgi->pfeiffer_errores ?? '' }}">
                                <small class="text-muted d-block mt-1">0-2: Normal | 3-4: Leve | 8+: Severo</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card p-3 border-info h-100" style="border-left: 5px solid #3498db;">
                            <h5>Escala Yesavage (Depresión)</h5>
                            <div class="form-group">
                                <label>Puntaje Total</label>
                                <input type="number" name="yesavage_total" class="form-control" value="{{ $vgi->yesavage_total ?? '' }}">
                                <small class="text-muted d-block mt-1">0-5: Normal | >5: Probable Depresión</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card p-3 mt-3 bg-light border">
                    <h5>Minimental (MMSE)</h5>
                    <div class="row">
                        <div class="col-md-4"><label>Orientación /10</label><input type="number" name="mmse_orientacion" class="form-control" max="10"></div>
                        <div class="col-md-4"><label>Memoria /3</label><input type="number" name="mmse_memoria" class="form-control" max="3"></div>
                        <div class="col-md-4"><label>Total MMSE</label><input type="number" name="mmse_total" class="form-control" placeholder="Calculado"></div>
                    </div>
                    <div class="mt-3">
                         <div class="form-check">
                             <input class="form-check-input" type="checkbox" name="test_reloj_anomalo" value="1" {{ ($vgi->test_reloj_anomalo ?? 0) ? 'checked' : '' }}>
                             <label class="form-check-label text-danger fw-bold">Test del Reloj Anómalo</label>
                         </div>
                    </div>
                </div>
            </div>

            <div id="tab-fisica" class="vgi-tab-content">
                <h4 class="mb-3 text-primary"><i class="fas fa-apple-alt"></i> V. Física y Nutricional</h4>
                
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>MNA (Nutrición)</label>
                            <input type="number" name="mna_puntaje" class="form-control" value="{{ $vgi->mna_puntaje ?? '' }}">
                            <small class="text-muted">< 7: Malnutrición</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Velocidad Marcha (m/s)</label>
                            <input type="number" step="0.1" name="velocidad_marcha" class="form-control" value="{{ $vgi->velocidad_marcha ?? '' }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Escala FRAIL (0-5)</label>
                            <input type="number" name="frail_puntaje" class="form-control" max="5" value="{{ $vgi->frail_puntaje ?? '' }}">
                            <small class="text-muted">3-5: Frágil</small>
                        </div>
                    </div>
                </div>
                
                <div class="card p-3 mb-3 mt-3 bg-light border">
                    <h5>SPPB (Desempeño Físico)</h5>
                    <div class="row">
                        <div class="col-md-3"><label>Balance</label><input type="number" name="sppb_balance" class="form-control"></div>
                        <div class="col-md-3"><label>Velocidad</label><input type="number" name="sppb_velocidad" class="form-control"></div>
                        <div class="col-md-3"><label>Silla</label><input type="number" name="sppb_silla" class="form-control"></div>
                        <div class="col-md-3"><label>Total</label><input type="number" name="sppb_total" class="form-control" readonly></div>
                    </div>
                </div>

                <hr class="mt-4">
                <div class="form-group">
                    <label class="text-primary fw-bold"><i class="fas fa-user-md"></i> Plan de Trabajo / Recomendaciones Médicas</label>
                    <textarea name="plan_cuidados" class="form-control border-primary" rows="5" placeholder="Escriba aquí las indicaciones, tratamiento o derivaciones...">{{ $vgi->plan_cuidados ?? '' }}</textarea>
                </div>
            </div>

            <div class="form-actions mt-4 d-flex justify-content-end gap-2">
                <a href="{{ route('adultos') }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary btn-lg px-5 shadow"><i class="fas fa-save"></i> Guardar VGI</button>
            </div>

        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Estilos Modernos WasiQhari */
    .vgi-tabs-container { background: #f8f9fa; border-radius: 10px 10px 0 0; }
    .vgi-tab { 
        padding: 15px 20px; 
        border: none; 
        background: transparent; 
        cursor: pointer; 
        font-weight: 600; 
        color: #6c757d; 
        border-bottom: 3px solid transparent; 
        transition: all 0.3s ease;
        white-space: nowrap;
    }
    .vgi-tab:hover { color: var(--primary-color); background-color: rgba(0,0,0,0.02); }
    .vgi-tab.active { 
        color: var(--primary-color); 
        border-bottom-color: var(--primary-color); 
        background: white; 
        border-radius: 5px 5px 0 0;
    }
    .vgi-tab-content { display: none; animation: slideIn 0.3s ease-out; }
    .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 15px; }
    
    @keyframes slideIn { 
        from { opacity: 0; transform: translateY(10px); } 
        to { opacity: 1; transform: translateY(0); } 
    }
</style>
@endpush

@push('scripts')
<script>
    // 1. Función de Pestañas
    function openTab(evt, tabName) {
        var i, x, tablinks;
        x = document.getElementsByClassName("vgi-tab-content");
        for (i = 0; i < x.length; i++) { x[i].style.display = "none"; }
        tablinks = document.getElementsByClassName("vgi-tab");
        for (i = 0; i < tablinks.length; i++) { tablinks[i].className = tablinks[i].className.replace(" active", ""); }
        document.getElementById(tabName).style.display = "block";
        if(evt) evt.currentTarget.className += " active";
    }

    // 2. Función del Cuidador (Ahora está FUERA y funciona)
    function toggleCuidador(show) {
        const bloque = document.getElementById('bloque_cuidador');
        if(show) {
            bloque.style.display = 'block';
            setTimeout(() => { bloque.style.opacity = 1; }, 10); // Efecto visual suave
        } else {
            bloque.style.display = 'none';
            bloque.style.opacity = 0;
        }
    }
</script>
@endpush