@extends('layouts.dashboard')

@section('title', 'Historia Clínica Geriátrica - VGI')

@section('content')
<div class="dashboard-container">
    
    <div class="row align-items-center mb-4 fade-in-down">
        <div class="col-md-8">
            <h1 class="page-title text-brand mb-1">
                <i class="fas fa-file-medical-alt me-2"></i>Valoración Geriátrica Integral
            </h1>
            <p class="text-muted mb-0 d-flex align-items-center flex-wrap gap-3">
                <span class="fs-5 text-dark fw-bold">{{ $adulto->nombres }} {{ $adulto->apellidos }}</span>
                <span class="badge rounded-pill bg-purple-light text-purple px-3">
                    <i class="fas fa-birthday-cake me-1"></i> {{ $adulto->edad }} años
                </span>
                <input type="hidden" id="paciente_sexo" value="{{ $adulto->sexo }}">
            </p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <a href="{{ route('adultos') }}" class="btn btn-outline-secondary rounded-pill px-4 shadow-sm hover-scale">
                <i class="fas fa-arrow-left me-2"></i> Volver
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center">
            <i class="fas fa-check-circle fs-4 me-3 text-success"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    <div class="card border-0 shadow-lg rounded-4 overflow-hidden bg-white main-card fade-in-up">
        
        <div class="vgi-tabs-container sticky-top bg-white z-index-10 border-bottom pt-3 pb-0 px-4">
            <div class="vgi-tabs d-flex gap-3 pb-2">
                <button class="vgi-tab active" onclick="openTab(event, 'tab-social')"><i class="fas fa-user-circle"></i> <span>I. Social</span></button>
                <button class="vgi-tab" onclick="openTab(event, 'tab-clinica')"><i class="fas fa-weight"></i> <span>II. Antropometría</span></button>
                <button class="vgi-tab" onclick="openTab(event, 'tab-comorbilidades')"><i class="fas fa-heartbeat"></i> <span>III. Comorbilidades</span></button>
                <button class="vgi-tab" onclick="openTab(event, 'tab-ejercicio')"><i class="fas fa-running"></i> <span>IV. Ejercicio</span></button>
                <button class="vgi-tab" onclick="openTab(event, 'tab-gijon')"><i class="fas fa-users"></i> <span>V. Gijón</span></button>
                <button class="vgi-tab" onclick="openTab(event, 'tab-barthel')"><i class="fas fa-wheelchair"></i> <span>VI. Barthel</span></button>
                <button class="vgi-tab" onclick="openTab(event, 'tab-lawton')"><i class="fas fa-tasks"></i> <span>VII. Lawton</span></button>
                <button class="vgi-tab" onclick="openTab(event, 'tab-pfeiffer')"><i class="fas fa-brain"></i> <span>VIII. Pfeiffer</span></button>
                <button class="vgi-tab" onclick="openTab(event, 'tab-rudas')"><i class="fas fa-puzzle-piece"></i> <span>IX. RUDAS</span></button>
                <button class="vgi-tab" onclick="openTab(event, 'tab-mmse')"><i class="fas fa-brain"></i> <span>X. MMSE</span></button>
                <button class="vgi-tab" onclick="openTab(event, 'tab-minicog')"><i class="fas fa-stopwatch"></i> <span>XI. Mini-Cog</span></button>
                <button class="vgi-tab" onclick="openTab(event, 'tab-gds')"><i class="fas fa-sad-tear"></i> <span>XII. GDS-4</span></button>
                <button class="vgi-tab" onclick="openTab(event, 'tab-mna')"><i class="fas fa-utensils"></i> <span>XIII. Nutrición</span></button>
                
                <button class="vgi-tab" onclick="openTab(event, 'tab-fisica')"><i class="fas fa-apple-alt"></i> <span>XIV. Física</span></button>
            </div>
        </div>

        <form action="{{ route('adultos.vgi.store', $adulto->id) }}" method="POST" class="p-4 p-lg-5 bg-soft-gray">
            @csrf
            
            <div id="tab-social" class="vgi-tab-content active-content">
                
                <div class="metadata-banner bg-white rounded-4 shadow-sm p-4 mb-5 border-start border-5 border-purple">
                    <div class="row g-4 align-items-center justify-content-between">
                        <div class="col-md-3">
                            <label class="label-mini text-muted">Fecha</label>
                            <div class="d-flex gap-2">
                                <div class="icon-sq bg-purple-light text-purple"><i class="far fa-calendar-alt"></i></div>
                                <input type="date" name="fecha_evaluacion" class="form-control border-0 bg-transparent fw-bold p-0 text-dark" value="{{ \Carbon\Carbon::now('America/Lima')->format('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-md-2 border-start ps-4">
                            <label class="label-mini text-muted">Hora</label>
                            <div class="d-flex gap-2">
                                <div class="icon-sq bg-purple-light text-purple"><i class="far fa-clock"></i></div>
                                <input type="time" name="hora_evaluacion" class="form-control border-0 bg-transparent fw-bold p-0 text-dark" value="{{ \Carbon\Carbon::now('America/Lima')->format('H:i') }}">
                            </div>
                        </div>
                        <div class="col-md-6 border-start ps-4">
                            <label class="label-mini text-purple fw-bold mb-1">N° Historia Clínica (HCL)</label>
                            <input type="text" name="hcl" class="form-control form-control-lg bg-light border-0 text-dark fw-bold" placeholder="---" value="{{ $vgi->hcl ?? '' }}">
                        </div>
                    </div>
                </div>

                <div class="section-container mb-5">
                    <div class="section-header">
                        <div class="header-icon bg-purple text-white"><i class="fas fa-id-card"></i></div>
                        <h4 class="header-title text-purple">Datos Personales</h4>
                    </div>
                    
                    <div class="section-body p-4">
                        <div class="row g-4">
                            <div class="col-md-8">
                                <div class="floating-data">
                                    <label>Paciente</label>
                                    <div class="value">{{ $adulto->nombres }} {{ $adulto->apellidos }}</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="floating-data">
                                    <label>DNI</label>
                                    <div class="value">{{ $adulto->dni }}</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="label-input">Fecha Nacimiento</label>
                                <input type="date" name="fecha_nacimiento" class="form-control modern-input" 
                                       value="{{ $adulto->fecha_nacimiento ? \Carbon\Carbon::parse($adulto->fecha_nacimiento)->format('Y-m-d') : '' }}">
                            </div>
                            <div class="col-md-4">
                                <label class="label-input">Lugar de Nacimiento</label>
                                <div class="input-with-icon">
                                    <i class="fas fa-map-marker-alt text-muted"></i>
                                    <input type="text" name="lugar_nacimiento" class="form-control ps-5" placeholder="Ciudad" value="{{ $vgi->lugar_nacimiento ?? '' }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="label-input">Grupo Sanguíneo</label>
                                <select name="grupo_sanguineo" class="form-select modern-input">
                                    <option value="">Seleccionar...</option>
                                    @foreach(['O+', 'O-', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-'] as $gs)
                                        <option value="{{ $gs }}" {{ ($vgi->grupo_sanguineo ?? '') == $gs ? 'selected' : '' }}>{{ $gs }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="label-input">Procedencia</label>
                                <input type="text" name="procedencia" class="form-control modern-input" value="{{ $vgi->procedencia ?? $adulto->distrito }}">
                            </div>
                            <div class="col-md-4">
                                <label class="label-input">Religión</label>
                                <input type="text" name="religion" class="form-control modern-input" value="{{ $vgi->religion ?? '' }}">
                            </div>
                            <div class="col-md-4">
                                <label class="label-input">Ocupación Anterior</label>
                                <input type="text" name="ocupacion" class="form-control modern-input" value="{{ $vgi->ocupacion ?? '' }}">
                            </div>
                            <div class="col-12">
                                <label class="label-input">Teléfonos</label>
                                <div class="input-with-icon">
                                    <i class="fas fa-phone text-muted"></i>
                                    <input type="text" name="telefonos_referencia" class="form-control ps-5" placeholder="Casa / Celular" value="{{ $vgi->telefonos_referencia ?? $adulto->telefono }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="section-container mb-5">
                    <div class="section-header">
                        <div class="header-icon bg-orange text-white"><i class="fas fa-graduation-cap"></i></div>
                        <h4 class="header-title text-orange">Grado de Instrucción</h4>
                    </div>
                    <div class="section-body p-4 bg-white text-center">
                        <div class="grid-selection justify-content-center">
                            <label class="selection-card">
                                <input type="radio" name="grado_instruccion" value="Sin instrucción" {{ ($vgi->grado_instruccion ?? '') == 'Sin instrucción' ? 'checked' : '' }}>
                                <div class="card-inner"><div class="icon-lg">🚫</div><div class="text">Sin Instrucción</div></div>
                            </label>
                            <label class="selection-card">
                                <input type="radio" name="grado_instruccion" value="Primaria I" {{ ($vgi->grado_instruccion ?? '') == 'Primaria I' ? 'checked' : '' }}>
                                <div class="card-inner"><div class="icon-lg">📘</div><div class="text">Primaria<br><small>Incompleta</small></div></div>
                            </label>
                            <label class="selection-card">
                                <input type="radio" name="grado_instruccion" value="Primaria C" {{ ($vgi->grado_instruccion ?? '') == 'Primaria C' ? 'checked' : '' }}>
                                <div class="card-inner"><div class="icon-lg">📘</div><div class="text">Primaria<br><small>Completa</small></div></div>
                            </label>
                            <label class="selection-card">
                                <input type="radio" name="grado_instruccion" value="Secundaria I" {{ ($vgi->grado_instruccion ?? '') == 'Secundaria I' ? 'checked' : '' }}>
                                <div class="card-inner"><div class="icon-lg">📙</div><div class="text">Secundaria<br><small>Incompleta</small></div></div>
                            </label>
                            <label class="selection-card">
                                <input type="radio" name="grado_instruccion" value="Secundaria C" {{ ($vgi->grado_instruccion ?? '') == 'Secundaria C' ? 'checked' : '' }}>
                                <div class="card-inner"><div class="icon-lg">📙</div><div class="text">Secundaria<br><small>Completa</small></div></div>
                            </label>
                            <label class="selection-card">
                                <input type="radio" name="grado_instruccion" value="Superior" {{ str_contains($vgi->grado_instruccion ?? '', 'Superior') ? 'checked' : '' }}>
                                <div class="card-inner"><div class="icon-lg">🎓</div><div class="text">Superior<br><small>Univ/Téc</small></div></div>
                            </label>
                        </div>
                        <div class="d-inline-flex align-items-center bg-soft-orange px-4 py-3 rounded-pill mt-4 border border-orange-light">
                            <span class="text-orange fw-bold me-3">Total Años de Estudio:</span>
                            <input type="number" name="anios_estudio" min="0" class="form-control border-0 bg-transparent text-center fw-bold fs-5 text-dark" style="width: 80px;" value="{{ $vgi->anios_estudio ?? '' }}" placeholder="0">
                        </div>
                    </div>
                </div>

                <div class="section-container mb-5">
                    <div class="section-header">
                        <div class="header-icon bg-teal text-white"><i class="fas fa-user-friends"></i></div>
                        <h4 class="header-title text-teal">Estado Civil</h4>
                    </div>
                    <div class="section-body p-4 text-center">
                        <div class="d-flex flex-wrap justify-content-center gap-3">
                            @foreach(['Soltero', 'Casado', 'Conviviente', 'Viudo', 'Divorciado'] as $ec)
                                <div class="btn-radio-pill">
                                    <input type="radio" name="estado_civil" id="ec_{{ $ec }}" value="{{ $ec }}" {{ ($vgi->estado_civil ?? '') == $ec ? 'checked' : '' }}>
                                    <label for="ec_{{ $ec }}">{{ $ec }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Nueva sección de Red Social y Apoyo -->
                <div class="section-container mb-5">
                    <div class="section-header">
                        <div class="header-icon bg-success text-white"><i class="fas fa-users"></i></div>
                        <h4 class="header-title text-success">Red Social y Apoyo</h4>
                    </div>
                    <div class="section-body p-4">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="label-input">¿Con quién vive?</label>
                                <select name="vive_con" class="form-select modern-input">
                                    <option value="">Seleccionar...</option>
                                    @foreach(['Solo', 'Pareja', 'Hijos', 'Familia extendida', 'Amigos', 'Institución'] as $opcion)
                                        <option value="{{ $opcion }}" {{ ($vgi->vive_con ?? '') == $opcion ? 'selected' : '' }}>{{ $opcion }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="label-input">Frecuencia de visitas familiares</label>
                                <select name="visitas_familiares" class="form-select modern-input">
                                    <option value="">Seleccionar...</option>
                                    @foreach(['Diario', 'Semanal', 'Quincenal', 'Mensual', 'Rara vez', 'Nunca'] as $frec)
                                        <option value="{{ $frec }}" {{ ($vgi->visitas_familiares ?? '') == $frec ? 'selected' : '' }}>{{ $frec }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="label-input">¿Participa en actividades sociales o comunitarias?</label>
                                <textarea name="actividades_sociales" class="form-control modern-input" rows="2" placeholder="Ej: Club del adulto mayor, iglesia, voluntariado...">{{ $vgi->actividades_sociales ?? '' }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="caregiver-section rounded-4 overflow-hidden shadow-sm {{ ($vgi->cuidador_aplica ?? 0) == 1 ? 'active' : '' }}" id="caregiverContainer">
                    <div class="caregiver-header p-4 d-flex align-items-center justify-content-between cursor-pointer" onclick="toggleSwitch()">
                        <div class="d-flex align-items-center">
                            <div class="icon-circle me-3 shadow-sm bg-white text-orange"><i class="fas fa-hands-helping"></i></div>
                            <div>
                                <h5 class="mb-1 fw-bold text-dark">¿Tiene Cuidador o Soporte?</h5>
                                <small class="text-muted d-block">Active si el paciente es dependiente</small>
                            </div>
                        </div>
                        <div class="form-check form-switch custom-switch">
                            <input class="form-check-input" type="checkbox" id="cuidadorSwitch" {{ ($vgi->cuidador_aplica ?? 0) == 1 ? 'checked' : '' }} onchange="toggleCuidador(this.checked)">
                            <input type="hidden" name="cuidador_aplica" id="cuidador_val" value="{{ $vgi->cuidador_aplica ?? 0 }}">
                        </div>
                    </div>
                    <div id="bloque_cuidador" class="caregiver-body bg-white px-4 pb-4 pt-2" style="display: {{ ($vgi->cuidador_aplica ?? 0) == 1 ? 'block' : 'none' }};">
                        <div class="divider-line mb-4"></div>
                        <div class="row g-4">
                            <div class="col-12 text-center">
                                <label class="label-input mb-3">Parentesco con el Paciente</label>
                                <div class="d-flex flex-wrap justify-content-center gap-2">
                                    @foreach(['Esposa(o)', 'Hijas(os)', 'Sobrinos', 'Nietos', 'Nuera', 'Otros'] as $par)
                                        <div class="btn-radio-pill small-pill">
                                            <input type="radio" name="parentesco_cuidador" id="par_{{ $par }}" value="{{ $par }}" {{ ($vgi->parentesco_cuidador ?? '') == $par ? 'checked' : '' }}>
                                            <label for="par_{{ $par }}">{{ $par }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-md-6"><label class="label-input">Nombre Completo</label><input type="text" name="nombre_cuidador" class="form-control modern-input" value="{{ $vgi->nombre_cuidador ?? '' }}"></div>
                            <div class="col-md-3"><label class="label-input">DNI Cuidador</label><input type="text" name="dni_cuidador" class="form-control modern-input" value="{{ $vgi->dni_cuidador ?? '' }}"></div>
                            <div class="col-md-2"><label class="label-input">Edad</label><input type="number" name="cuidador_edad" min="0" class="form-control modern-input" value="{{ $vgi->cuidador_edad ?? '' }}"></div>
                            <div class="col-md-1"><label class="label-input">Sexo</label><select name="cuidador_sexo" class="form-select modern-input"><option value="F">F</option><option value="M">M</option></select></div>
                        </div>
                    </div>
                </div>
            </div> 

            <div id="tab-clinica" class="vgi-tab-content">
                
                <div class="section-header mb-4">
                    <div class="header-icon bg-danger text-white"><i class="fas fa-weight"></i></div>
                    <h4 class="header-title text-danger">II. Evaluación Antropométrica</h4>
                </div>

                <div class="row g-4 mb-5">
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm bg-white h-100">
                            <div class="card-body p-4 text-center">
                                <label class="label-title text-muted mb-2">PESO (Kg) <span class="text-danger">*</span></label>
                                <div class="input-group input-group-lg">
                                    <input type="number" step="0.1" min="0" id="peso" name="peso" 
                                           class="form-control text-center fw-bold fs-3 border-0 bg-light rounded-3 text-dark" 
                                           placeholder="0.0" value="{{ $vgi->peso ?? '' }}" required oninput="calcularIMC()">
                                    <span class="input-group-text bg-transparent border-0 text-muted">kg</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm bg-white h-100">
                            <div class="card-body p-4 text-center">
                                <label class="label-title text-muted mb-2">TALLA (m) <span class="text-danger">*</span></label>
                                <div class="input-group input-group-lg">
                                    <input type="number" step="0.01" min="0" id="talla" name="talla" 
                                           class="form-control text-center fw-bold fs-3 border-0 bg-light rounded-3 text-dark" 
                                           placeholder="0.00" value="{{ $vgi->talla ?? '' }}" required oninput="calcularIMC()">
                                    <span class="input-group-text bg-transparent border-0 text-muted">mts</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm bg-primary text-white h-100" style="background: linear-gradient(135deg, var(--primary) 0%, #8e44ad 100%);">
                            <div class="card-body p-4 text-center position-relative">
                                <label class="label-title text-white-50 mb-2">IMC (Automático)</label>
                                <div class="d-flex justify-content-center align-items-baseline">
                                    <input type="number" step="0.1" id="imc" name="imc" 
                                           class="form-control text-center fw-bold border-0 bg-transparent text-white p-0 m-0" 
                                           style="font-size: 3.5rem; height: auto;" 
                                           readonly value="{{ $vgi->imc ?? '' }}" placeholder="--.-">
                                </div>
                                <small id="imc-estado" class="text-white-50 font-monospace mt-2 d-block">Esperando datos...</small>
                                <i class="fas fa-calculator position-absolute top-0 end-0 m-3 opacity-25" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="section-container mb-5">
                    <div class="section-body p-4">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="label-input">Perímetro Abdominal (cm)</label>
                                <div class="input-group shadow-sm">
                                    <span class="input-group-text bg-white text-danger"><i class="fas fa-ruler-horizontal"></i></span>
                                    <input type="number" min="0" name="perimetro_abdominal" class="form-control form-control-lg border-start-0" 
                                           value="{{ $vgi->perimetro_abdominal ?? '' }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="label-input">Perímetro de Pantorrilla (cm)</label>
                                <div class="input-group shadow-sm">
                                    <span class="input-group-text bg-white text-danger"><i class="fas fa-ruler-horizontal"></i></span>
                                    <input type="number" min="0" name="perimetro_pantorrilla" class="form-control form-control-lg border-start-0" 
                                           value="{{ $vgi->perimetro_pantorrilla ?? '' }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="section-container mb-4">
                    <div class="section-header border-bottom">
                        <div class="d-flex align-items-center gap-3">
                            <div class="icon-box bg-dark text-white"><i class="fas fa-fist-raised"></i></div>
                            <div>
                                <h5 class="m-0 fw-bold text-dark">Dinamómetro</h5>
                                <small class="text-muted">Fuerza de prensión manual</small>
                            </div>
                        </div>
                        <div class="ms-auto"><select name="mano_dominante" class="form-select form-select-sm w-auto"><option value="">Seleccionar...</option><option value="Derecha" {{ ($vgi->mano_dominante ?? '') == 'Derecha' ? 'selected' : '' }}>Derecha</option><option value="Izquierda" {{ ($vgi->mano_dominante ?? '') == 'Izquierda' ? 'selected' : '' }}>Izquierda</option></select></div>
                    </div>
                    
                    <div class="section-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0 text-center align-middle">
                                <thead class="bg-light text-secondary small text-uppercase">
                                    <tr>
                                        <th class="py-3" style="width: 20%;">Mano</th>
                                        <th class="py-3">1ra (Kg)</th>
                                        <th class="py-3">2da (Kg)</th>
                                        <th class="py-3">3ra (Kg)</th>
                                        <th class="py-3 bg-soft-gray">Máximo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="fw-bold text-end pe-4">Derecha</td>
                                        <td class="p-2"><input type="number" step="0.1" name="dinam_derecha_1" class="form-control border-0 text-center bg-transparent input-grid" placeholder="-" value="{{ $vgi->dinam_derecha_1 ?? '' }}"></td>
                                        <td class="p-2"><input type="number" step="0.1" name="dinam_derecha_2" class="form-control border-0 text-center bg-transparent input-grid" placeholder="-" value="{{ $vgi->dinam_derecha_2 ?? '' }}"></td>
                                        <td class="p-2"><input type="number" step="0.1" name="dinam_derecha_3" class="form-control border-0 text-center bg-transparent input-grid" placeholder="-" value="{{ $vgi->dinam_derecha_3 ?? '' }}"></td>
                                        <td class="bg-soft-gray fw-bold text-dark" id="max_derecha">-</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold text-end pe-4">Izquierda</td>
                                        <td class="p-2"><input type="number" step="0.1" name="dinam_izquierda_1" class="form-control border-0 text-center bg-transparent input-grid" placeholder="-" value="{{ $vgi->dinam_izquierda_1 ?? '' }}"></td>
                                        <td class="p-2"><input type="number" step="0.1" name="dinam_izquierda_2" class="form-control border-0 text-center bg-transparent input-grid" placeholder="-" value="{{ $vgi->dinam_izquierda_2 ?? '' }}"></td>
                                        <td class="p-2"><input type="number" step="0.1" name="dinam_izquierda_3" class="form-control border-0 text-center bg-transparent input-grid" placeholder="-" value="{{ $vgi->dinam_izquierda_3 ?? '' }}"></td>
                                        <td class="bg-soft-gray fw-bold text-dark" id="max_izquierda">-</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div id="tab-comorbilidades" class="vgi-tab-content">
                <div class="section-header mb-4">
                    <div class="header-icon bg-danger text-white"><i class="fas fa-heartbeat"></i></div>
                    <h4 class="header-title text-danger">III. Comorbilidades y Síndromes</h4>
                </div>
                
                <div class="section-container mb-5">
                    <div class="section-header bg-soft-gray"><h5 class="m-0 fw-bold text-dark">Patologías Crónicas</h5></div>
                    <div class="section-body p-4">
                        <div class="row g-3">
                            @php $enfermedades = [ 'tiene_hta' => 'HTA Presión Arterial', 'tiene_diabetes' => 'Diabetes Mellitus', 'tiene_epoc' => 'EPOC', 'tiene_epid' => 'Enf. Pulmonar Intersticial Difusa', 'tiene_fa' => 'Fibrilación Auricular', 'tiene_coronaria' => 'Enf. Coronaria Crónica', 'tiene_icc' => 'Insuficiencia Cardiaca', 'tiene_demencia' => 'Demencia', 'tiene_hipotiroidismo' => 'Hipotiroidismo', 'tiene_depresion' => 'Depresión en tratamiento', 'tiene_osteoporosis' => 'Osteoporosis', 'tiene_artrosis' => 'Osteoartrosis', 'tiene_parkinson' => 'Enfermedad de Parkinson' ]; @endphp
                            @foreach($enfermedades as $key => $label)
                            <div class="col-md-12 border-bottom pb-2">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="fw-bold text-dark">{{ $label }}</span>
                                    <div class="btn-group"><input type="radio" class="btn-check" name="{{ $key }}" id="{{ $key }}_si" value="1" {{ ($vgi->$key ?? 0) == 1 ? 'checked' : '' }}><label class="btn btn-outline-danger btn-sm px-4 rounded-start-pill" for="{{ $key }}_si">SI</label><input type="radio" class="btn-check" name="{{ $key }}" id="{{ $key }}_no" value="0" {{ ($vgi->$key ?? 0) == 0 ? 'checked' : '' }}><label class="btn btn-outline-secondary btn-sm px-4 rounded-end-pill" for="{{ $key }}_no">NO</label></div>
                                </div>
                            </div>
                            @endforeach
                            <div class="col-12 border-bottom pb-2 pt-2 bg-light rounded">
                                <div class="d-flex align-items-center justify-content-between mb-2"><span class="fw-bold text-danger">Cáncer</span><div class="btn-group"><input type="radio" class="btn-check" name="tiene_cancer" id="cancer_si" value="1" {{ ($vgi->tiene_cancer ?? 0) == 1 ? 'checked' : '' }} onclick="document.getElementById('cancer_details').style.display='block'"><label class="btn btn-outline-danger btn-sm px-4 rounded-start-pill" for="cancer_si">SI</label><input type="radio" class="btn-check" name="tiene_cancer" id="cancer_no" value="0" {{ ($vgi->tiene_cancer ?? 0) == 0 ? 'checked' : '' }} onclick="document.getElementById('cancer_details').style.display='none'"><label class="btn btn-outline-secondary btn-sm px-4 rounded-end-pill" for="cancer_no">NO</label></div></div>
                                <div id="cancer_details" style="display: {{ ($vgi->tiene_cancer ?? 0) == 1 ? 'block' : 'none' }};"><input type="text" name="cancer_info" class="form-control modern-input" placeholder="Especifique..." value="{{ $vgi->cancer_info ?? '' }}"></div>
                            </div>
                            <div class="col-12 pt-2"><label class="label-input">Otras:</label><input type="text" name="otras_enfermedades" class="form-control modern-input" value="{{ $vgi->otras_enfermedades ?? '' }}"></div>
                        </div>
                    </div>
                </div>
                <div class="section-container mb-5">
                    <div class="section-header bg-soft-gray"><h5 class="m-0 fw-bold text-dark">Síndromes y Problemas</h5></div>
                    <div class="section-body p-4">
                        <div class="row g-3">
                            <div class="col-12 border-bottom pb-2"><div class="d-flex justify-content-between"><span>Caídas > 2 último año</span><div class="btn-group"><input type="radio" class="btn-check" name="sindrome_caidas" id="caidas_si" value="1" {{ ($vgi->sindrome_caidas ?? 0) == 1 ? 'checked' : '' }}><label class="btn btn-outline-danger btn-sm px-3" for="caidas_si">SI</label><input type="radio" class="btn-check" name="sindrome_caidas" id="caidas_no" value="0" {{ ($vgi->sindrome_caidas ?? 0) == 0 ? 'checked' : '' }}><label class="btn btn-outline-secondary btn-sm px-3" for="caidas_no">NO</label></div></div></div>
                            <div class="col-12 border-bottom pb-2"><div class="d-flex justify-content-between"><span>Incontinencia</span><div class="btn-group"><input type="radio" class="btn-check" name="sindrome_incontinencia" id="inc_si" value="1" {{ ($vgi->sindrome_incontinencia ?? 0) == 1 ? 'checked' : '' }}><label class="btn btn-outline-danger btn-sm px-3" for="inc_si">SI</label><input type="radio" class="btn-check" name="sindrome_incontinencia" id="inc_no" value="0" {{ ($vgi->sindrome_incontinencia ?? 0) == 0 ? 'checked' : '' }}><label class="btn btn-outline-secondary btn-sm px-3" for="inc_no">NO</label></div></div></div>
                            <div class="col-12 border-bottom pb-2"><div class="d-flex justify-content-between"><span>Delirio</span><div class="btn-group"><input type="radio" class="btn-check" name="sindrome_delirio" id="del_si" value="1" {{ ($vgi->sindrome_delirio ?? 0) == 1 ? 'checked' : '' }}><label class="btn btn-outline-danger btn-sm px-3" for="del_si">SI</label><input type="radio" class="btn-check" name="sindrome_delirio" id="del_no" value="0" {{ ($vgi->sindrome_delirio ?? 0) == 0 ? 'checked' : '' }}><label class="btn btn-outline-secondary btn-sm px-3" for="del_no">NO</label></div></div></div>
                            <div class="col-12 border-bottom pb-2"><div class="d-flex justify-content-between"><span>Faltan piezas dentales</span><div class="btn-group"><input type="radio" class="btn-check" name="problema_dental" id="dent_si" value="1" {{ ($vgi->problema_dental ?? 0) == 1 ? 'checked' : '' }}><label class="btn btn-outline-danger btn-sm px-3" for="dent_si">SI</label><input type="radio" class="btn-check" name="problema_dental" id="dent_no" value="0" {{ ($vgi->problema_dental ?? 0) == 0 ? 'checked' : '' }}><label class="btn btn-outline-secondary btn-sm px-3" for="dent_no">NO</label></div></div></div>
                            <div class="col-12 border-bottom pb-2"><div class="d-flex justify-content-between"><span>Usa prótesis</span><div class="btn-group"><input type="radio" class="btn-check" name="usa_protesis" id="prot_si" value="1" {{ ($vgi->usa_protesis ?? 0) == 1 ? 'checked' : '' }}><label class="btn btn-outline-primary btn-sm px-3" for="prot_si">SI</label><input type="radio" class="btn-check" name="usa_protesis" id="prot_no" value="0" {{ ($vgi->usa_protesis ?? 0) == 0 ? 'checked' : '' }}><label class="btn btn-outline-secondary btn-sm px-3" for="prot_no">NO</label></div></div></div>
                            <div class="col-12 border-bottom pb-2"><div class="d-flex justify-content-between"><span>Ve bien</span><div class="btn-group"><input type="radio" class="btn-check" name="vision_conservada" id="vis_si" value="1" {{ ($vgi->vision_conservada ?? 1) == 1 ? 'checked' : '' }}><label class="btn btn-outline-success btn-sm px-3" for="vis_si">SI</label><input type="radio" class="btn-check" name="vision_conservada" id="vis_no" value="0" {{ ($vgi->vision_conservada ?? 1) == 0 ? 'checked' : '' }}><label class="btn btn-outline-danger btn-sm px-3" for="vis_no">NO</label></div></div></div>
                            <div class="col-12 border-bottom pb-2"><div class="d-flex justify-content-between"><span>Escucha bien</span><div class="btn-group"><input type="radio" class="btn-check" name="audicion_conservada" id="aud_si" value="1" {{ ($vgi->audicion_conservada ?? 1) == 1 ? 'checked' : '' }}><label class="btn btn-outline-success btn-sm px-3" for="aud_si">SI</label><input type="radio" class="btn-check" name="audicion_conservada" id="aud_no" value="0" {{ ($vgi->audicion_conservada ?? 1) == 0 ? 'checked' : '' }}><label class="btn btn-outline-danger btn-sm px-3" for="aud_no">NO</label></div></div></div>
                            <div class="col-12 border-bottom pb-2"><div class="d-flex justify-content-between"><span>Estreñimiento</span><div class="btn-group"><input type="radio" class="btn-check" name="problema_estrenimiento" id="estr_si" value="1" {{ ($vgi->problema_estrenimiento ?? 0) == 1 ? 'checked' : '' }}><label class="btn btn-outline-danger btn-sm px-3" for="estr_si">SI</label><input type="radio" class="btn-check" name="problema_estrenimiento" id="estr_no" value="0" {{ ($vgi->problema_estrenimiento ?? 0) == 0 ? 'checked' : '' }}><label class="btn btn-outline-secondary btn-sm px-3" for="estr_no">NO</label></div></div></div>
                            <div class="col-12 border-bottom pb-2"><div class="d-flex justify-content-between"><span>Insomnio</span><div class="btn-group"><input type="radio" class="btn-check" name="problema_insomnio" id="insom_si" value="1" {{ ($vgi->problema_insomnio ?? 0) == 1 ? 'checked' : '' }}><label class="btn btn-outline-danger btn-sm px-3" for="insom_si">SI</label><input type="radio" class="btn-check" name="problema_insomnio" id="insom_no" value="0" {{ ($vgi->problema_insomnio ?? 0) == 0 ? 'checked' : '' }}><label class="btn btn-outline-secondary btn-sm px-3" for="insom_no">NO</label></div></div></div>
                            <div class="col-12"><div class="d-flex justify-content-between"><span>Nocturia</span><div class="btn-group"><input type="radio" class="btn-check" name="problema_nocturia" id="noct_si" value="1" {{ ($vgi->problema_nocturia ?? 0) == 1 ? 'checked' : '' }}><label class="btn btn-outline-danger btn-sm px-3" for="noct_si">SI</label><input type="radio" class="btn-check" name="problema_nocturia" id="noct_no" value="0" {{ ($vgi->problema_nocturia ?? 0) == 0 ? 'checked' : '' }}><label class="btn btn-outline-secondary btn-sm px-3" for="noct_no">NO</label></div></div></div>
                        </div>
                    </div>
                </div>
                <div class="section-container">
                    <div class="section-header bg-soft-gray"><h5 class="m-0 fw-bold text-dark">Medicación</h5></div>
                    <div class="section-body p-4">
                        <div class="mb-3"><label class="d-block mb-3 fw-bold">¿Toma tratamiento diario (últimos 6 meses)?</label><div class="btn-group"><input type="radio" class="btn-check" name="toma_medicacion" id="meds_si" value="1" {{ ($vgi->toma_medicacion ?? 0) == 1 ? 'checked' : '' }} onclick="document.getElementById('meds_qty').style.display='block'"><label class="btn btn-outline-danger btn-sm px-4" for="meds_si">1. SI</label><input type="radio" class="btn-check" name="toma_medicacion" id="meds_no" value="0" {{ ($vgi->toma_medicacion ?? 0) == 0 ? 'checked' : '' }} onclick="document.getElementById('meds_qty').style.display='none'"><label class="btn btn-outline-secondary btn-sm px-4" for="meds_no">2. NO</label></div></div>
                        <div id="meds_qty" style="display: {{ ($vgi->toma_medicacion ?? 0) == 1 ? 'block' : 'none' }};"><label class="label-input">¿Cuántos medicamentos al día?</label><input type="number" name="num_medicamentos" class="form-control modern-input w-25" placeholder="N°" value="{{ $vgi->num_medicamentos ?? '' }}"></div>
                    </div>
                </div>
            </div>

            <div id="tab-ejercicio" class="vgi-tab-content">
                <div class="section-header mb-4"><div class="header-icon bg-info text-white"><i class="fas fa-running"></i></div><h4 class="header-title text-info">IV. Ejercicio y Tiempo Libre</h4></div>
                <div class="section-container">
                    <div class="section-body p-4">
                        <div class="mb-4 pb-4 border-bottom">
                            <label class="d-block mb-3 fw-bold">1. ¿Usted realiza ejercicio? (camina 150 min/semana)</label>
                            <div class="btn-group"><input type="radio" class="btn-check" name="realiza_ejercicio" id="ejer_si" value="1" {{ ($vgi->realiza_ejercicio ?? 0) == 1 ? 'checked' : '' }}><label class="btn btn-outline-success btn-sm px-5 rounded-start-pill" for="ejer_si">1. SI</label><input type="radio" class="btn-check" name="realiza_ejercicio" id="ejer_no" value="0" {{ ($vgi->realiza_ejercicio ?? 0) == 0 ? 'checked' : '' }}><label class="btn btn-outline-secondary btn-sm px-5 rounded-end-pill" for="ejer_no">2. NO</label></div>
                        </div>
                        <div>
                            <label class="d-block mb-3 fw-bold">2. ¿Acude a algún centro del adulto mayor, parroquial o municipal?</label>
                            <div class="btn-group mb-4"><input type="radio" class="btn-check" name="acude_centro_social" id="centro_si" value="1" {{ ($vgi->acude_centro_social ?? 0) == 1 ? 'checked' : '' }} onclick="document.getElementById('activ_centro').style.display='block'"><label class="btn btn-outline-success btn-sm px-5 rounded-start-pill" for="centro_si">1. SI</label><input type="radio" class="btn-check" name="acude_centro_social" id="centro_no" value="0" {{ ($vgi->acude_centro_social ?? 0) == 0 ? 'checked' : '' }} onclick="document.getElementById('activ_centro').style.display='none'"><label class="btn btn-outline-secondary btn-sm px-5 rounded-end-pill" for="centro_no">2. NO</label></div>
                            <div id="activ_centro" style="display: {{ ($vgi->acude_centro_social ?? 0) == 1 ? 'block' : 'none' }};" class="bg-soft-gray p-4 rounded-3 border">
                                <label class="label-input mb-3 fw-bold text-dark">2.1 ¿Qué actividad realiza?</label>
                                <div class="grid-selection justify-content-start">
                                    @foreach(['Manualidades', 'Ejercicio / Taichí', 'Computación', 'Danzas'] as $act)
                                        <label class="selection-card"><input type="radio" name="actividad_centro_social" value="{{ $act }}" {{ ($vgi->actividad_centro_social ?? '') == $act ? 'checked' : '' }}><div class="card-inner py-3"><div class="text">{{ $act }}</div></div></label>
                                    @endforeach
                                </div>
                                <div class="mt-3"><label class="label-input">Otras actividades:</label><input type="text" name="actividad_centro_social" class="form-control modern-input" placeholder="Especifique..." value="{{ !in_array($vgi->actividad_centro_social ?? '', ['Manualidades', 'Ejercicio / Taichí', 'Computación', 'Danzas']) ? ($vgi->actividad_centro_social ?? '') : '' }}"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PESTAÑA: EVALUACIÓN SOCIAL (GIJÓN) -->
            <div id="tab-gijon" class="vgi-tab-content">
                <div class="section-header mb-4">
                    <div class="header-icon bg-purple text-white"><i class="fas fa-users"></i></div>
                    <h4 class="header-title text-purple">V. Evaluación Social (Gijón)</h4>
                </div>

                <div class="row g-4">
                    <div class="col-md-12">
                        <div class="section-container border mb-3">
                            <div class="section-header bg-light"><h6 class="m-0 fw-bold">1. Situación Familiar</h6></div>
                            <div class="section-body p-3">
                                @php $fam = [
                                    1 => 'Vive con familia, sin dependencia física/psíquica',
                                    2 => 'Vive con cónyuge de similar edad',
                                    3 => 'Vive con familia y presenta dependencia',
                                    4 => 'Vive solo, hijos próximos',
                                    5 => 'Vive solo, carece de hijos o viven lejos'
                                ]; @endphp
                                @foreach($fam as $val => $txt)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="gijon_familiar" id="gf_{{$val}}" value="{{$val}}" {{ ($vgi->gijon_familiar ?? 0) == $val ? 'checked' : '' }} onchange="calcularGijon()">
                                    <label class="form-check-label" for="gf_{{$val}}">{{$val}}. {{$txt}}</label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="section-container border mb-3">
                            <div class="section-header bg-light"><h6 class="m-0 fw-bold">2. Situación Económica</h6></div>
                            <div class="section-body p-3">
                                @php $eco = [
                                    1 => 'Dos veces el salario mínimo',
                                    2 => 'Menos de 2 veces, pero más de 1 salario mínimo',
                                    3 => '1 salario mínimo vital',
                                    4 => 'Ingreso irregular (menos del mínimo)',
                                    5 => 'Sin pensión, no tiene otros ingresos'
                                ]; @endphp
                                @foreach($eco as $val => $txt)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="gijon_economica" id="ge_{{$val}}" value="{{$val}}" {{ ($vgi->gijon_economica ?? 0) == $val ? 'checked' : '' }} onchange="calcularGijon()">
                                    <label class="form-check-label" for="ge_{{$val}}">{{$val}}. {{$txt}}</label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="section-container border mb-3">
                            <div class="section-header bg-light"><h6 class="m-0 fw-bold">3. Vivienda</h6></div>
                            <div class="section-body p-3">
                                @php $viv = [
                                    1 => 'Adecuada a necesidades',
                                    2 => 'Barreras arquitectónicas (pisos irregulares, peldaños)',
                                    3 => 'Mala conservación, humedad, mala higiene',
                                    4 => 'Vivienda semiconstruida o rústica',
                                    5 => 'Asentamiento humano (Invasión) o sin vivienda'
                                ]; @endphp
                                @foreach($viv as $val => $txt)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="gijon_vivienda" id="gv_{{$val}}" value="{{$val}}" {{ ($vgi->gijon_vivienda ?? 0) == $val ? 'checked' : '' }} onchange="calcularGijon()">
                                    <label class="form-check-label" for="gv_{{$val}}">{{$val}}. {{$txt}}</label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="section-container border mb-3">
                            <div class="section-header bg-light"><h6 class="m-0 fw-bold">4. Relaciones Sociales</h6></div>
                            <div class="section-body p-3">
                                @php $rel = [
                                    1 => 'Relaciones sociales externas',
                                    2 => 'Relación solo con familia y vecinos',
                                    3 => 'Relación solo con familia O vecinos',
                                    4 => 'No sale, recibe familia',
                                    5 => 'No sale y no recibe visitas'
                                ]; @endphp
                                @foreach($rel as $val => $txt)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="gijon_relaciones" id="gr_{{$val}}" value="{{$val}}" {{ ($vgi->gijon_relaciones ?? 0) == $val ? 'checked' : '' }} onchange="calcularGijon()">
                                    <label class="form-check-label" for="gr_{{$val}}">{{$val}}. {{$txt}}</label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="section-container border mb-3">
                            <div class="section-header bg-light"><h6 class="m-0 fw-bold">5. Apoyos a la red social</h6></div>
                            <div class="section-body p-3">
                                @php $apo = [
                                    1 => 'No necesita apoyo',
                                    2 => 'Con apoyo familiar o vecinal',
                                    3 => 'Tiene seguro/SIS, pero necesita apoyo voluntariado',
                                    4 => 'No cuenta con seguro social',
                                    5 => 'Situación de abandono familiar'
                                ]; @endphp
                                @foreach($apo as $val => $txt)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="gijon_apoyo" id="ga_{{$val}}" value="{{$val}}" {{ ($vgi->gijon_apoyo ?? 0) == $val ? 'checked' : '' }} onchange="calcularGijon()">
                                    <label class="form-check-label" for="ga_{{$val}}">{{$val}}. {{$txt}}</label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-4 border-0 shadow-sm bg-dark text-white">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="m-0">Puntaje Total Gijón</h5>
                            <small class="text-white-50">Suma automática</small>
                        </div>
                        <div class="text-end">
                            <h2 class="m-0 fw-bold" id="gijon_score">{{ $vgi->gijon_total ?? 0 }}</h2>
                            <span class="badge bg-secondary" id="gijon_result">Sin evaluar</span>
                            <input type="hidden" name="gijon_total" id="input_gijon_total" value="{{ $vgi->gijon_total ?? 0 }}">
                            <input type="hidden" name="gijon_valoracion" id="input_gijon_valoracion" value="{{ $vgi->gijon_valoracion ?? '' }}">
                        </div>
                    </div>
                </div>
            </div>

            <!-- PESTAÑA: ÍNDICE DE BARTHEL COMPLETO -->
            <div id="tab-barthel" class="vgi-tab-content">
                <div class="section-header mb-4">
                    <div class="header-icon bg-primary text-white"><i class="fas fa-wheelchair"></i></div>
                    <h4 class="header-title text-primary">VI. Desempeño Funcional: Índice de BARTHEL</h4>
                </div>

                <div class="row">
                    <div class="col-lg-8">
                        
                        <div class="section-container mb-3 border-start border-4 border-primary">
                            <div class="p-3 bg-light d-flex justify-content-between align-items-center">
                                <h6 class="m-0 fw-bold">1. COMER</h6>
                                <span class="badge bg-white text-dark border">Puntos</span>
                            </div>
                            <div class="p-3">
                                <div class="form-check mb-2">
                                    <input class="form-check-input barthel-radio" type="radio" name="barthel_comer" value="10" {{ ($vgi->barthel_comer ?? 0) == 10 ? 'checked' : '' }}>
                                    <label class="form-check-label">Independiente (La comida está al alcance)</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input barthel-radio" type="radio" name="barthel_comer" value="5" {{ ($vgi->barthel_comer ?? 0) == 5 ? 'checked' : '' }}>
                                    <label class="form-check-label">Necesita ayuda para cortar, untar, etc.</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input barthel-radio" type="radio" name="barthel_comer" value="0" {{ ($vgi->barthel_comer ?? 0) == 0 ? 'checked' : '' }}>
                                    <label class="form-check-label">Dependiente (Necesita ser alimentado)</label>
                                </div>
                            </div>
                        </div>

                        <div class="section-container mb-3 border-start border-4 border-primary">
                            <div class="p-3 bg-light"><h6 class="m-0 fw-bold">2. ASEO PERSONAL (Lavarse cara, manos, dientes)</h6></div>
                            <div class="p-3">
                                <div class="form-check mb-2"><input class="form-check-input barthel-radio" type="radio" name="barthel_aseo" value="5" {{ ($vgi->barthel_aseo ?? 0) == 5 ? 'checked' : '' }}><label class="form-check-label">Independiente</label></div>
                                <div class="form-check"><input class="form-check-input barthel-radio" type="radio" name="barthel_aseo" value="0" {{ ($vgi->barthel_aseo ?? 0) == 0 ? 'checked' : '' }}><label class="form-check-label">Dependiente (Necesita ayuda)</label></div>
                            </div>
                        </div>

                        <div class="section-container mb-3 border-start border-4 border-primary">
                            <div class="p-3 bg-light"><h6 class="m-0 fw-bold">3. VESTIRSE</h6></div>
                            <div class="p-3">
                                <div class="form-check mb-2"><input class="form-check-input barthel-radio" type="radio" name="barthel_vestirse" value="10" {{ ($vgi->barthel_vestirse ?? 0) == 10 ? 'checked' : '' }}><label class="form-check-label">Independiente (Incluye botones, cierres, zapatos)</label></div>
                                <div class="form-check mb-2"><input class="form-check-input barthel-radio" type="radio" name="barthel_vestirse" value="5" {{ ($vgi->barthel_vestirse ?? 0) == 5 ? 'checked' : '' }}><label class="form-check-label">Necesita ayuda (hace el 50% solo)</label></div>
                                <div class="form-check"><input class="form-check-input barthel-radio" type="radio" name="barthel_vestirse" value="0" {{ ($vgi->barthel_vestirse ?? 0) == 0 ? 'checked' : '' }}><label class="form-check-label">Dependiente total</label></div>
                            </div>
                        </div>

                        <div class="section-container mb-3 border-start border-4 border-primary">
                            <div class="p-3 bg-light"><h6 class="m-0 fw-bold">4. BAÑARSE / DUCHARSE</h6></div>
                            <div class="p-3">
                                <div class="form-check mb-2"><input class="form-check-input barthel-radio" type="radio" name="barthel_banarse" value="5" {{ ($vgi->barthel_banarse ?? 0) == 5 ? 'checked' : '' }}><label class="form-check-label">Independiente</label></div>
                                <div class="form-check"><input class="form-check-input barthel-radio" type="radio" name="barthel_banarse" value="0" {{ ($vgi->barthel_banarse ?? 0) == 0 ? 'checked' : '' }}><label class="form-check-label">Dependiente</label></div>
                            </div>
                        </div>

                        <div class="section-container mb-3 border-start border-4 border-primary">
                            <div class="p-3 bg-light"><h6 class="m-0 fw-bold">5. CONTROL DE HECES</h6></div>
                            <div class="p-3">
                                <div class="form-check mb-2"><input class="form-check-input barthel-radio" type="radio" name="barthel_heces" value="10" {{ ($vgi->barthel_heces ?? 0) == 10 ? 'checked' : '' }}><label class="form-check-label">Continente (Ningún accidente)</label></div>
                                <div class="form-check mb-2"><input class="form-check-input barthel-radio" type="radio" name="barthel_heces" value="5" {{ ($vgi->barthel_heces ?? 0) == 5 ? 'checked' : '' }}><label class="form-check-label">Accidente ocasional (1 vez por semana)</label></div>
                                <div class="form-check"><input class="form-check-input barthel-radio" type="radio" name="barthel_heces" value="0" {{ ($vgi->barthel_heces ?? 0) == 0 ? 'checked' : '' }}><label class="form-check-label">Incontinente (habitual)</label></div>
                            </div>
                        </div>

                        <div class="section-container mb-3 border-start border-4 border-primary">
                            <div class="p-3 bg-light"><h6 class="m-0 fw-bold">6. CONTROL DE ORINA</h6></div>
                            <div class="p-3">
                                <div class="form-check mb-2"><input class="form-check-input barthel-radio" type="radio" name="barthel_orina" value="10" {{ ($vgi->barthel_orina ?? 0) == 10 ? 'checked' : '' }}><label class="form-check-label">Continente (Al menos 7 días seguidos)</label></div>
                                <div class="form-check mb-2"><input class="form-check-input barthel-radio" type="radio" name="barthel_orina" value="5" {{ ($vgi->barthel_orina ?? 0) == 5 ? 'checked' : '' }}><label class="form-check-label">Accidente ocasional (Máx 1 en 24h)</label></div>
                                <div class="form-check"><input class="form-check-input barthel-radio" type="radio" name="barthel_orina" value="0" {{ ($vgi->barthel_orina ?? 0) == 0 ? 'checked' : '' }}><label class="form-check-label">Incontinente / Sonda</label></div>
                            </div>
                        </div>

                        <div class="section-container mb-3 border-start border-4 border-primary">
                            <div class="p-3 bg-light"><h6 class="m-0 fw-bold">7. USO DE RETRETE</h6></div>
                            <div class="p-3">
                                <div class="form-check mb-2"><input class="form-check-input barthel-radio" type="radio" name="barthel_retrete" value="10" {{ ($vgi->barthel_retrete ?? 0) == 10 ? 'checked' : '' }}><label class="form-check-label">Independiente (Entrar, salir, limpiarse, vestirse)</label></div>
                                <div class="form-check mb-2"><input class="form-check-input barthel-radio" type="radio" name="barthel_retrete" value="5" {{ ($vgi->barthel_retrete ?? 0) == 5 ? 'checked' : '' }}><label class="form-check-label">Necesita ayuda (equilibrio, limpiarse)</label></div>
                                <div class="form-check"><input class="form-check-input barthel-radio" type="radio" name="barthel_retrete" value="0" {{ ($vgi->barthel_retrete ?? 0) == 0 ? 'checked' : '' }}><label class="form-check-label">Dependiente</label></div>
                            </div>
                        </div>

                        <div class="section-container mb-3 border-start border-4 border-primary">
                            <div class="p-3 bg-light"><h6 class="m-0 fw-bold">8. TRASLADO CAMA / SILLÓN</h6></div>
                            <div class="p-3">
                                <div class="form-check mb-2"><input class="form-check-input barthel-radio" type="radio" name="barthel_traslado" value="15" {{ ($vgi->barthel_traslado ?? 0) == 15 ? 'checked' : '' }}><label class="form-check-label">Independiente</label></div>
                                <div class="form-check mb-2"><input class="form-check-input barthel-radio" type="radio" name="barthel_traslado" value="10" {{ ($vgi->barthel_traslado ?? 0) == 10 ? 'checked' : '' }}><label class="form-check-label">Mínima ayuda (física o verbal)</label></div>
                                <div class="form-check mb-2"><input class="form-check-input barthel-radio" type="radio" name="barthel_traslado" value="5" {{ ($vgi->barthel_traslado ?? 0) == 5 ? 'checked' : '' }}><label class="form-check-label">Gran ayuda (1 o 2 personas) / Se mantiene sentado</label></div>
                                <div class="form-check"><input class="form-check-input barthel-radio" type="radio" name="barthel_traslado" value="0" {{ ($vgi->barthel_traslado ?? 0) == 0 ? 'checked' : '' }}><label class="form-check-label">Dependiente / No se mantiene sentado</label></div>
                            </div>
                        </div>

                        <div class="section-container mb-3 border-start border-4 border-primary">
                            <div class="p-3 bg-light"><h6 class="m-0 fw-bold">9. DEAMBULACIÓN</h6></div>
                            <div class="p-3">
                                <div class="form-check mb-2"><input class="form-check-input barthel-radio" type="radio" name="barthel_deambulacion" value="15" {{ ($vgi->barthel_deambulacion ?? 0) == 15 ? 'checked' : '' }}><label class="form-check-label">Independiente (al menos 50m, puede usar bastón)</label></div>
                                <div class="form-check mb-2"><input class="form-check-input barthel-radio" type="radio" name="barthel_deambulacion" value="10" {{ ($vgi->barthel_deambulacion ?? 0) == 10 ? 'checked' : '' }}><label class="form-check-label">Necesita ayuda (física o verbal de 1 persona)</label></div>
                                <div class="form-check mb-2"><input class="form-check-input barthel-radio" type="radio" name="barthel_deambulacion" value="5" {{ ($vgi->barthel_deambulacion ?? 0) == 5 ? 'checked' : '' }}><label class="form-check-label">En silla de ruedas (independiente 50m)</label></div>
                                <div class="form-check"><input class="form-check-input barthel-radio" type="radio" name="barthel_deambulacion" value="0" {{ ($vgi->barthel_deambulacion ?? 0) == 0 ? 'checked' : '' }}><label class="form-check-label">Inmóvil</label></div>
                            </div>
                        </div>

                        <div class="section-container mb-3 border-start border-4 border-primary">
                            <div class="p-3 bg-light"><h6 class="m-0 fw-bold">10. SUBIR Y BAJAR ESCALERAS</h6></div>
                            <div class="p-3">
                                <div class="form-check mb-2"><input class="form-check-input barthel-radio" type="radio" name="barthel_escaleras" value="10" {{ ($vgi->barthel_escaleras ?? 0) == 10 ? 'checked' : '' }}><label class="form-check-label">Independiente</label></div>
                                <div class="form-check mb-2"><input class="form-check-input barthel-radio" type="radio" name="barthel_escaleras" value="5" {{ ($vgi->barthel_escaleras ?? 0) == 5 ? 'checked' : '' }}><label class="form-check-label">Necesita ayuda (física o verbal)</label></div>
                                <div class="form-check"><input class="form-check-input barthel-radio" type="radio" name="barthel_escaleras" value="0" {{ ($vgi->barthel_escaleras ?? 0) == 0 ? 'checked' : '' }}><label class="form-check-label">Incapaz</label></div>
                            </div>
                        </div>

                    </div>

                    <div class="col-lg-4">
                        <div class="card shadow-lg border-0 bg-primary text-white sticky-top" style="top: 100px;">
                            <div class="card-body text-center p-4">
                                <h5 class="fw-bold mb-3"><i class="fas fa-calculator me-2"></i>PUNTUACIÓN BARTHEL</h5>
                                <div class="display-1 fw-bold mb-2" id="barthel_score_display">0</div>
                                <div class="badge bg-white text-primary px-3 py-2 fs-6 rounded-pill w-100" id="barthel_result_display">Sin evaluar</div>
                                
                                <input type="hidden" name="barthel_total" id="input_barthel_total" value="{{ $vgi->barthel_total ?? 0 }}">
                                <input type="hidden" name="barthel_valoracion" id="input_barthel_valoracion" value="{{ $vgi->barthel_valoracion ?? '' }}">
                                
                                <hr class="border-white opacity-25 my-4">
                                <ul class="list-unstyled text-start small opacity-75">
                                    <li><strong>100:</strong> Independiente</li>
                                    <li><strong>≥ 60:</strong> Dependencia Leve</li>
                                    <li><strong>40 - 55:</strong> Dep. Moderada</li>
                                    <li><strong>20 - 35:</strong> Dep. Severa</li>
                                    <li><strong>< 20:</strong> Dep. Total</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PESTAÑA: LAWTON Y BRODY -->
            <div id="tab-lawton" class="vgi-tab-content">
                <div class="section-header mb-4">
                    <div class="header-icon bg-success text-white"><i class="fas fa-tasks"></i></div>
                    <h4 class="header-title text-success">VII. Actividades Instrumentales: Lawton y Brody</h4>
                </div>

                <div class="row">
                    <div class="col-lg-8">
                        
                        <div class="section-container mb-3 border-start border-4 border-success">
                            <div class="p-3 bg-light d-flex justify-content-between">
                                <h6 class="m-0 fw-bold">1. Capacidad para usar el teléfono</h6>
                            </div>
                            <div class="p-3">
                                <div class="form-check mb-2"><input class="form-check-input lawton-radio" type="radio" name="lawton_telefono" value="1" {{ ($vgi->lawton_telefono ?? 0) == 1 ? 'checked' : '' }}><label class="form-check-label">Utiliza el teléfono por iniciativa propia, busca y marca</label></div>
                                <div class="form-check mb-2"><input class="form-check-input lawton-radio" type="radio" name="lawton_telefono" value="1" {{ ($vgi->lawton_telefono ?? 0) == 1 ? 'checked' : '' }}><label class="form-check-label">Es capaz de marcar bien algunos números conocidos</label></div>
                                <div class="form-check mb-2"><input class="form-check-input lawton-radio" type="radio" name="lawton_telefono" value="1" {{ ($vgi->lawton_telefono ?? 0) == 1 ? 'checked' : '' }}><label class="form-check-label">Es capaz de contestar, pero no de marcar</label></div>
                                <div class="form-check"><input class="form-check-input lawton-radio" type="radio" name="lawton_telefono" value="0" {{ ($vgi->lawton_telefono ?? 0) == 0 ? 'checked' : '' }}><label class="form-check-label">No utiliza el teléfono en absoluto</label></div>
                            </div>
                        </div>

                        <div class="section-container mb-3 border-start border-4 border-success">
                            <div class="p-3 bg-light"><h6 class="m-0 fw-bold">2. Preparación de la comida</h6></div>
                            <div class="p-3">
                                <div class="form-check mb-2"><input class="form-check-input lawton-radio" type="radio" name="lawton_comida" value="1" {{ ($vgi->lawton_comida ?? 0) == 1 ? 'checked' : '' }}><label class="form-check-label">Organiza, prepara y sirve las comidas por sí mismo</label></div>
                                <div class="form-check mb-2"><input class="form-check-input lawton-radio" type="radio" name="lawton_comida" value="0" {{ ($vgi->lawton_comida ?? 0) == 0 ? 'checked' : '' }}><label class="form-check-label">Prepara comidas si se le dan los ingredientes</label></div>
                                <div class="form-check mb-2"><input class="form-check-input lawton-radio" type="radio" name="lawton_comida" value="0" {{ ($vgi->lawton_comida ?? 0) == 0 ? 'checked' : '' }}><label class="form-check-label">Prepara, calienta y sirve, pero no sigue dieta</label></div>
                                <div class="form-check"><input class="form-check-input lawton-radio" type="radio" name="lawton_comida" value="0" {{ ($vgi->lawton_comida ?? 0) == 0 ? 'checked' : '' }}><label class="form-check-label">Necesita que le preparen y sirvan</label></div>
                            </div>
                        </div>

                        <div class="section-container mb-3 border-start border-4 border-success">
                            <div class="p-3 bg-light"><h6 class="m-0 fw-bold">3. Responsabilidad respecto a su medicación</h6></div>
                            <div class="p-3">
                                <div class="form-check mb-2"><input class="form-check-input lawton-radio" type="radio" name="lawton_medicacion" value="1" {{ ($vgi->lawton_medicacion ?? 0) == 1 ? 'checked' : '' }}><label class="form-check-label">Capaz de tomarla a su hora y dosis correcta</label></div>
                                <div class="form-check mb-2"><input class="form-check-input lawton-radio" type="radio" name="lawton_medicacion" value="0" {{ ($vgi->lawton_medicacion ?? 0) == 0 ? 'checked' : '' }}><label class="form-check-label">Toma su medicación si se le prepara</label></div>
                                <div class="form-check"><input class="form-check-input lawton-radio" type="radio" name="lawton_medicacion" value="0" {{ ($vgi->lawton_medicacion ?? 0) == 0 ? 'checked' : '' }}><label class="form-check-label">No es capaz de administrarse su medicación</label></div>
                            </div>
                        </div>

                        <div class="section-container mb-3 border-start border-4 border-success">
                            <div class="p-3 bg-light"><h6 class="m-0 fw-bold">4. Cuidado de la casa</h6></div>
                            <div class="p-3">
                                <div class="form-check mb-2"><input class="form-check-input lawton-radio" type="radio" name="lawton_casa" value="1" {{ ($vgi->lawton_casa ?? 0) == 1 ? 'checked' : '' }}><label class="form-check-label">Mantiene la casa solo o con ayuda ocasional</label></div>
                                <div class="form-check mb-2"><input class="form-check-input lawton-radio" type="radio" name="lawton_casa" value="1" {{ ($vgi->lawton_casa ?? 0) == 1 ? 'checked' : '' }}><label class="form-check-label">Realiza tareas ligeras (platos, camas)</label></div>
                                <div class="form-check mb-2"><input class="form-check-input lawton-radio" type="radio" name="lawton_casa" value="1" {{ ($vgi->lawton_casa ?? 0) == 1 ? 'checked' : '' }}><label class="form-check-label">Realiza tareas ligeras pero no mantiene limpieza</label></div>
                                <div class="form-check mb-2"><input class="form-check-input lawton-radio" type="radio" name="lawton_casa" value="1" {{ ($vgi->lawton_casa ?? 0) == 1 ? 'checked' : '' }}><label class="form-check-label">Necesita ayuda en todas las labores</label></div>
                                <div class="form-check"><input class="form-check-input lawton-radio" type="radio" name="lawton_casa" value="0" {{ ($vgi->lawton_casa ?? 0) == 0 ? 'checked' : '' }}><label class="form-check-label">No participa en ninguna labor</label></div>
                            </div>
                        </div>

                        <div class="section-container mb-3 border-start border-4 border-success">
                            <div class="p-3 bg-light"><h6 class="m-0 fw-bold">5. Ir de compras</h6></div>
                            <div class="p-3">
                                <div class="form-check mb-2"><input class="form-check-input lawton-radio" type="radio" name="lawton_compras" value="1" {{ ($vgi->lawton_compras ?? 0) == 1 ? 'checked' : '' }}><label class="form-check-label">Realiza todas las compras independientemente</label></div>
                                <div class="form-check mb-2"><input class="form-check-input lawton-radio" type="radio" name="lawton_compras" value="0" {{ ($vgi->lawton_compras ?? 0) == 0 ? 'checked' : '' }}><label class="form-check-label">Realiza independientemente pequeñas compras</label></div>
                                <div class="form-check mb-2"><input class="form-check-input lawton-radio" type="radio" name="lawton_compras" value="0" {{ ($vgi->lawton_compras ?? 0) == 0 ? 'checked' : '' }}><label class="form-check-label">Necesita ir acompañado para comprar</label></div>
                                <div class="form-check"><input class="form-check-input lawton-radio" type="radio" name="lawton_compras" value="0" {{ ($vgi->lawton_compras ?? 0) == 0 ? 'checked' : '' }}><label class="form-check-label">Totalmente incapaz de comprar</label></div>
                            </div>
                        </div>

                        <div class="section-container mb-3 border-start border-4 border-success">
                            <div class="p-3 bg-light"><h6 class="m-0 fw-bold">6. Lavado de ropa</h6></div>
                            <div class="p-3">
                                <div class="form-check mb-2"><input class="form-check-input lawton-radio" type="radio" name="lawton_ropa" value="1" {{ ($vgi->lawton_ropa ?? 0) == 1 ? 'checked' : '' }}><label class="form-check-label">Lava por sí mismo toda su ropa</label></div>
                                <div class="form-check mb-2"><input class="form-check-input lawton-radio" type="radio" name="lawton_ropa" value="1" {{ ($vgi->lawton_ropa ?? 0) == 1 ? 'checked' : '' }}><label class="form-check-label">Lava por sí mismo pequeñas prendas</label></div>
                                <div class="form-check"><input class="form-check-input lawton-radio" type="radio" name="lawton_ropa" value="0" {{ ($vgi->lawton_ropa ?? 0) == 0 ? 'checked' : '' }}><label class="form-check-label">Todo el lavado debe ser realizado por otro</label></div>
                            </div>
                        </div>

                        <div class="section-container mb-3 border-start border-4 border-success">
                            <div class="p-3 bg-light"><h6 class="m-0 fw-bold">7. Uso de medios de transporte</h6></div>
                            <div class="p-3">
                                <div class="form-check mb-2"><input class="form-check-input lawton-radio" type="radio" name="lawton_transporte" value="1" {{ ($vgi->lawton_transporte ?? 0) == 1 ? 'checked' : '' }}><label class="form-check-label">Viaja solo en transporte público o conduce</label></div>
                                <div class="form-check mb-2"><input class="form-check-input lawton-radio" type="radio" name="lawton_transporte" value="1" {{ ($vgi->lawton_transporte ?? 0) == 1 ? 'checked' : '' }}><label class="form-check-label">Capaz de coger un taxi, pero no otro medio</label></div>
                                <div class="form-check mb-2"><input class="form-check-input lawton-radio" type="radio" name="lawton_transporte" value="1" {{ ($vgi->lawton_transporte ?? 0) == 1 ? 'checked' : '' }}><label class="form-check-label">Viaja en transporte público acompañado</label></div>
                                <div class="form-check mb-2"><input class="form-check-input lawton-radio" type="radio" name="lawton_transporte" value="0" {{ ($vgi->lawton_transporte ?? 0) == 0 ? 'checked' : '' }}><label class="form-check-label">Utiliza taxi/auto con ayuda de otros</label></div>
                                <div class="form-check"><input class="form-check-input lawton-radio" type="radio" name="lawton_transporte" value="0" {{ ($vgi->lawton_transporte ?? 0) == 0 ? 'checked' : '' }}><label class="form-check-label">No viaja en absoluto</label></div>
                            </div>
                        </div>

                        <div class="section-container mb-3 border-start border-4 border-success">
                            <div class="p-3 bg-light"><h6 class="m-0 fw-bold">8. Manejo de asuntos económicos</h6></div>
                            <div class="p-3">
                                <div class="form-check mb-2"><input class="form-check-input lawton-radio" type="radio" name="lawton_finanzas" value="1" {{ ($vgi->lawton_finanzas ?? 0) == 1 ? 'checked' : '' }}><label class="form-check-label">Maneja asuntos financieros con independencia</label></div>
                                <div class="form-check mb-2"><input class="form-check-input lawton-radio" type="radio" name="lawton_finanzas" value="0" {{ ($vgi->lawton_finanzas ?? 0) == 0 ? 'checked' : '' }}><label class="form-check-label">Realiza compras diarias, necesita ayuda en grandes</label></div>
                                <div class="form-check"><input class="form-check-input lawton-radio" type="radio" name="lawton_finanzas" value="0" {{ ($vgi->lawton_finanzas ?? 0) == 0 ? 'checked' : '' }}><label class="form-check-label">Incapaz de manejar dinero</label></div>
                            </div>
                        </div>

                    </div>

                    <div class="col-lg-4">
                        <div class="card shadow-lg border-0 bg-success text-white sticky-top" style="top: 100px;">
                            <div class="card-body text-center p-4">
                                <h5 class="fw-bold mb-3"><i class="fas fa-calculator me-2"></i>PUNTUACIÓN LAWTON</h5>
                                <div class="display-1 fw-bold mb-2" id="lawton_score_display">0</div>
                                <div class="badge bg-white text-success px-3 py-2 fs-6 rounded-pill w-100" id="lawton_result_display">Sin evaluar</div>
                                
                                <input type="hidden" name="lawton_total" id="input_lawton_total" value="{{ $vgi->lawton_total ?? 0 }}">
                                <input type="hidden" name="lawton_valoracion" id="input_lawton_valoracion" value="{{ $vgi->lawton_valoracion ?? '' }}">
                                
                                <hr class="border-white opacity-25 my-4">
                                <div class="text-start small opacity-75">
                                    <p class="mb-1 fw-bold">Interpretación:</p>
                                    <ul class="list-unstyled mb-0">
                                        <li><i class="fas fa-female me-1"></i> <strong>Mujer:</strong> 0-7 Dependiente / 8 Independiente</li>
                                        <li><i class="fas fa-male me-1"></i> <strong>Hombre:</strong> 0-5 Dependiente / 6-8 Independiente</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PESTAÑA: PFEIFFER DETALLADO -->
            <div id="tab-pfeiffer" class="vgi-tab-content">
                <div class="section-header mb-4">
                    <div class="header-icon bg-warning text-white"><i class="fas fa-brain"></i></div>
                    <h4 class="header-title text-warning">VIII. Escala Cognitiva: Test de Pfeiffer (SPMSQ)</h4>
                </div>

                <div class="row">
                    <div class="col-lg-8">
                        <div class="section-container">
                            <div class="section-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0 align-middle">
                                        <thead class="bg-light text-uppercase small text-muted">
                                            <tr>
                                                <th class="ps-4 py-3">Pregunta</th>
                                                <th class="text-center" style="width: 120px;">Correcto</th>
                                                <th class="text-center" style="width: 120px;">Incorrecto</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $preguntas = [
                                                    'pf_fecha' => '1. ¿Cuál es la fecha de hoy? (día, mes, año)',
                                                    'pf_dia' => '2. ¿Qué día de la semana es hoy?',
                                                    'pf_lugar' => '3. ¿En qué lugar estamos?',
                                                    'pf_telefono' => '4a. ¿Cuál es su número de teléfono?',
                                                    'pf_direccion' => '4b. ¿Cuál es su dirección completa? (Si no tiene telf.)',
                                                    'pf_edad' => '5. ¿Cuántos años tiene?',
                                                    'pf_nacer' => '6. ¿Dónde nació?',
                                                    'pf_pres_act' => '7. ¿Nombre del Presidente del Gobierno actual?',
                                                    'pf_pres_ant' => '8. ¿Nombre del Presidente anterior?',
                                                    'pf_madre' => '9. Dígame el primer apellido de su madre',
                                                    'pf_resta' => '10. Restar de 3 en 3 desde 30 (30-3=27, ...)'
                                                ];
                                            @endphp

                                            @foreach($preguntas as $key => $pregunta)
                                            <tr>
                                                <td class="ps-4 fw-500">{{ $pregunta }}</td>
                                                <td class="text-center">
                                                    <div class="form-check d-inline-block">
                                                        <input class="form-check-input pfeiffer-radio border-success" type="radio" name="{{ $key }}" value="0" {{ ($vgi->$key ?? 0) == 0 ? 'checked' : '' }}>
                                                    </div>
                                                </td>
                                                <td class="text-center bg-soft-danger-hover">
                                                    <div class="form-check d-inline-block">
                                                        <input class="form-check-input pfeiffer-radio border-danger bg-danger-subtle" type="radio" name="{{ $key }}" value="1" {{ ($vgi->$key ?? 0) == 1 ? 'checked' : '' }}>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="alert alert-info mt-3 border-0 shadow-sm">
                            <i class="fas fa-info-circle me-2"></i> <strong>Nota:</strong> Marque "Incorrecto" si el paciente se equivoca. El puntaje se basa en la cantidad de errores.
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card shadow-lg border-0 bg-warning text-dark sticky-top" style="top: 100px;">
                            <div class="card-body text-center p-4">
                                <h5 class="fw-bold mb-3"><i class="fas fa-poll me-2"></i>RESULTADO PFEIFFER</h5>
                                
                                <div class="bg-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3 shadow-sm" style="width: 100px; height: 100px;">
                                    <div class="display-4 fw-bold text-warning" id="pfeiffer_score_display">0</div>
                                </div>
                                <p class="mb-2 fw-bold">ERRORES</p>
                                
                                <div class="badge bg-dark text-white px-3 py-2 fs-6 rounded-pill w-100 mb-3" id="pfeiffer_result_display">INTACTO</div>
                                
                                <input type="hidden" name="pfeiffer_errores" id="input_pfeiffer_errores" value="{{ $vgi->pfeiffer_errores ?? 0 }}">
                                <input type="hidden" name="pfeiffer_valoracion" id="input_pfeiffer_valoracion" value="{{ $vgi->pfeiffer_valoracion ?? '' }}">
                                
                                <hr class="border-dark opacity-25 my-4">
                                <div class="text-start small opacity-75">
                                    <p class="mb-1 fw-bold">Interpretación:</p>
                                    <ul class="list-unstyled mb-0">
                                        <li><strong>0-2:</strong> Normal / Intacto</li>
                                        <li><strong>3-4:</strong> Deterioro Leve</li>
                                        <li><strong>5-7:</strong> Deterioro Moderado</li>
                                        <li><strong>8-10:</strong> Deterioro Severo</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PESTAÑA: RUDAS (Rowland Universal Dementia Assessment Scale) -->
            <div id="tab-rudas" class="vgi-tab-content">
                <div class="section-header mb-4">
                    <div class="header-icon bg-indigo text-white"><i class="fas fa-puzzle-piece"></i></div>
                    <h4 class="header-title text-indigo">IX. Escala RUDAS (Rowland Universal Dementia Assessment Scale)</h4>
                </div>

                <div class="row g-4">
                    <div class="col-lg-7">
                        
                        <div class="section-container mb-4">
                            <div class="section-header bg-light"><h6 class="m-0 fw-bold">1. Orientación (Máx 8 puntos)</h6></div>
                            <div class="section-body p-3">
                                <p class="text-muted small mb-2">Marque si la respuesta es correcta (2 puntos c/u):</p>
                                <div class="form-check mb-2">
                                    <input class="form-check-input rudas-check" type="checkbox" value="2" name="rudas_orientacion_1" {{ (($vgi->rudas_orientacion ?? 0) >= 2) ? 'checked' : '' }}>
                                    <label class="form-check-label">¿En qué lugar estamos ahora?</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input rudas-check" type="checkbox" value="2" name="rudas_orientacion_2" {{ (($vgi->rudas_orientacion ?? 0) >= 4) ? 'checked' : '' }}>
                                    <label class="form-check-label">¿En qué ciudad o distrito nos encontramos?</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input rudas-check" type="checkbox" value="2" name="rudas_orientacion_3" {{ (($vgi->rudas_orientacion ?? 0) >= 6) ? 'checked' : '' }}>
                                    <label class="form-check-label">¿Qué fecha es hoy? (día, mes, año)</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input rudas-check" type="checkbox" value="2" name="rudas_orientacion_4" {{ (($vgi->rudas_orientacion ?? 0) == 8) ? 'checked' : '' }}>
                                    <label class="form-check-label">¿Qué día de la semana es hoy?</label>
                                </div>
                                <input type="hidden" name="rudas_orientacion" id="val_rudas_1" value="{{ $vgi->rudas_orientacion ?? 0 }}">
                            </div>
                        </div>

                        <div class="section-container mb-4">
                            <div class="section-header bg-light"><h6 class="m-0 fw-bold">2. Funciones Motoras Práxicas (Máx 2)</h6></div>
                            <div class="section-body p-3">
                                <div class="row align-items-center">
                                    <div class="col-md-7">
                                        <p class="mb-2">Gesto a imitar: <strong>Puño -> Canto -> Palma</strong></p>
                                        
                                        <img src="https://i.postimg.cc/wT6ygCJz/Captura-de-pantalla-2026-01-06-235601.png" alt="Imagen: Puño - Canto - Palma">
                                    </div>
                                    <div class="col-md-5">
                                        <label class="label-input">Puntaje obtenido:</label>
                                        <input type="number" name="rudas_praxis" id="val_rudas_2" min="0" max="2" class="form-control modern-input text-center fs-4 fw-bold rudas-input" value="{{ $vgi->rudas_praxis ?? 0 }}">
                                        <small class="text-muted d-block text-center mt-1">0 a 2 puntos</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="section-container mb-4">
                            <div class="section-header bg-light"><h6 class="m-0 fw-bold">3. Praxis Visoconstructiva (Máx 4)</h6></div>
                            <div class="section-body p-3">
                                <div class="row align-items-center">
                                    <div class="col-md-7">
                                        <p class="mb-2">Copiar un cubo o dos pentágonos superpuestos.</p>
                                        
                                        <img src="https://stimuluspro.com/www/book/imagenes/891/test-MEC.png" alt="Imagen: Cubo / Pentágonos">
                                    </div>
                                    <div class="col-md-5">
                                        <label class="label-input">Puntaje obtenido:</label>
                                        <input type="number" name="rudas_visoconstructiva" id="val_rudas_3" min="0" max="4" class="form-control modern-input text-center fs-4 fw-bold rudas-input" value="{{ $vgi->rudas_visoconstructiva ?? 0 }}">
                                        <small class="text-muted d-block text-center mt-1">0 a 4 puntos</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="section-container mb-4">
                            <div class="section-header bg-light"><h6 class="m-0 fw-bold">4. Juicio / Función Ejecutiva (Máx 4)</h6></div>
                            <div class="section-body p-3 d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="mb-0 fw-bold">Pregunta:</p>
                                    <p class="mb-0 fst-italic">"Si hubiera humo en la cocina, ¿qué haría?"</p>
                                </div>
                                <div style="width: 100px;">
                                    <input type="number" name="rudas_juicio" id="val_rudas_4" min="0" max="4" class="form-control modern-input text-center fs-4 fw-bold rudas-input" value="{{ $vgi->rudas_juicio ?? 0 }}">
                                </div>
                            </div>
                        </div>

                        <div class="section-container mb-4">
                            <div class="section-header bg-light"><h6 class="m-0 fw-bold">5. Lenguaje (Máx 6)</h6></div>
                            <div class="section-body p-3 d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="mb-0">Denominación de objetos + Repetición oral.</p>
                                </div>
                                <div style="width: 100px;">
                                    <input type="number" name="rudas_lenguaje" id="val_rudas_5" min="0" max="6" class="form-control modern-input text-center fs-4 fw-bold rudas-input" value="{{ $vgi->rudas_lenguaje ?? 0 }}">
                                </div>
                            </div>
                        </div>

                        <div class="section-container mb-4">
                            <div class="section-header bg-light"><h6 class="m-0 fw-bold">6. Memoria (Máx 6)</h6></div>
                            <div class="section-body p-3 d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="mb-0">Aprendizaje y recuerdo diferido de 5 palabras.</p>
                                </div>
                                <div style="width: 100px;">
                                    <input type="number" name="rudas_memoria" id="val_rudas_6" min="0" max="6" class="form-control modern-input text-center fs-4 fw-bold rudas-input" value="{{ $vgi->rudas_memoria ?? 0 }}">
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-lg-5">
                        <div class="card shadow-lg border-0 bg-white sticky-top" style="top: 100px;">
                            <div class="card-header bg-indigo text-white py-3">
                                <h5 class="m-0 fw-bold"><i class="fas fa-calculator me-2"></i>Registro Final RUDAS</h5>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-striped mb-0">
                                    <thead class="small text-uppercase text-muted">
                                        <tr>
                                            <th class="ps-4">Dominio</th>
                                            <th class="text-end pe-4">Puntaje</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr><td class="ps-4">1. Orientación</td><td class="text-end pe-4 fw-bold"><span id="txt_rudas_1">0</span> / 8</td></tr>
                                        <tr><td class="ps-4">2. Función motora</td><td class="text-end pe-4 fw-bold"><span id="txt_rudas_2">0</span> / 2</td></tr>
                                        <tr><td class="ps-4">3. Visoconstructiva</td><td class="text-end pe-4 fw-bold"><span id="txt_rudas_3">0</span> / 4</td></tr>
                                        <tr><td class="ps-4">4. Juicio</td><td class="text-end pe-4 fw-bold"><span id="txt_rudas_4">0</span> / 4</td></tr>
                                        <tr><td class="ps-4">5. Lenguaje</td><td class="text-end pe-4 fw-bold"><span id="txt_rudas_5">0</span> / 6</td></tr>
                                        <tr><td class="ps-4">6. Memoria</td><td class="text-end pe-4 fw-bold"><span id="txt_rudas_6">0</span> / 6</td></tr>
                                        <tr class="bg-indigo-light">
                                            <td class="ps-4 fw-bold text-indigo">TOTAL</td>
                                            <td class="text-end pe-4 fs-4 fw-bold text-indigo"><span id="rudas_total_display">0</span> / 30</td>
                                        </tr>
                                    </tbody>
                                </table>
                                
                                <div class="p-3 text-center border-top">
                                    <span class="badge w-100 py-2 fs-6" id="rudas_interpretation_badge">Sin evaluar</span>
                                </div>

                                <input type="hidden" name="rudas_total" id="input_rudas_total" value="{{ $vgi->rudas_total ?? 0 }}">
                                <input type="hidden" name="rudas_valoracion" id="input_rudas_valoracion" value="{{ $vgi->rudas_valoracion ?? '' }}">
                            </div>
                            <div class="card-footer bg-light p-3 small text-muted">
                                <strong>Interpretación:</strong><br>
                                • 23–30: Rango esperado.<br>
                                • ≤ 22: Sospecha de deterioro cognitivo.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PESTAÑA: MMSE DE FOLSTEIN CON CANDADO -->
            <div id="tab-mmse" class="vgi-tab-content">
                
                <div class="locked-wrapper">
                    
                    <div class="lock-overlay" id="mmse_lock_screen">
                        <div class="lock-card">
                            <div class="mb-3 text-danger">
                                <i class="fas fa-user-md fa-4x"></i>
                                <i class="fas fa-lock fa-2x" style="margin-left: -15px; vertical-align: bottom; color: #333; background: white; border-radius: 50%;"></i>
                            </div>
                            <h4 class="fw-bold text-dark">Acceso Restringido</h4>
                            <p class="text-muted small">Esta evaluación es exclusiva para personal clínico autorizado. Ingrese su PIN de seguridad.</p>
                            
                            <input type="password" id="security_pin" class="pin-input" placeholder="****" maxlength="4">
                            
                            <button type="button" class="btn btn-danger w-100 fw-bold py-2 rounded-pill shadow-sm" onclick="unlockMMSE()">
                                <i class="fas fa-unlock me-2"></i> DESBLOQUEAR
                            </button>
                            <p id="pin_error" class="text-danger small mt-2 fw-bold" style="display:none;">
                                <i class="fas fa-times-circle"></i> PIN Incorrecto
                            </p>
                        </div>
                    </div>

                    <div class="locked-content" id="mmse_content">
                        
                        <div class="section-header mb-4">
                            <div class="header-icon bg-dark text-white"><i class="fas fa-brain"></i></div>
                            <h4 class="header-title text-dark">X. MMSE de Folstein (Mini Mental State Examination)</h4>
                        </div>

                        <div class="row g-4">
                            <div class="col-lg-8">
                                
                                <div class="section-container mb-4">
                                    <div class="section-header bg-light"><h6 class="m-0 fw-bold">1. Orientación (Máx 10 puntos)</h6></div>
                                    <div class="section-body p-3">
                                        <div class="row">
                                            <div class="col-md-6 border-end">
                                                <p class="fw-bold small text-muted text-uppercase mb-2">Tiempo (5 pts)</p>
                                                @foreach(['mmse_tiempo_anio'=>'Año', 'mmse_tiempo_estacion'=>'Estación', 'mmse_tiempo_fecha'=>'Fecha', 'mmse_tiempo_dia'=>'Día', 'mmse_tiempo_mes'=>'Mes'] as $key => $label)
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input mmse-check" type="checkbox" name="{{ $key }}" value="1" {{ ($vgi->$key ?? 0) == 1 ? 'checked' : '' }}>
                                                    <label class="form-check-label">{{ $label }}</label>
                                                </div>
                                                @endforeach
                                            </div>
                                            <div class="col-md-6 ps-4">
                                                <p class="fw-bold small text-muted text-uppercase mb-2">Lugar (5 pts)</p>
                                                @foreach(['mmse_lugar_pais'=>'País', 'mmse_lugar_dep'=>'Departamento', 'mmse_lugar_dist'=>'Distrito', 'mmse_lugar_hosp'=>'Hospital / Lugar', 'mmse_lugar_piso'=>'Piso / Consultorio'] as $key => $label)
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input mmse-check" type="checkbox" name="{{ $key }}" value="1" {{ ($vgi->$key ?? 0) == 1 ? 'checked' : '' }}>
                                                    <label class="form-check-label">{{ $label }}</label>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="section-container mb-4">
                                    <div class="section-header bg-light"><h6 class="m-0 fw-bold">2. Memoria Inmediata (Máx 3 puntos)</h6></div>
                                    <div class="section-body p-3">
                                        <p class="small text-muted mb-2">Repetir 3 nombres. Marque si lo logra al primer intento.</p>
                                        <div class="d-flex gap-4 mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input mmse-check" type="checkbox" name="mmse_mem_arbol" value="1" {{ ($vgi->mmse_mem_arbol ?? 0) == 1 ? 'checked' : '' }}>
                                                <label class="form-check-label fw-bold">Árbol</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input mmse-check" type="checkbox" name="mmse_mem_puente" value="1" {{ ($vgi->mmse_mem_puente ?? 0) == 1 ? 'checked' : '' }}>
                                                <label class="form-check-label fw-bold">Puente</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input mmse-check" type="checkbox" name="mmse_mem_farol" value="1" {{ ($vgi->mmse_mem_farol ?? 0) == 1 ? 'checked' : '' }}>
                                                <label class="form-check-label fw-bold">Farol</label>
                                            </div>
                                        </div>
                                        <div class="input-group input-group-sm w-50">
                                            <span class="input-group-text">N° de ensayos necesarios:</span>
                                            <input type="number" name="mmse_mem_intentos" class="form-control" value="{{ $vgi->mmse_mem_intentos ?? '' }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="section-container mb-4">
                                    <div class="section-header bg-light"><h6 class="m-0 fw-bold">3. Atención y Cálculo (Máx 5 puntos)</h6></div>
                                    <div class="section-body p-3">
                                        <p class="small text-muted mb-2">Restar 7 de 100 consecutivamente <strong>O</strong> deletrear MUNDO al revés. (Marque 1 por cada acierto).</p>
                                        <div class="d-flex justify-content-between text-center px-3">
                                            <div>
                                                <label class="d-block small fw-bold">93 / O</label>
                                                <input class="form-check-input mmse-check scale-15" type="checkbox" name="mmse_atencion_1" value="1" {{ ($vgi->mmse_atencion_1 ?? 0) == 1 ? 'checked' : '' }}>
                                            </div>
                                            <div>
                                                <label class="d-block small fw-bold">86 / D</label>
                                                <input class="form-check-input mmse-check scale-15" type="checkbox" name="mmse_atencion_2" value="1" {{ ($vgi->mmse_atencion_2 ?? 0) == 1 ? 'checked' : '' }}>
                                            </div>
                                            <div>
                                                <label class="d-block small fw-bold">79 / N</label>
                                                <input class="form-check-input mmse-check scale-15" type="checkbox" name="mmse_atencion_3" value="1" {{ ($vgi->mmse_atencion_3 ?? 0) == 1 ? 'checked' : '' }}>
                                            </div>
                                            <div>
                                                <label class="d-block small fw-bold">72 / U</label>
                                                <input class="form-check-input mmse-check scale-15" type="checkbox" name="mmse_atencion_4" value="1" {{ ($vgi->mmse_atencion_4 ?? 0) == 1 ? 'checked' : '' }}>
                                            </div>
                                            <div>
                                                <label class="d-block small fw-bold">65 / M</label>
                                                <input class="form-check-input mmse-check scale-15" type="checkbox" name="mmse_atencion_5" value="1" {{ ($vgi->mmse_atencion_5 ?? 0) == 1 ? 'checked' : '' }}>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="section-container mb-4">
                                    <div class="section-header bg-light"><h6 class="m-0 fw-bold">4. Recuerdo Diferido (Máx 3 puntos)</h6></div>
                                    <div class="section-body p-3">
                                        <div class="d-flex gap-4">
                                            <div class="form-check">
                                                <input class="form-check-input mmse-check" type="checkbox" name="mmse_rec_arbol" value="1" {{ ($vgi->mmse_rec_arbol ?? 0) == 1 ? 'checked' : '' }}>
                                                <label class="form-check-label">Árbol</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input mmse-check" type="checkbox" name="mmse_rec_puente" value="1" {{ ($vgi->mmse_rec_puente ?? 0) == 1 ? 'checked' : '' }}>
                                                <label class="form-check-label">Puente</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input mmse-check" type="checkbox" name="mmse_rec_farol" value="1" {{ ($vgi->mmse_rec_farol ?? 0) == 1 ? 'checked' : '' }}>
                                                <label class="form-check-label">Farol</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="section-container mb-4">
                                    <div class="section-header bg-light"><h6 class="m-0 fw-bold">5. Lenguaje y Construcción (Máx 9 puntos)</h6></div>
                                    <div class="section-body p-3">
                                        <div class="row g-3">
                                            <div class="col-12 border-bottom pb-2">
                                                <span class="fw-bold small d-block mb-1">Denominación:</span>
                                                <div class="d-flex gap-4">
                                                    <div class="form-check">
                                                        <input class="form-check-input mmse-check" type="checkbox" name="mmse_nom_lapiz" value="1" {{ ($vgi->mmse_nom_lapiz ?? 0) == 1 ? 'checked' : '' }}>
                                                        <label class="form-check-label">Lápiz</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input mmse-check" type="checkbox" name="mmse_nom_reloj" value="1" {{ ($vgi->mmse_nom_reloj ?? 0) == 1 ? 'checked' : '' }}>
                                                        <label class="form-check-label">Reloj</label>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-12 border-bottom pb-2">
                                                <div class="form-check">
                                                    <input class="form-check-input mmse-check" type="checkbox" name="mmse_repeticion" value="1" {{ ($vgi->mmse_repeticion ?? 0) == 1 ? 'checked' : '' }}>
                                                    <label class="form-check-label">Repite: <em>"En un trigal había 5 perros"</em></label>
                                                </div>
                                            </div>

                                            <div class="col-12 border-bottom pb-2">
                                                <span class="fw-bold small d-block mb-1">Órdenes (3 pts):</span>
                                                <div class="form-check"><input class="form-check-input mmse-check" type="checkbox" name="mmse_orden_mano" value="1" {{ ($vgi->mmse_orden_mano ?? 0) == 1 ? 'checked' : '' }}><label>Coja este papel con mano derecha</label></div>
                                                <div class="form-check"><input class="form-check-input mmse-check" type="checkbox" name="mmse_orden_doblar" value="1" {{ ($vgi->mmse_orden_doblar ?? 0) == 1 ? 'checked' : '' }}><label>Dóblelo por la mitad</label></div>
                                                <div class="form-check"><input class="form-check-input mmse-check" type="checkbox" name="mmse_orden_suelo" value="1" {{ ($vgi->mmse_orden_suelo ?? 0) == 1 ? 'checked' : '' }}><label>Póngalo en el suelo</label></div>
                                            </div>

                                            <div class="col-12">
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input mmse-check" type="checkbox" name="mmse_leer" value="1" {{ ($vgi->mmse_leer ?? 0) == 1 ? 'checked' : '' }}>
                                                    <label class="form-check-label">Lee y ejecuta: <em>"CIERRE LOS OJOS"</em></label>
                                                </div>
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input mmse-check" type="checkbox" name="mmse_escribir" value="1" {{ ($vgi->mmse_escribir ?? 0) == 1 ? 'checked' : '' }}>
                                                    <label class="form-check-label">Escribe frase con sujeto y predicado</label>
                                                </div>
                                                <div class="form-check d-flex align-items-center">
                                                    <input class="form-check-input mmse-check" type="checkbox" name="mmse_copiar" value="1" {{ ($vgi->mmse_copiar ?? 0) == 1 ? 'checked' : '' }}>
                                                    <div class="ms-2">
                                                        <label class="form-check-label d-block">Copiar el dibujo (Pentágonos)</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="col-lg-4">
                                <div class="card shadow-lg border-0 bg-dark text-white sticky-top" style="top: 100px;">
                                    <div class="card-body text-center p-4">
                                        <h5 class="fw-bold mb-3"><i class="fas fa-calculator me-2"></i>PUNTAJE MMSE</h5>
                                        
                                        <div class="bg-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3 shadow-sm" style="width: 100px; height: 100px;">
                                            <div class="display-4 fw-bold text-dark" id="mmse_score_display">0</div>
                                        </div>
                                        <p class="mb-2 fw-bold text-uppercase text-white-50">Sobre 30 puntos</p>
                                        
                                        <div class="badge bg-secondary px-3 py-2 fs-6 rounded-pill w-100 mb-3" id="mmse_result_display">Sin evaluar</div>
                                        
                                        <input type="hidden" name="mmse_total_final" id="input_mmse_total" value="{{ $vgi->mmse_total_final ?? 0 }}">
                                        <input type="hidden" name="mmse_valoracion_final" id="input_mmse_valoracion" value="{{ $vgi->mmse_valoracion_final ?? '' }}">
                                        
                                        <hr class="border-white opacity-25 my-4">
                                        <div class="text-start small opacity-75">
                                            <p class="mb-1 fw-bold">Referencia (Folstein):</p>
                                            <ul class="list-unstyled mb-0">
                                                <li><span class="text-success">●</span> 27-30: Normal</li>
                                                <li><span class="text-warning">●</span> 24-26: Sospecha Patológica</li>
                                                <li><span class="text-warning">●</span> 12-24: Deterioro</li>
                                                <li><span class="text-danger">●</span> 9-12: Demencia</li>
                                                <li><span class="text-danger">●</span> <9: Demencia Severa</li>
                                            </ul>
                                            <p class="mt-2 fst-italic" style="font-size: 0.8em;">*Ajustar según nivel educativo.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div> <!-- Fin locked-content -->
                </div> <!-- Fin locked-wrapper -->
            </div>

            <!-- PESTAÑA: MINI-COG -->
            <div id="tab-minicog" class="vgi-tab-content">
                
                <div class="locked-wrapper">
                    <div class="lock-overlay" id="minicog_lock_screen">
                        <div class="lock-card border-danger">
                            <div class="mb-3 text-danger"><i class="fas fa-stopwatch fa-4x"></i><i class="fas fa-lock fa-2x" style="margin-left: -15px; vertical-align: bottom; background: white; border-radius: 50%;"></i></div>
                            <h4 class="fw-bold text-dark">Evaluación Clínica Protegida</h4>
                            <p class="text-muted small">El Test Mini-Cog es exclusivo para personal autorizado.</p>
                            <input type="password" id="minicog_pin" class="pin-input" placeholder="****" maxlength="4">
                            <button type="button" class="btn btn-danger w-100 fw-bold py-2 rounded-pill shadow-sm" onclick="unlockMinicog()"><i class="fas fa-unlock me-2"></i> DESBLOQUEAR</button>
                            <p id="minicog_pin_error" class="text-danger small mt-2 fw-bold" style="display:none;"><i class="fas fa-times-circle"></i> PIN Incorrecto</p>
                        </div>
                    </div>

                    <div class="locked-content" id="minicog_content">
                        <div class="section-header mb-4">
                            <div class="header-icon bg-indigo text-white"><i class="fas fa-stopwatch"></i></div>
                            <h4 class="header-title text-indigo">XI. Evaluación Cognitiva: Mini-Cog ©</h4>
                        </div>

                        <div class="row g-4">
                            <div class="col-lg-7">
                                
                                <div class="section-container mb-4">
                                    <div class="section-header bg-light"><h6 class="m-0 fw-bold">1. Registro de Palabras</h6></div>
                                    <div class="section-body p-4">
                                        <p class="mb-2 text-dark">Pida al paciente que repita y aprenda estas 3 palabras:</p>
                                        <div class="d-flex justify-content-center gap-3 my-3">
                                            <span class="badge bg-soft-purple text-purple fs-6 px-4 py-2 border">MESA</span>
                                            <span class="badge bg-soft-purple text-purple fs-6 px-4 py-2 border">LLAVE</span>
                                            <span class="badge bg-soft-purple text-purple fs-6 px-4 py-2 border">LIBRO</span>
                                        </div>
                                        <p class="small text-muted fst-italic mb-0"><i class="fas fa-info-circle"></i> Avise que las preguntará de nuevo en 3 minutos.</p>
                                    </div>
                                </div>

                                <div class="section-container mb-4">
                                    <div class="section-header bg-light"><h6 class="m-0 fw-bold">2. Test del Reloj (Distractor)</h6></div>
                                    <div class="section-body p-4">
                                        <p class="small text-muted">Instrucción: "Dibuje un reloj circular con todos los números y las manecillas marcando las 11:10".</p>
                                        
                                        <div class="bg-white border rounded p-5 text-center mb-3" style="border-style: dashed !important; height: 200px;">
                                            <i class="far fa-clock fs-1 text-muted opacity-25"></i>
                                            <p class="small text-muted mt-2">Área para que el paciente dibuje (en papel físico)</p>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center p-3 bg-soft-gray rounded">
                                            <span class="fw-bold">Evaluación del Reloj:</span>
                                            <div class="btn-group" role="group">
                                                <input type="radio" class="btn-check minicog-clock" name="minicog_reloj_puntaje" id="reloj_anormal" value="0" {{ ($vgi->minicog_reloj_puntaje ?? 0) == 0 ? 'checked' : '' }}>
                                                <label class="btn btn-outline-danger btn-sm px-3" for="reloj_anormal">0 pts (Anormal)</label>

                                                <input type="radio" class="btn-check minicog-clock" name="minicog_reloj_puntaje" id="reloj_normal" value="2" {{ ($vgi->minicog_reloj_puntaje ?? 0) == 2 ? 'checked' : '' }}>
                                                <label class="btn btn-outline-success btn-sm px-3" for="reloj_normal">2 pts (Normal)</label>
                                            </div>
                                        </div>
                                        <small class="d-block mt-2 text-muted" style="font-size: 0.8em;">*Normal: Todos los números, orden correcto, manecillas en 11 y 2.</small>
                                    </div>
                                </div>

                                <div class="section-container mb-4">
                                    <div class="section-header bg-light"><h6 class="m-0 fw-bold">3. Recuerdo Diferido (Evocación)</h6></div>
                                    <div class="section-body p-4">
                                        <p class="mb-3 fw-bold">¿Qué palabras recuerda el paciente?</p>
                                        <div class="d-flex flex-column gap-3">
                                            <label class="selection-card p-3 d-flex align-items-center justify-content-between">
                                                <span class="text fw-bold">MESA</span>
                                                <input type="checkbox" class="minicog-check" name="minicog_palabra_mesa" value="1" {{ ($vgi->minicog_palabra_mesa ?? 0) == 1 ? 'checked' : '' }}>
                                                <div class="card-inner p-0 border-0 shadow-none"><div class="check-box-visual"></div></div>
                                            </label>
                                            <label class="selection-card p-3 d-flex align-items-center justify-content-between">
                                                <span class="text fw-bold">LLAVE</span>
                                                <input type="checkbox" class="minicog-check" name="minicog_palabra_llave" value="1" {{ ($vgi->minicog_palabra_llave ?? 0) == 1 ? 'checked' : '' }}>
                                                <div class="card-inner p-0 border-0 shadow-none"><div class="check-box-visual"></div></div>
                                            </label>
                                            <label class="selection-card p-3 d-flex align-items-center justify-content-between">
                                                <span class="text fw-bold">LIBRO</span>
                                                <input type="checkbox" class="minicog-check" name="minicog_palabra_libro" value="1" {{ ($vgi->minicog_palabra_libro ?? 0) == 1 ? 'checked' : '' }}>
                                                <div class="card-inner p-0 border-0 shadow-none"><div class="check-box-visual"></div></div>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="col-lg-5">
                                <div class="card shadow-lg border-0 bg-indigo text-white sticky-top" style="top: 100px;">
                                    <div class="card-body text-center p-4">
                                        <h5 class="fw-bold mb-3"><i class="fas fa-calculator me-2"></i>RESULTADO MINI-COG</h5>
                                        
                                        <div class="row text-start small mb-3">
                                            <div class="col-8">Palabras recordadas:</div>
                                            <div class="col-4 text-end fw-bold"><span id="mc_words">0</span> / 3</div>
                                            <div class="col-8">Puntaje Reloj:</div>
                                            <div class="col-4 text-end fw-bold"><span id="mc_clock">0</span> / 2</div>
                                        </div>
                                        <hr class="border-white opacity-25">
                                        
                                        <div class="display-3 fw-bold my-2" id="minicog_total_display">0</div>
                                        <p class="small opacity-75 text-uppercase">Puntaje Total (Máx 5)</p>

                                        <div class="badge bg-white text-indigo px-4 py-2 fs-6 rounded-pill w-100 mb-3" id="minicog_result_display">Sin evaluar</div>
                                        
                                        <input type="hidden" name="minicog_total" id="input_minicog_total" value="{{ $vgi->minicog_total ?? 0 }}">
                                        <input type="hidden" name="minicog_valoracion" id="input_minicog_valoracion" value="{{ $vgi->minicog_valoracion ?? '' }}">
                                        
                                        <div class="text-start bg-white bg-opacity-10 p-3 rounded small">
                                            <strong>Criterio:</strong><br>
                                            <i class="fas fa-check-circle text-success me-1"></i> <strong>Normal:</strong> 3 palabras <strong>O</strong> (1-2 palabras + Reloj Normal).<br>
                                            <i class="fas fa-exclamation-circle text-danger me-1"></i> <strong>Anómalo:</strong> 0 palabras <strong>O</strong> (1-2 palabras + Reloj Anormal).
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PESTAÑA: GDS-4 Y YESAVAGE -->
            <div id="tab-gds" class="vgi-tab-content">
                <!-- Se quitó el bloqueo de seguridad - acceso directo -->
                <div class="section-header mb-4">
                    <div class="header-icon bg-warning text-white"><i class="fas fa-heart-broken"></i></div>
                    <h4 class="header-title text-warning">XII. Evaluación Afectiva: GDS-4 y Yesavage</h4>
                </div>

                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <div class="section-container mb-4">
                            <div class="section-header bg-soft-gray">
                                <h5 class="m-0 fw-bold text-dark"><i class="fas fa-filter me-2"></i>Escala Abreviada GDS-4</h5>
                            </div>
                            <div class="section-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0 align-middle">
                                        <thead class="bg-light text-uppercase small text-muted">
                                            <tr>
                                                <th class="ps-4 py-3">Pregunta</th>
                                                <th class="text-center" style="width: 100px;">SI (1)</th>
                                                <th class="text-center" style="width: 100px;">NO (0)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="ps-4 fw-500">1. ¿Está insatisfecho con su vida?</td>
                                                <td class="text-center"><input class="form-check-input gds4-radio scale-13" type="radio" name="gds_insatisfecho" value="1" {{ ($vgi->gds_insatisfecho ?? 0) == 1 ? 'checked' : '' }}></td>
                                                <td class="text-center"><input class="form-check-input gds4-radio scale-13" type="radio" name="gds_insatisfecho" value="0" {{ ($vgi->gds_insatisfecho ?? 0) == 0 ? 'checked' : '' }}></td>
                                            </tr>
                                            <tr>
                                                <td class="ps-4 fw-500">2. ¿Se siente impotente o indefenso?</td>
                                                <td class="text-center"><input class="form-check-input gds4-radio scale-13" type="radio" name="gds_impotente" value="1" {{ ($vgi->gds_impotente ?? 0) == 1 ? 'checked' : '' }}></td>
                                                <td class="text-center"><input class="form-check-input gds4-radio scale-13" type="radio" name="gds_impotente" value="0" {{ ($vgi->gds_impotente ?? 0) == 0 ? 'checked' : '' }}></td>
                                            </tr>
                                            <tr>
                                                <td class="ps-4 fw-500">3. ¿Tiene problemas de memoria?</td>
                                                <td class="text-center"><input class="form-check-input gds4-radio scale-13" type="radio" name="gds_memoria" value="1" {{ ($vgi->gds_memoria ?? 0) == 1 ? 'checked' : '' }}></td>
                                                <td class="text-center"><input class="form-check-input gds4-radio scale-13" type="radio" name="gds_memoria" value="0" {{ ($vgi->gds_memoria ?? 0) == 0 ? 'checked' : '' }}></td>
                                            </tr>
                                            <tr>
                                                <td class="ps-4 fw-500">4. ¿Siente desgano respecto a actividades?</td>
                                                <td class="text-center"><input class="form-check-input gds4-radio scale-13" type="radio" name="gds_desgano" value="1" {{ ($vgi->gds_desgano ?? 0) == 1 ? 'checked' : '' }}></td>
                                                <td class="text-center"><input class="form-check-input gds4-radio scale-13" type="radio" name="gds_desgano" value="0" {{ ($vgi->gds_desgano ?? 0) == 0 ? 'checked' : '' }}></td>
                                            </tr>
                                            <tr class="bg-warning bg-opacity-10 border-top">
                                                <td class="ps-4 fw-bold text-dark">PUNTAJE TOTAL GDS-4</td>
                                                <td colspan="2" class="text-center fs-4 fw-bold text-warning" id="gds4_score">0</td>
                                                <input type="hidden" name="gds_total" id="input_gds_total" value="{{ $vgi->gds_total ?? 0 }}">
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="p-3 bg-light border-top">
                                <div class="alert alert-info mb-0 d-flex align-items-center" id="gds_msg_normal">
                                    <i class="fas fa-check-circle fs-4 me-3"></i>
                                    <div><strong>Normal:</strong> Menos de 2 puntos. No se requiere test adicional.</div>
                                </div>
                                <div class="alert alert-danger mb-0 d-flex align-items-center" id="gds_msg_risk" style="display:none;">
                                    <i class="fas fa-exclamation-triangle fs-4 me-3"></i>
                                    <div><strong>Riesgo Detectado:</strong> 2 o más puntos. <u>Se ha desplegado el Test de Yesavage abajo.</u></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="yesavage_section" style="display: none; animation: slideDown 0.5s ease;">
                    <div class="section-container border-danger mb-4">
                        <div class="section-header bg-danger text-white">
                            <h5 class="m-0 fw-bold"><i class="fas fa-list-ol me-2"></i>Test de Yesavage (15 Ítems)</h5>
                        </div>
                        <div class="section-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover mb-0 align-middle">
                                    <thead class="bg-light text-uppercase small text-muted">
                                        <tr>
                                            <th class="ps-4 py-3">Pregunta</th>
                                            <th class="text-center" style="width: 100px;">SI</th>
                                            <th class="text-center" style="width: 100px;">NO</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="ps-4">1. ¿Está satisfecho con su vida?</td>
                                            <td class="text-center"><input class="form-check-input ysg-radio scale-13" type="radio" name="ysg_1" value="0" {{ ($vgi->ysg_1 ?? 0) == 0 ? 'checked' : '' }}></td> <td class="text-center"><input class="form-check-input ysg-radio scale-13" type="radio" name="ysg_1" value="1" {{ ($vgi->ysg_1 ?? 0) == 1 ? 'checked' : '' }}></td> </tr>
                                        <tr>
                                            <td class="ps-4">2. ¿Ha renunciado a muchas actividades?</td>
                                            <td class="text-center"><input class="form-check-input ysg-radio scale-13" type="radio" name="ysg_2" value="1" {{ ($vgi->ysg_2 ?? 0) == 1 ? 'checked' : '' }}></td>
                                            <td class="text-center"><input class="form-check-input ysg-radio scale-13" type="radio" name="ysg_2" value="0" {{ ($vgi->ysg_2 ?? 0) == 0 ? 'checked' : '' }}></td>
                                        </tr>
                                        <tr>
                                            <td class="ps-4">3. ¿Siente que su vida está vacía?</td>
                                            <td class="text-center"><input class="form-check-input ysg-radio scale-13" type="radio" name="ysg_3" value="1" {{ ($vgi->ysg_3 ?? 0) == 1 ? 'checked' : '' }}></td>
                                            <td class="text-center"><input class="form-check-input ysg-radio scale-13" type="radio" name="ysg_3" value="0" {{ ($vgi->ysg_3 ?? 0) == 0 ? 'checked' : '' }}></td>
                                        </tr>
                                        <tr>
                                            <td class="ps-4">4. ¿Se encuentra a menudo aburrido?</td>
                                            <td class="text-center"><input class="form-check-input ysg-radio scale-13" type="radio" name="ysg_4" value="1" {{ ($vgi->ysg_4 ?? 0) == 1 ? 'checked' : '' }}></td>
                                            <td class="text-center"><input class="form-check-input ysg-radio scale-13" type="radio" name="ysg_4" value="0" {{ ($vgi->ysg_4 ?? 0) == 0 ? 'checked' : '' }}></td>
                                        </tr>
                                        <tr>
                                            <td class="ps-4">5. ¿Tiene a menudo buen ánimo?</td>
                                            <td class="text-center"><input class="form-check-input ysg-radio scale-13" type="radio" name="ysg_5" value="0" {{ ($vgi->ysg_5 ?? 0) == 0 ? 'checked' : '' }}></td>
                                            <td class="text-center"><input class="form-check-input ysg-radio scale-13" type="radio" name="ysg_5" value="1" {{ ($vgi->ysg_5 ?? 0) == 1 ? 'checked' : '' }}></td>
                                        </tr>
                                        <tr>
                                            <td class="ps-4">6. ¿Teme que algo malo le pase?</td>
                                            <td class="text-center"><input class="form-check-input ysg-radio scale-13" type="radio" name="ysg_6" value="1" {{ ($vgi->ysg_6 ?? 0) == 1 ? 'checked' : '' }}></td>
                                            <td class="text-center"><input class="form-check-input ysg-radio scale-13" type="radio" name="ysg_6" value="0" {{ ($vgi->ysg_6 ?? 0) == 0 ? 'checked' : '' }}></td>
                                        </tr>
                                        <tr>
                                            <td class="ps-4">7. ¿Se siente feliz muchas veces?</td>
                                            <td class="text-center"><input class="form-check-input ysg-radio scale-13" type="radio" name="ysg_7" value="0" {{ ($vgi->ysg_7 ?? 0) == 0 ? 'checked' : '' }}></td>
                                            <td class="text-center"><input class="form-check-input ysg-radio scale-13" type="radio" name="ysg_7" value="1" {{ ($vgi->ysg_7 ?? 0) == 1 ? 'checked' : '' }}></td>
                                        </tr>
                                        <tr>
                                            <td class="ps-4">8. ¿Se siente a menudo abandonado/a?</td>
                                            <td class="text-center"><input class="form-check-input ysg-radio scale-13" type="radio" name="ysg_8" value="1" {{ ($vgi->ysg_8 ?? 0) == 1 ? 'checked' : '' }}></td>
                                            <td class="text-center"><input class="form-check-input ysg-radio scale-13" type="radio" name="ysg_8" value="0" {{ ($vgi->ysg_8 ?? 0) == 0 ? 'checked' : '' }}></td>
                                        </tr>
                                        <tr>
                                            <td class="ps-4">9. ¿Prefiere quedarse en casa a salir?</td>
                                            <td class="text-center"><input class="form-check-input ysg-radio scale-13" type="radio" name="ysg_9" value="1" {{ ($vgi->ysg_9 ?? 0) == 1 ? 'checked' : '' }}></td>
                                            <td class="text-center"><input class="form-check-input ysg-radio scale-13" type="radio" name="ysg_9" value="0" {{ ($vgi->ysg_9 ?? 0) == 0 ? 'checked' : '' }}></td>
                                        </tr>
                                        <tr>
                                            <td class="ps-4">10. ¿Cree tener más problemas de memoria que el resto?</td>
                                            <td class="text-center"><input class="form-check-input ysg-radio scale-13" type="radio" name="ysg_10" value="1" {{ ($vgi->ysg_10 ?? 0) == 1 ? 'checked' : '' }}></td>
                                            <td class="text-center"><input class="form-check-input ysg-radio scale-13" type="radio" name="ysg_10" value="0" {{ ($vgi->ysg_10 ?? 0) == 0 ? 'checked' : '' }}></td>
                                        </tr>
                                        <tr>
                                            <td class="ps-4">11. ¿Piensa que es maravilloso vivir?</td>
                                            <td class="text-center"><input class="form-check-input ysg-radio scale-13" type="radio" name="ysg_11" value="0" {{ ($vgi->ysg_11 ?? 0) == 0 ? 'checked' : '' }}></td>
                                            <td class="text-center"><input class="form-check-input ysg-radio scale-13" type="radio" name="ysg_11" value="1" {{ ($vgi->ysg_11 ?? 0) == 1 ? 'checked' : '' }}></td>
                                        </tr>
                                        <tr>
                                            <td class="ps-4">12. ¿Le cuesta iniciar nuevos proyectos?</td>
                                            <td class="text-center"><input class="form-check-input ysg-radio scale-13" type="radio" name="ysg_12" value="1" {{ ($vgi->ysg_12 ?? 0) == 1 ? 'checked' : '' }}></td>
                                            <td class="text-center"><input class="form-check-input ysg-radio scale-13" type="radio" name="ysg_12" value="0" {{ ($vgi->ysg_12 ?? 0) == 0 ? 'checked' : '' }}></td>
                                        </tr>
                                        <tr>
                                            <td class="ps-4">13. ¿Se siente lleno/a de energía?</td>
                                            <td class="text-center"><input class="form-check-input ysg-radio scale-13" type="radio" name="ysg_13" value="0" {{ ($vgi->ysg_13 ?? 0) == 0 ? 'checked' : '' }}></td>
                                            <td class="text-center"><input class="form-check-input ysg-radio scale-13" type="radio" name="ysg_13" value="1" {{ ($vgi->ysg_13 ?? 0) == 1 ? 'checked' : '' }}></td>
                                        </tr>
                                        <tr>
                                            <td class="ps-4">14. ¿Siente que su situación es desesperada?</td>
                                            <td class="text-center"><input class="form-check-input ysg-radio scale-13" type="radio" name="ysg_14" value="1" {{ ($vgi->ysg_14 ?? 0) == 1 ? 'checked' : '' }}></td>
                                            <td class="text-center"><input class="form-check-input ysg-radio scale-13" type="radio" name="ysg_14" value="0" {{ ($vgi->ysg_14 ?? 0) == 0 ? 'checked' : '' }}></td>
                                        </tr>
                                        <tr>
                                            <td class="ps-4">15. ¿Cree que mucha gente está mejor que usted?</td>
                                            <td class="text-center"><input class="form-check-input ysg-radio scale-13" type="radio" name="ysg_15" value="1" {{ ($vgi->ysg_15 ?? 0) == 1 ? 'checked' : '' }}></td>
                                            <td class="text-center"><input class="form-check-input ysg-radio scale-13" type="radio" name="ysg_15" value="0" {{ ($vgi->ysg_15 ?? 0) == 0 ? 'checked' : '' }}></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="p-4 bg-danger bg-opacity-10 border-top d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="fw-bold mb-1">Resultado Yesavage:</h5>
                                    <div id="ysg_interpretation" class="badge bg-secondary fs-6">Sin evaluar</div>
                                </div>
                                <div class="text-end">
                                    <span class="fs-1 fw-bold text-danger" id="ysg_score">0</span>
                                    <span class="text-muted fw-bold">/ 15</span>
                                    <input type="hidden" name="yesavage_total" id="input_yesavage_total" value="{{ $vgi->yesavage_total ?? 0 }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- NUEVA PESTAÑA: MNA (Mini Nutritional Assessment) -->
            <div id="tab-mna" class="vgi-tab-content">
                <div class="section-header mb-4">
                    <div class="header-icon bg-success text-white"><i class="fas fa-utensils"></i></div>
                    <h4 class="header-title text-success">XIII. Evaluación Nutricional: Mini Nutritional Assessment (MNA-SF)</h4>
                </div>

                <div class="row">
                    <div class="col-lg-8">
                        <div class="section-container">
                            <div class="section-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0 align-middle">
                                        <thead class="bg-light text-uppercase small text-muted">
                                            <tr>
                                                <th class="ps-4 py-3">Pregunta</th>
                                                <th class="text-center" style="width: 250px;">Respuesta</th>
                                                <th class="text-center" style="width: 80px;">Pts</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="ps-4 fw-500">
                                                    <strong>A. ¿Ha comido menos en los últimos 3 meses?</strong><br>
                                                    <span class="text-muted small">(Por falta de apetito, problemas digestivos, deglución)</span>
                                                </td>
                                                <td>
                                                    <select name="mna_a" class="form-select form-select-sm mna-select">
                                                        <option value="0" {{ ($vgi->mna_a ?? 0) == 0 ? 'selected' : '' }}>0 = Apetito grave (Pérdida severa)</option>
                                                        <option value="1" {{ ($vgi->mna_a ?? 0) == 1 ? 'selected' : '' }}>1 = Apetito moderado (Pérdida mod.)</option>
                                                        <option value="2" {{ ($vgi->mna_a ?? 0) == 2 ? 'selected' : '' }}>2 = Sin pérdida de apetito</option>
                                                    </select>
                                                </td>
                                                <td class="text-center fw-bold bg-light" id="score_mna_a">0</td>
                                            </tr>

                                            <tr>
                                                <td class="ps-4 fw-500">
                                                    <strong>B. ¿Pérdida de peso reciente (3 meses)?</strong>
                                                </td>
                                                <td>
                                                    <select name="mna_b" class="form-select form-select-sm mna-select">
                                                        <option value="0" {{ ($vgi->mna_b ?? 0) == 0 ? 'selected' : '' }}>0 = Pérdida > 3 kg</option>
                                                        <option value="1" {{ ($vgi->mna_b ?? 0) == 1 ? 'selected' : '' }}>1 = No lo sabe</option>
                                                        <option value="2" {{ ($vgi->mna_b ?? 0) == 2 ? 'selected' : '' }}>2 = Pérdida entre 1 y 3 kg</option>
                                                        <option value="3" {{ ($vgi->mna_b ?? 0) == 3 ? 'selected' : '' }}>3 = No ha perdido peso</option>
                                                    </select>
                                                </td>
                                                <td class="text-center fw-bold bg-light" id="score_mna_b">0</td>
                                            </tr>

                                            <tr>
                                                <td class="ps-4 fw-500">
                                                    <strong>C. Movilidad</strong>
                                                </td>
                                                <td>
                                                    <select name="mna_c" class="form-select form-select-sm mna-select">
                                                        <option value="0" {{ ($vgi->mna_c ?? 0) == 0 ? 'selected' : '' }}>0 = De la cama al sillón</option>
                                                        <option value="1" {{ ($vgi->mna_c ?? 0) == 1 ? 'selected' : '' }}>1 = Autonomía en interior</option>
                                                        <option value="2" {{ ($vgi->mna_c ?? 0) == 2 ? 'selected' : '' }}>2 = Sale del domicilio</option>
                                                    </select>
                                                </td>
                                                <td class="text-center fw-bold bg-light" id="score_mna_c">0</td>
                                            </tr>

                                            <tr>
                                                <td class="ps-4 fw-500">
                                                    <strong>D. ¿Ha tenido enfermedad aguda o estrés psicológico (3 meses)?</strong>
                                                </td>
                                                <td>
                                                    <select name="mna_d" class="form-select form-select-sm mna-select">
                                                        <option value="0" {{ ($vgi->mna_d ?? 0) == 0 ? 'selected' : '' }}>0 = Sí</option>
                                                        <option value="2" {{ ($vgi->mna_d ?? 0) == 2 ? 'selected' : '' }}>2 = No</option>
                                                    </select>
                                                </td>
                                                <td class="text-center fw-bold bg-light" id="score_mna_d">0</td>
                                            </tr>

                                            <tr>
                                                <td class="ps-4 fw-500">
                                                    <strong>E. Problemas neuropsicológicos</strong>
                                                </td>
                                                <td>
                                                    <select name="mna_e" class="form-select form-select-sm mna-select">
                                                        <option value="0" {{ ($vgi->mna_e ?? 0) == 0 ? 'selected' : '' }}>0 = Demencia/Depresión grave</option>
                                                        <option value="1" {{ ($vgi->mna_e ?? 0) == 1 ? 'selected' : '' }}>1 = Demencia moderada</option>
                                                        <option value="2" {{ ($vgi->mna_e ?? 0) == 2 ? 'selected' : '' }}>2 = Sin problemas</option>
                                                    </select>
                                                </td>
                                                <td class="text-center fw-bold bg-light" id="score_mna_e">0</td>
                                            </tr>

                                            <tr>
                                                <td class="ps-4 fw-500">
                                                    <strong>F. Índice de Masa Corporal (IMC)</strong><br>
                                                    <span class="text-muted small">IMC actual: <strong id="mna_current_bmi">{{ $vgi->imc ?? '--' }}</strong> (Calculado en Pestaña II)</span><br>
                                                    <span class="text-danger small fst-italic">*Si no se puede IMC, medir pantorrilla.</span>
                                                </td>
                                                <td>
                                                    <select name="mna_f" class="form-select form-select-sm mna-select">
                                                        <option value="0" {{ ($vgi->mna_f ?? 0) == 0 ? 'selected' : '' }}>0 = IMC < 19 (o Pantorrilla < 31)</option>
                                                        <option value="1" {{ ($vgi->mna_f ?? 0) == 1 ? 'selected' : '' }}>1 = IMC 19 - < 21</option>
                                                        <option value="2" {{ ($vgi->mna_f ?? 0) == 2 ? 'selected' : '' }}>2 = IMC 21 - < 23</option>
                                                        <option value="3" {{ ($vgi->mna_f ?? 0) == 3 ? 'selected' : '' }}>3 = IMC ≥ 23 (o Pantorrilla ≥ 31)</option>
                                                    </select>
                                                </td>
                                                <td class="text-center fw-bold bg-light" id="score_mna_f">0</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card shadow-lg border-0 bg-success text-white sticky-top" style="top: 100px;">
                            <div class="card-body text-center p-4">
                                <h5 class="fw-bold mb-3"><i class="fas fa-apple-alt me-2"></i>PUNTAJE MNA</h5>
                                
                                <div class="bg-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3 shadow-sm" style="width: 100px; height: 100px;">
                                    <div class="display-4 fw-bold text-success" id="mna_score_display">0</div>
                                </div>
                                <p class="mb-2 fw-bold text-uppercase text-white-50">Máximo 14 Puntos</p>
                                
                                <div class="badge bg-white text-success px-3 py-2 fs-6 rounded-pill w-100 mb-3" id="mna_result_display">Sin evaluar</div>
                                
                                <input type="hidden" name="mna_puntaje" id="input_mna_puntaje" value="{{ $vgi->mna_puntaje ?? 0 }}">
                                <input type="hidden" name="mna_valoracion" id="input_mna_valoracion" value="{{ $vgi->mna_valoracion ?? '' }}">
                                
                                <hr class="border-white opacity-25 my-4">
                                <div class="text-start small opacity-75">
                                    <ul class="list-unstyled mb-0 d-grid gap-1">
                                        <li><span class="badge bg-white text-success me-2">12-14</span> Estado Normal</li>
                                        <li><span class="badge bg-white text-warning me-2">8-11</span> Riesgo de Malnutrición</li>
                                        <li><span class="badge bg-white text-danger me-2">0-7</span> Malnutrición</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PESTAÑA: FÍSICA -->
            <div id="tab-fisica" class="vgi-tab-content">
                <div class="section-container mb-4">
                    <div class="section-header bg-purple-light text-purple">
                        <div class="icon-box bg-purple text-white"><i class="fas fa-apple-alt"></i></div>
                        <h5 class="m-0 fw-bold">Estado Nutricional y Físico</h5>
                    </div>
                    <div class="section-body p-4">
                        <div class="row g-4 text-center justify-content-center">
                            <div class="col-md-3">
                                <label class="label-input d-block mb-2">MNA (Nutrición)</label>
                                <input type="number" name="mna_puntaje" min="0" class="form-control modern-input text-center fs-4" value="{{ $vgi->mna_puntaje ?? '' }}">
                            </div>
                            <div class="col-md-3">
                                <label class="label-input d-block mb-2">Velocidad Marcha</label>
                                <input type="number" step="0.1" min="0" name="velocidad_marcha" class="form-control modern-input text-center fs-4" value="{{ $vgi->velocidad_marcha ?? '' }}">
                            </div>
                            <div class="col-md-3">
                                <label class="label-input d-block mb-2">FRAIL (Fragilidad)</label>
                                <input type="number" name="frail_puntaje" min="0" max="5" class="form-control modern-input text-center fs-4" value="{{ $vgi->frail_puntaje ?? '' }}">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="form-group mt-5">
                    <label class="text-brand fw-bold mb-3 fs-5"><i class="fas fa-user-md me-2"></i>Plan de Trabajo / Recomendaciones</label>
                    <textarea name="plan_cuidados" class="form-control modern-input p-4 shadow-sm" rows="6" placeholder="Escriba aquí las indicaciones médicas, tratamiento y observaciones...">{{ $vgi->plan_cuidados ?? '' }}</textarea>
                </div>
            </div>

            <div class="form-actions-footer d-flex justify-content-between align-items-center mt-5 pt-4 border-top">
                <div class="text-muted small d-none d-md-block"><i class="fas fa-lock me-1"></i> Información protegida por WasiQhari</div>
                <div class="d-flex gap-3 ms-auto">
                    <a href="{{ route('adultos') }}" class="btn btn-light px-4 fw-bold text-muted border rounded-pill hover-scale">Cancelar</a>
                    <button type="submit" class="btn btn-brand px-5 py-2 shadow-lg fw-bold rounded-pill text-white hover-scale">
                        <i class="fas fa-save me-2"></i> Guardar Historia
                    </button>
                </div>
            </div>

        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* === VARIABLES VISUALES (WasiQhari Theme) === */
    :root {
        --primary: #6f42c1; /* Morado WasiQhari */
        --primary-light: #f3f0ff;
        --secondary: #fd7e14; /* Naranja */
        --secondary-light: #fff8f3;
        --teal: #20c997;
        --teal-light: #e6fffa;
        --bg-body: #f8f9fc;
        --text-dark: #2c3e50;
    }

    /* === ESTRUCTURA GLOBAL === */
    .dashboard-container { background-color: var(--bg-body); padding-bottom: 80px; font-family: 'Poppins', sans-serif; }
    .main-card { border-radius: 24px; border: none; box-shadow: 0 12px 40px rgba(0,0,0,0.06); }
    .bg-soft-gray { background-color: #fbfbfb; }
    .text-brand { color: var(--primary); }
    
    /* === PESTAÑAS (Centradas y Elegantes) === */
    .vgi-tabs-container { overflow-x: auto; white-space: nowrap; padding-bottom: 0; box-shadow: 0 4px 20px rgba(0,0,0,0.03); }
    .vgi-tab {
        border: none; background: transparent; padding: 15px 30px; 
        font-weight: 600; color: #adb5bd; border-bottom: 3px solid transparent; 
        transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 8px; font-size: 1rem;
    }
    .vgi-tab:hover { color: var(--primary); background: #f8f9fa; }
    .vgi-tab.active { color: var(--primary); border-bottom-color: var(--primary); background: transparent; }
    .vgi-tab i { font-size: 1.1rem; }

    /* === HEADER & ICONS (Alineación Perfecta) === */
    .section-container { background: white; border: 1px solid #f0f0f0; border-radius: 20px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.02); transition: transform 0.2s; }
    .section-container:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(0,0,0,0.05); }
    
    .section-header { 
        padding: 20px 25px; display: flex; align-items: center; gap: 15px;
        background-color: white; border-bottom: 1px solid #f8f9fa;
    }
    .header-icon {
        width: 40px; height: 40px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1); flex-shrink: 0; /* Evita que se aplaste */
    }
    .header-title { margin: 0; font-weight: 700; font-size: 1.1rem; }

    /* === COLORES DE FONDO SUAVES === */
    .bg-purple { background-color: var(--primary); }
    .bg-purple-light { background-color: var(--primary-light); color: var(--primary); }
    .bg-orange { background-color: var(--secondary); }
    .bg-orange-light { background-color: var(--secondary-light); }
    .bg-teal { background-color: var(--teal); }
    .bg-teal-light { background-color: var(--teal-light); }
    .bg-indigo { background-color: #6610f2; }
    .text-indigo { color: #6610f2; }
    .bg-indigo-light { background-color: #f3ebff; }

    /* === INPUTS & FORMS (Con Iconos) === */
    .label-title { font-size: 0.8rem; font-weight: 700; color: #adb5bd; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 0.5px; }
    .label-input { font-size: 0.9rem; font-weight: 600; color: #495057; margin-bottom: 8px; }
    
    .modern-input, .form-select {
        border: 2px solid #edf2f7; border-radius: 12px; padding: 12px 15px; 
        font-size: 0.95rem; transition: all 0.2s; background: #fff;
    }
    .modern-input:focus, .form-select:focus { border-color: var(--primary); box-shadow: 0 0 0 4px rgba(111, 66, 193, 0.1); outline: none; }
    
    /* Input con Icono Interior */
    .input-with-icon { position: relative; }
    .input-with-icon i { position: absolute; top: 50%; left: 15px; transform: translateY(-50%); color: #adb5bd; }
    .input-with-icon input { padding-left: 45px; }

    /* Metadata Icons */
    .icon-sq { width: 30px; height: 30px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 0.9rem; }

    /* Floating Data (Solo Lectura) */
    .floating-data { background: #f8f9fa; border: 1px dashed #dee2e6; padding: 15px; border-radius: 12px; }
    .floating-data label { font-size: 0.75rem; color: #adb5bd; text-transform: uppercase; display: block; margin-bottom: 4px; }
    .floating-data .value { font-weight: 700; color: #343a40; font-size: 1.1rem; }

    /* === GRID DE TARJETAS (Centrado y Bonito) === */
    .grid-selection { display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 160px)); gap: 20px; width: 100%; }
    .selection-card { cursor: pointer; position: relative; margin: 0; }
    .selection-card input { position: absolute; opacity: 0; }
    
    .card-inner { 
        border: 2px solid #edf2f7; border-radius: 16px; padding: 20px 10px; 
        text-align: center; transition: all 0.2s; background: #fff; height: 100%;
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        box-shadow: 0 4px 6px rgba(0,0,0,0.01);
    }
    .card-inner .icon-lg { font-size: 2rem; margin-bottom: 10px; }
    .card-inner .text { font-weight: 600; font-size: 0.9rem; color: #495057; line-height: 1.2; }
    
    .selection-card input:checked + .card-inner { 
        border-color: var(--primary); background: #f3f0ff; color: var(--primary); 
        transform: translateY(-5px); box-shadow: 0 10px 20px rgba(111, 66, 193, 0.15);
    }

    /* === BOTONES PÍLDORA (Estado Civil) === */
    .btn-radio-pill input { display: none; }
    .btn-radio-pill label { 
        display: inline-block; padding: 10px 25px; border-radius: 50px; border: 2px solid #edf2f7; 
        color: #6c757d; font-weight: 600; cursor: pointer; transition: all 0.2s; background: #fff;
    }
    .btn-radio-pill input:checked + label { 
        background: var(--primary); color: white; border-color: var(--primary); 
        box-shadow: 0 4px 12px rgba(111, 66, 193, 0.3); transform: translateY(-2px);
    }
    .small-pill label { padding: 6px 18px; font-size: 0.85rem; }

    /* === CUIDADOR SECTION === */
    .caregiver-section { background: #fff8f3; border: 2px solid #ffe8cc; transition: all 0.3s; }
    .caregiver-section.active { background: #fff; border-color: var(--secondary); box-shadow: 0 0 0 4px rgba(253, 126, 20, 0.1); }
    .caregiver-header { cursor: pointer; }
    .icon-circle { width: 45px; height: 45px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; color: var(--secondary); box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
    .custom-switch .form-check-input { width: 3em; height: 1.5em; cursor: pointer; border: 2px solid #adb5bd; }
    .custom-switch .form-check-input:checked { background-color: var(--secondary); border-color: var(--secondary); }
    .divider-line { height: 1px; background-color: #eee; width: 100%; }

    /* === ESTILOS ESPECÍFICOS BARTHEL === */
    .barthel-radio { transform: scale(1.3); margin-right: 10px; cursor: pointer; }
    .barthel-radio:checked + label { color: var(--primary); font-weight: bold; }

    /* === ESTILOS ESPECÍFICOS LAWTON === */
    .lawton-radio { transform: scale(1.3); margin-right: 10px; cursor: pointer; }
    .lawton-radio:checked + label { color: var(--teal); font-weight: bold; }

    /* === ESTILOS ESPECÍFICOS PFEIFFER === */
    .pfeiffer-radio { width: 1.5em; height: 1.5em; cursor: pointer; }
    .pfeiffer-radio:checked[value="1"] { background-color: #dc3545; border-color: #dc3545; } /* Rojo para error */
    .pfeiffer-radio:checked[value="0"] { background-color: #198754; border-color: #198754; } /* Verde para correcto */
    .bg-soft-danger-hover:hover { background-color: #fff5f5; }
    .fw-500 { font-weight: 500; }

    /* === ESTILOS ESPECÍFICOS RUDAS === */
    .rudas-check { transform: scale(1.3); margin-right: 10px; cursor: pointer; }
    .border-dashed { border-style: dashed !important; }
    .rudas-input { border-color: #6610f2; }

    /* === ESTILOS ESPECÍFICOS MMSE === */
    .scale-15 { transform: scale(1.5); margin-top: 5px; }
    .mmse-check { width: 1.3em; height: 1.3em; cursor: pointer; }

    /* ESTILOS DEL CANDADO DE SEGURIDAD */
    .locked-wrapper { position: relative; min-height: 400px; }

    /* El contenido real (oculto/borroso al inicio) */
    .locked-content { 
        filter: blur(8px); 
        pointer-events: none; /* No deja hacer clic */
        user-select: none;    /* No deja seleccionar texto */
        transition: all 0.5s ease;
        opacity: 0.5;
    }

    /* Cuando se desbloquea */
    .locked-content.unlocked { 
        filter: blur(0); 
        pointer-events: all; 
        user-select: auto; 
        opacity: 1;
    }

    /* La pantalla de bloqueo encima */
    .lock-overlay {
        position: absolute; top: 0; left: 0; width: 100%; height: 100%;
        z-index: 50;
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        background: rgba(255, 255, 255, 0.6);
        border-radius: 20px;
    }

    .lock-card {
        background: white; padding: 40px; border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.15); text-align: center;
        max-width: 400px; width: 90%; border: 1px solid #eee;
    }

    .pin-input {
        font-size: 1.5rem; letter-spacing: 5px; text-align: center;
        border: 2px solid #ddd; border-radius: 10px; padding: 10px;
        width: 100%; margin: 20px 0; transition: 0.3s;
    }
    .pin-input:focus { border-color: #dc3545; outline: none; box-shadow: 0 0 0 4px rgba(220, 53, 69, 0.1); }

    /* Colores para los badges del Barthel */
    .bg-orange { background-color: var(--secondary) !important; }
    .bg-info { background-color: #0dcaf0 !important; }
    .bg-warning { background-color: #ffc107 !important; }
    .bg-success { background-color: #198754 !important; }
    .bg-danger { background-color: #dc3545 !important; }

    /* === BOTONES ACCIÓN === */
    .btn-brand { background: linear-gradient(135deg, #6f42c1, #8e44ad); border: none; transition: transform 0.2s; }
    .hover-scale:hover { transform: translateY(-2px); }

    /* === ANIMACIONES === */
    .fade-in-down { animation: fadeInDown 0.6s ease; }
    .fade-in-up { animation: fadeInUp 0.6s ease; }
    @keyframes fadeInDown { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .vgi-tab-content { display: none; animation: fadeIn 0.4s ease; }
    .vgi-tab-content.active-content { display: block; }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    
    /* Checkbox Visual para Enfermedades */
    .check-box-visual { width: 20px; height: 20px; border: 2px solid #adb5bd; border-radius: 6px; display: inline-block; transition: 0.2s; position: relative; background: white; }
    .selection-card input:checked + .card-inner .check-box-visual { background-color: var(--primary); border-color: var(--primary); }
    .selection-card input:checked + .card-inner .check-box-visual::after { content: '✔'; position: absolute; color: white; font-size: 12px; top: -1px; left: 3px; }

    /* Nuevos estilos para GDS */
    .scale-13 { transform: scale(1.3); cursor: pointer; }
    @keyframes slideDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }

</style>
@endpush

@push('scripts')
<script>
    // 1. CONTROL DE PESTAÑAS
    function openTab(evt, tabName) {
        evt.preventDefault(); 
        var contents = document.getElementsByClassName("vgi-tab-content");
        for (var i = 0; i < contents.length; i++) {
            contents[i].style.display = "none";
            contents[i].classList.remove("active-content");
        }
        var tabs = document.getElementsByClassName("vgi-tab");
        for (var i = 0; i < tabs.length; i++) {
            tabs[i].classList.remove("active");
        }
        document.getElementById(tabName).style.display = "block";
        document.getElementById(tabName).classList.add("active-content");
        if(evt) evt.currentTarget.classList.add("active");
    }

    // 2. TOGGLE SWITCH CUIDADOR
    function toggleSwitch() {
        const checkbox = document.getElementById('cuidadorSwitch');
        checkbox.checked = !checkbox.checked;
        toggleCuidador(checkbox.checked);
    }

    function toggleCuidador(isChecked) {
        const bloque = document.getElementById('bloque_cuidador');
        const container = document.getElementById('caregiverContainer');
        const inputVal = document.getElementById('cuidador_val');
        
        inputVal.value = isChecked ? 1 : 0; 

        if(isChecked) {
            bloque.style.display = 'block';
            container.classList.add('active');
            bloque.style.opacity = 0;
            bloque.style.transform = "translateY(-10px)";
            setTimeout(() => { 
                bloque.style.transition = "all 0.3s ease";
                bloque.style.opacity = 1; 
                bloque.style.transform = "translateY(0)";
            }, 10);
        } else {
            bloque.style.display = 'none';
            container.classList.remove('active');
        }
    }

    // 3. CÁLCULO DE IMC
    function calcularIMC() {
        const peso = parseFloat(document.getElementById('peso').value);
        const talla = parseFloat(document.getElementById('talla').value);
        const imcInput = document.getElementById('imc');
        const estadoLabel = document.getElementById('imc-estado');

        if (peso > 0 && talla > 0) {
            const imc = peso / (talla * talla);
            imcInput.value = imc.toFixed(2);

            let estado = "";
            if (imc < 18.5) estado = "Bajo Peso";
            else if (imc < 25) estado = "Peso Normal";
            else if (imc < 30) estado = "Sobrepeso";
            else estado = "Obesidad";
            
            estadoLabel.innerText = estado;
        } else {
            imcInput.value = "";
            estadoLabel.innerText = "Esperando datos...";
        }
    }

    // 4. MÁXIMOS DINAMÓMETRO
    document.addEventListener("input", function(e) {
        if(e.target.classList.contains('input-grid')) {
            updateMaxDynamo();
        }
    });

    function updateMaxDynamo() {
        // Derecha
        const d1 = parseFloat(document.querySelector('input[name="dinam_derecha_1"]').value) || 0;
        const d2 = parseFloat(document.querySelector('input[name="dinam_derecha_2"]').value) || 0;
        const d3 = parseFloat(document.querySelector('input[name="dinam_derecha_3"]').value) || 0;
        const maxD = Math.max(d1, d2, d3);
        document.getElementById('max_derecha').innerText = maxD > 0 ? maxD + ' Kg' : '-';

        // Izquierda
        const i1 = parseFloat(document.querySelector('input[name="dinam_izquierda_1"]').value) || 0;
        const i2 = parseFloat(document.querySelector('input[name="dinam_izquierda_2"]').value) || 0;
        const i3 = parseFloat(document.querySelector('input[name="dinam_izquierda_3"]').value) || 0;
        const maxI = Math.max(i1, i2, i3);
        document.getElementById('max_izquierda').innerText = maxI > 0 ? maxI + ' Kg' : '-';
    }

    // 5. CÁLCULO DE GIJÓN
    function calcularGijon() {
        const fields = ['gijon_familiar', 'gijon_economica', 'gijon_vivienda', 'gijon_relaciones', 'gijon_apoyo'];
        let total = 0;
        let complete = true;
        fields.forEach(f => {
            const el = document.querySelector(`input[name="${f}"]:checked`);
            if(el) total += parseInt(el.value);
            else complete = false;
        });

        const scoreEl = document.getElementById('gijon_score');
        const resultEl = document.getElementById('gijon_result');
        const inputTotal = document.getElementById('input_gijon_total');
        const inputVal = document.getElementById('input_gijon_valoracion');

        scoreEl.innerText = total;
        inputTotal.value = total;

        let result = "Sin evaluar";
        let color = "secondary";

        if(total > 0) {
            if(total <= 9) { result = "Buena / Aceptable situación social"; color = "success"; }
            else if(total <= 14) { result = "Existe riesgo social"; color = "warning text-dark"; }
            else { result = "Existe problema social"; color = "danger"; }
        }

        resultEl.innerText = result;
        resultEl.className = `badge bg-${color} fs-6`;
        inputVal.value = result;
    }

    // 6. CÁLCULO DE BARTHEL
    function calcularBarthel() {
        let total = 0;
        const radios = document.querySelectorAll('.barthel-radio:checked');
        radios.forEach(r => {
            total += parseInt(r.value);
        });

        // Actualizar visualización
        const scoreDisplay = document.getElementById('barthel_score_display');
        const resultDisplay = document.getElementById('barthel_result_display');
        const inputTotal = document.getElementById('input_barthel_total');
        const inputVal = document.getElementById('input_barthel_valoracion');

        scoreDisplay.innerText = total;
        inputTotal.value = total;

        let result = "";
        let colorClass = "bg-white text-primary"; // Default

        if (total == 100) {
            result = "INDEPENDIENTE";
            colorClass = "bg-success text-white";
        } else if (total >= 60) {
            result = "DEPENDIENTE LEVE";
            colorClass = "bg-info text-white";
        } else if (total >= 40) {
            result = "DEPENDIENTE MODERADO";
            colorClass = "bg-warning text-dark";
        } else if (total >= 20) {
            result = "DEPENDIENTE SEVERO";
            colorClass = "bg-orange text-white";
        } else {
            result = "DEPENDIENTE TOTAL";
            colorClass = "bg-warning text-white";
        }

        resultDisplay.innerText = result;
        resultDisplay.className = `badge px-3 py-2 fs-6 rounded-pill w-100 ${colorClass}`;
        inputVal.value = result;
    }

    // 7. NUEVA FUNCIÓN: CALCULAR LAWTON
    function calcularLawton() {
        let total = 0;
        const radios = document.querySelectorAll('.lawton-radio:checked');
        radios.forEach(r => {
            total += parseInt(r.value);
        });

        // Actualizar visualización
        const scoreDisplay = document.getElementById('lawton_score_display');
        const resultDisplay = document.getElementById('lawton_result_display');
        const inputTotal = document.getElementById('input_lawton_total');
        const inputVal = document.getElementById('input_lawton_valoracion');
        
        // Obtener sexo para interpretación
        const sexo = document.getElementById('paciente_sexo') ? document.getElementById('paciente_sexo').value : 'F';

        scoreDisplay.innerText = total;
        inputTotal.value = total;

        let result = "";
        let colorClass = "bg-white text-success"; 

        // Interpretación según documento (Ajustada)
        if (sexo === 'F') {
            if (total === 8) {
                result = "INDEPENDIENTE (Mujer)";
                colorClass = "bg-white text-success";
            } else {
                result = "DEPENDIENTE (0-7)";
                colorClass = "bg-warning text-dark";
            }
        } else {
            // Interpretación Hombres
            if (total >= 6) {
                 result = "INDEPENDIENTE (Hombre)";
                 colorClass = "bg-white text-success";
            } else {
                 result = "DEPENDIENTE";
                 colorClass = "bg-warning text-dark";
            }
        }

        resultDisplay.innerText = result;
        resultDisplay.className = `badge px-3 py-2 fs-6 rounded-pill w-100 ${colorClass}`;
        inputVal.value = result;
    }

    // 8. NUEVA FUNCIÓN: CALCULAR PFEIFFER
    function calcularPfeiffer() {
        let errores = 0;
        // Seleccionamos solo los radios marcados con valor 1 (Incorrecto)
        const radios = document.querySelectorAll('.pfeiffer-radio:checked');
        
        radios.forEach(r => {
            if(r.value == "1") errores++;
        });

        // Caso especial: Si marcó teléfono Y dirección, usualmente solo cuenta 1 punto si ambos fallan, 
        // pero seguiremos la lógica simple: Suma todo. 
        // Si el usuario quiere lógica estricta (max 10), podemos caparlo.
        if(errores > 10) errores = 10; 

        // Actualizar visualización
        const scoreDisplay = document.getElementById('pfeiffer_score_display');
        const resultDisplay = document.getElementById('pfeiffer_result_display');
        const inputTotal = document.getElementById('input_pfeiffer_errores');
        const inputVal = document.getElementById('input_pfeiffer_valoracion');

        scoreDisplay.innerText = errores;
        inputTotal.value = errores;

        let result = "";
        let colorClass = "bg-success text-white"; // Default

        if (errores <= 2) {
            result = "NORMAL / INTACTO";
            colorClass = "bg-success text-white";
        } else if (errores <= 4) {
            result = "DETERIORO LEVE";
            colorClass = "bg-warning text-dark";
        } else if (errores <= 7) {
            result = "DETERIORO MODERADO";
            colorClass = "bg-orange text-white";
        } else {
            result = "DETERIORO SEVERO";
            colorClass = "bg-danger text-white";
        }

        resultDisplay.innerText = result;
        resultDisplay.className = `badge px-3 py-2 fs-6 rounded-pill w-100 ${colorClass}`;
        inputVal.value = result;
    }

    // 9. NUEVA FUNCIÓN: CALCULAR RUDAS
    function calcularRudas() {
        // 1. Orientación (Checkboxes)
        let score1 = 0;
        document.querySelectorAll('.rudas-check:checked').forEach(c => score1 += parseInt(c.value));
        document.getElementById('val_rudas_1').value = score1;
        document.getElementById('txt_rudas_1').innerText = score1;

        // 2-6. Inputs numéricos
        const s2 = parseInt(document.getElementById('val_rudas_2').value) || 0;
        const s3 = parseInt(document.getElementById('val_rudas_3').value) || 0;
        const s4 = parseInt(document.getElementById('val_rudas_4').value) || 0;
        const s5 = parseInt(document.getElementById('val_rudas_5').value) || 0;
        const s6 = parseInt(document.getElementById('val_rudas_6').value) || 0;

        // Actualizar tabla resumen
        document.getElementById('txt_rudas_2').innerText = s2;
        document.getElementById('txt_rudas_3').innerText = s3;
        document.getElementById('txt_rudas_4').innerText = s4;
        document.getElementById('txt_rudas_5').innerText = s5;
        document.getElementById('txt_rudas_6').innerText = s6;

        // Total
        const total = score1 + s2 + s3 + s4 + s5 + s6;
        document.getElementById('rudas_total_display').innerText = total;
        document.getElementById('input_rudas_total').value = total;

        // Interpretación
        const badge = document.getElementById('rudas_interpretation_badge');
        const inputVal = document.getElementById('input_rudas_valoracion');
        let text = "";
        let color = "";

        if (total <= 22) {
            text = "SOSPECHA DE DETERIORO COGNITIVO";
            color = "bg-danger";
        } else {
            text = "RANGO ESPERADO";
            color = "bg-success";
        }

        badge.innerText = text;
        badge.className = `badge w-100 py-2 fs-6 ${color}`;
        inputVal.value = text;
    }

    // 10. NUEVA FUNCIÓN: CALCULAR MMSE
    function calcularMMSE() {
        let total = 0;
        // Simplemente contamos cuántos checkboxes de la clase mmse-check están marcados
        document.querySelectorAll('.mmse-check:checked').forEach(c => {
            total += parseInt(c.value);
        });

        const scoreDisplay = document.getElementById('mmse_score_display');
        const resultDisplay = document.getElementById('mmse_result_display');
        const inputTotal = document.getElementById('input_mmse_total');
        const inputVal = document.getElementById('input_mmse_valoracion');

        scoreDisplay.innerText = total;
        inputTotal.value = total;

        let result = "";
        let colorClass = "bg-secondary"; 

        if (total >= 27) {
            result = "NORMAL";
            colorClass = "bg-success";
        } else if (total >= 24) {
            result = "SOSPECHA PATOLÓGICA";
            colorClass = "bg-warning text-dark";
        } else if (total >= 12) {
            result = "DETERIORO";
            colorClass = "bg-orange text-white";
        } else if (total >= 9) {
            result = "DEMENCIA";
            colorClass = "bg-danger";
        } else {
            result = "DEMENCIA SEVERA";
            colorClass = "bg-danger";
        }

        resultDisplay.innerText = result;
        resultDisplay.className = `badge px-3 py-2 fs-6 rounded-pill w-100 mb-3 ${colorClass}`;
        inputVal.value = result;
    }

    // === LÓGICA DEL CANDADO MMSE ===
    function unlockMMSE() {
        const pin = document.getElementById('security_pin').value;
        const correctPin = "2026"; // <--- ¡AQUÍ PUEDES CAMBIAR LA CONTRASEÑA!
        
        if(pin === correctPin) {
            document.getElementById('mmse_lock_screen').style.opacity = '0';
            setTimeout(() => {
                document.getElementById('mmse_lock_screen').style.display = 'none';
            }, 500); // Espera medio segundo que termine la animación
            document.getElementById('mmse_content').classList.add('unlocked');
        } else {
            document.getElementById('pin_error').style.display = 'block';
            document.getElementById('security_pin').value = '';
            document.getElementById('security_pin').focus();
        }
    }

    // Permitir desbloquear con tecla ENTER
    document.getElementById('security_pin').addEventListener("keypress", function(event) {
        if (event.key === "Enter") {
            event.preventDefault();
            unlockMMSE();
        }
    });

    // NUEVO: DESBLOQUEO MINI-COG
    function unlockMinicog() {
        const pin = document.getElementById('minicog_pin').value;
        const correctPin = "2026"; // Mismo PIN
        
        if(pin === correctPin) {
            document.getElementById('minicog_lock_screen').style.opacity = '0';
            setTimeout(() => { document.getElementById('minicog_lock_screen').style.display = 'none'; }, 500);
            document.getElementById('minicog_content').classList.add('unlocked');
        } else {
            document.getElementById('minicog_pin_error').style.display = 'block';
            document.getElementById('minicog_pin').value = '';
        }
    }

    // Permitir Enter
    document.getElementById('minicog_pin').addEventListener("keypress", function(event) {
        if (event.key === "Enter") { event.preventDefault(); unlockMinicog(); }
    });

    // NUEVO: CÁLCULO MINI-COG
    function calcularMinicog() {
        // 1. Palabras (1 pto c/u)
        let words = 0;
        document.querySelectorAll('.minicog-check:checked').forEach(c => words++);
        
        // 2. Reloj (0 o 2)
        let clock = 0;
        const clockEl = document.querySelector('.minicog-clock:checked');
        if(clockEl) clock = parseInt(clockEl.value);

        // 3. Total
        const total = words + clock;

        // 4. Lógica de Interpretación (Según documento)
        let result = "";
        let colorClass = "bg-white text-indigo";

        if (words === 3) {
            // 3 palabras = NORMAL (reloj no importa)
            result = "PRUEBA NORMAL";
            colorClass = "bg-white text-success";
        } else if (words === 0) {
            // 0 palabras = ANÓMALA
            result = "PRUEBA ANÓMALA";
            colorClass = "bg-white text-danger";
        } else {
            // 1 o 2 palabras: Depende del reloj
            if (clock === 2) {
                result = "PRUEBA NORMAL"; // Reloj Normal
                colorClass = "bg-white text-success";
            } else {
                result = "PRUEBA ANÓMALA"; // Reloj Anormal
                colorClass = "bg-white text-danger";
            }
        }

        // Update UI
        document.getElementById('mc_words').innerText = words;
        document.getElementById('mc_clock').innerText = clock;
        document.getElementById('minicog_total_display').innerText = total;
        
        const badge = document.getElementById('minicog_result_display');
        badge.innerText = result;
        badge.className = `badge px-4 py-2 fs-6 rounded-pill w-100 mb-3 ${colorClass}`;

        // Inputs
        document.getElementById('input_minicog_total').value = total;
        document.getElementById('input_minicog_valoracion').value = result;
    }

    // === NUEVO: CÁLCULO GDS-4 ===
    function calculateGDS4() {
        let total = 0;
        document.querySelectorAll('.gds4-radio:checked').forEach(r => total += parseInt(r.value));
        
        document.getElementById('gds4_score').innerText = total;
        document.getElementById('input_gds_total').value = total;

        const yesavageSection = document.getElementById('yesavage_section');
        const msgNormal = document.getElementById('gds_msg_normal');
        const msgRisk = document.getElementById('gds_msg_risk');

        if (total >= 2) {
            msgNormal.style.display = 'none';
            msgRisk.style.display = 'flex';
            yesavageSection.style.display = 'block';
        } else {
            msgNormal.style.display = 'flex';
            msgRisk.style.display = 'none';
            yesavageSection.style.display = 'none';
        }
    }

    // === NUEVO: CÁLCULO YESAVAGE ===
    function calculateYesavage() {
        let total = 0;
        document.querySelectorAll('.ysg-radio:checked').forEach(r => total += parseInt(r.value));
        
        document.getElementById('ysg_score').innerText = total;
        document.getElementById('input_yesavage_total').value = total;

        const badge = document.getElementById('ysg_interpretation');
        let text = "";
        let color = "";

        if (total <= 5) {
            text = "NORMAL";
            color = "bg-success";
        } else if (total <= 9) {
            text = "RIESGO DE DEPRESIÓN";
            color = "bg-warning text-dark";
        } else {
            text = "DEPRESIÓN ESTABLECIDA";
            color = "bg-danger";
        }
        
        badge.innerText = text;
        badge.className = `badge fs-6 ${color}`;
    }

    // 11. NUEVA FUNCIÓN: CALCULAR MNA
    function calcularMNA() {
        let total = 0;
        
        // Sumar selects
        document.querySelectorAll('.mna-select').forEach(select => {
            let val = parseInt(select.value);
            total += val;
            
            // Actualizar la celda de puntaje individual
            let row = select.closest('tr');
            let scoreCell = row.querySelector('.text-center.fw-bold');
            if(scoreCell) scoreCell.innerText = val;
        });

        // Actualizar visualización total
        const scoreDisplay = document.getElementById('mna_score_display');
        const resultDisplay = document.getElementById('mna_result_display');
        const inputTotal = document.getElementById('input_mna_puntaje');
        const inputVal = document.getElementById('input_mna_valoracion');
        const cardBg = scoreDisplay.closest('.card'); // Para cambiar color de fondo

        scoreDisplay.innerText = total;
        inputTotal.value = total;

        let result = "";
        let colorClass = "";
        let cardClass = ""; // bg-success, bg-warning, bg-danger

        if (total >= 12) {
            result = "ESTADO NUTRICIONAL NORMAL";
            colorClass = "text-success";
            cardClass = "bg-success";
        } else if (total >= 8) {
            result = "RIESGO DE MALNUTRICIÓN";
            colorClass = "text-warning";
            cardClass = "bg-warning";
        } else {
            result = "MALNUTRICIÓN";
            colorClass = "text-danger";
            cardClass = "bg-danger";
        }

        // Actualizar Badge
        resultDisplay.innerText = result;
        resultDisplay.className = `badge bg-white ${colorClass} px-3 py-2 fs-6 rounded-pill w-100 mb-3`;
        inputVal.value = result;

        // Actualizar Color de Tarjeta
        cardBg.className = `card shadow-lg border-0 text-white sticky-top ${cardClass}`;
        scoreDisplay.className = `display-4 fw-bold ${colorClass}`;
    }

    // Auto-select IMC if available
    function autoSelectMNA_BMI() {
        // Obtenemos el IMC del input de la pestaña II
        const bmi = parseFloat(document.getElementById('imc').value);
        if(!isNaN(bmi) && bmi > 0) {
            const select = document.querySelector('select[name="mna_f"]');
            if(bmi < 19) select.value = "0";
            else if(bmi < 21) select.value = "1";
            else if(bmi < 23) select.value = "2";
            else select.value = "3";
            
            // Actualizar visual
            document.getElementById('mna_current_bmi').innerText = bmi;
            calcularMNA(); // Recalcular con el nuevo valor
        }
    }

    // Inicializar al cargar
    document.addEventListener("DOMContentLoaded", function() {
        const tieneCuidador = {{ ($vgi->cuidador_aplica ?? 0) == 1 ? 'true' : 'false' }};
        if(tieneCuidador) {
            document.getElementById('bloque_cuidador').style.display = 'block';
            document.getElementById('caregiverContainer').classList.add('active');
        }
        calcularGijon(); // Calcular Gijón al cargar
        calcularBarthel(); // Calcular Barthel al cargar
        calcularLawton(); // Calcular Lawton al cargar
        calcularPfeiffer(); // Calcular Pfeiffer al cargar
        calcularRudas(); // Calcular RUDAS al cargar
        calcularMMSE(); // Calcular MMSE al cargar
        calcularMinicog(); // Calcular Mini-Cog al cargar
        calculateGDS4(); // Chequear si hay que mostrar Yesavage al cargar
        calculateYesavage(); // Calcular Yesavage si existe
        calcularMNA(); // Calcular MNA al cargar
        
        // Intentar autoseleccionar IMC al cargar (si ya estaba guardado o calculado)
        setTimeout(autoSelectMNA_BMI, 500); 
    });

    // Listeners para Barthel, Lawton, Pfeiffer, RUDAS, MMSE, Mini-Cog, GDS, Yesavage y MNA
    document.addEventListener("change", function(e) {
        if(e.target.classList.contains('barthel-radio')) {
            calcularBarthel();
        }
        if(e.target.classList.contains('lawton-radio')) {
            calcularLawton();
        }
        if(e.target.classList.contains('pfeiffer-radio')) {
            calcularPfeiffer();
        }
        if(e.target.classList.contains('rudas-check')) {
            calcularRudas();
        }
        if(e.target.classList.contains('mmse-check')) {
            calcularMMSE();
        }
        if(e.target.classList.contains('minicog-check') || e.target.classList.contains('minicog-clock')) {
            calcularMinicog();
        }
        if(e.target.classList.contains('gds4-radio')) {
            calculateGDS4();
        }
        if(e.target.classList.contains('ysg-radio')) {
            calculateYesavage();
        }
        if(e.target.classList.contains('mna-select')) {
            calcularMNA();
        }
    });
    
    document.addEventListener("input", function(e) {
        if(e.target.classList.contains('rudas-input')) {
            calcularRudas();
        }
        if(e.target.id === 'peso' || e.target.id === 'talla') {
            setTimeout(autoSelectMNA_BMI, 100);
        }
    });
    
    // Listener específico para IMC
    document.getElementById('imc').addEventListener('change', autoSelectMNA_BMI);
</script>
@endpush