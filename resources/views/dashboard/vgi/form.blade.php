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
            <div class="vgi-tabs d-flex justify-content-center gap-4">
                <button class="vgi-tab active" onclick="openTab(event, 'tab-social')">
                    <i class="fas fa-user-circle"></i> <span>Datos Sociales</span>
                </button>
                <button class="vgi-tab" onclick="openTab(event, 'tab-clinica')">
                    <i class="fas fa-weight"></i> <span>Antropometría</span>
                </button>
                <button class="vgi-tab" onclick="openTab(event, 'tab-funcional')">
                    <i class="fas fa-walking"></i> <span>Funcional</span>
                </button>
                <button class="vgi-tab" onclick="openTab(event, 'tab-mental')">
                    <i class="fas fa-brain"></i> <span>Mental</span>
                </button>
                <button class="vgi-tab" onclick="openTab(event, 'tab-fisica')">
                    <i class="fas fa-apple-alt"></i> <span>Física</span>
                </button>
            </div>
        </div>

        <form action="{{ route('adultos.vgi.store', $adulto->id) }}" method="POST" class="p-4 p-lg-5 bg-soft-gray">
            @csrf
            
            <div id="tab-social" class="vgi-tab-content active-content">
                
                <div class="metadata-banner bg-white rounded-4 shadow-sm p-4 mb-5 border-start border-5 border-purple">
                    <div class="row g-4 align-items-center justify-content-between">
                        <div class="col-md-3">
                            <label class="label-mini text-muted">Fecha de Registro</label>
                            <div class="d-flex align-items-center gap-2">
                                <div class="icon-sq bg-purple-light text-purple"><i class="far fa-calendar-alt"></i></div>
                                <input type="date" name="fecha_evaluacion" class="form-control border-0 bg-transparent fw-bold p-0 text-dark" 
                                       value="{{ \Carbon\Carbon::now('America/Lima')->format('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-md-2 border-start ps-4">
                            <label class="label-mini text-muted">Hora</label>
                            <div class="d-flex align-items-center gap-2">
                                <div class="icon-sq bg-purple-light text-purple"><i class="far fa-clock"></i></div>
                                <input type="time" name="hora_evaluacion" class="form-control border-0 bg-transparent fw-bold p-0 text-dark" 
                                       value="{{ \Carbon\Carbon::now('America/Lima')->format('H:i') }}">
                            </div>
                        </div>
                        <div class="col-md-6 border-start ps-4">
                            <label class="label-mini text-purple fw-bold mb-1">N° Historia Clínica (HCL)</label>
                            <input type="text" name="hcl" class="form-control form-control-lg bg-light border-0 text-dark fw-bold" 
                                   placeholder="---" value="{{ $vgi->hcl ?? '' }}">
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
                                <label class="label-input">Teléfonos de Contacto</label>
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
                        
                        <div class="ms-auto d-flex align-items-center gap-2">
                            <span class="text-muted small fw-bold">Mano Dominante:</span>
                            <select name="mano_dominante" class="form-select form-select-sm w-auto border-secondary">
                                <option value="">Seleccionar...</option>
                                <option value="Derecha" {{ ($vgi->mano_dominante ?? '') == 'Derecha' ? 'selected' : '' }}>Derecha</option>
                                <option value="Izquierda" {{ ($vgi->mano_dominante ?? '') == 'Izquierda' ? 'selected' : '' }}>Izquierda</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="section-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0 text-center align-middle">
                                <thead class="bg-light text-secondary small text-uppercase">
                                    <tr>
                                        <th class="py-3" style="width: 20%;">Mano</th>
                                        <th class="py-3">1ra Medida (Kg)</th>
                                        <th class="py-3">2da Medida (Kg)</th>
                                        <th class="py-3">3ra Medida (Kg)</th>
                                        <th class="py-3 bg-soft-gray">Máximo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="fw-bold text-end pe-4"><i class="far fa-hand-paper me-2"></i> Derecha</td>
                                        <td class="p-2"><input type="number" step="0.1" name="dinam_derecha_1" class="form-control border-0 text-center bg-transparent input-grid" placeholder="-" value="{{ $vgi->dinam_derecha_1 ?? '' }}"></td>
                                        <td class="p-2"><input type="number" step="0.1" name="dinam_derecha_2" class="form-control border-0 text-center bg-transparent input-grid" placeholder="-" value="{{ $vgi->dinam_derecha_2 ?? '' }}"></td>
                                        <td class="p-2"><input type="number" step="0.1" name="dinam_derecha_3" class="form-control border-0 text-center bg-transparent input-grid" placeholder="-" value="{{ $vgi->dinam_derecha_3 ?? '' }}"></td>
                                        <td class="bg-soft-gray fw-bold text-dark" id="max_derecha">-</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold text-end pe-4"><i class="far fa-hand-paper me-2" style="transform: scaleX(-1);"></i> Izquierda</td>
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

                <div class="mt-5 pt-4 border-top">
                    <button type="button" class="btn btn-outline-secondary btn-sm w-100" onclick="document.getElementById('extra-clinico').style.display = 'block'; this.style.display='none';">
                        <i class="fas fa-chevron-down me-2"></i> Mostrar Comorbilidades (Si Aplica)
                    </button>
                    
                    <div id="extra-clinico" style="display: none;" class="mt-4 fade-in-down">
                        <div class="section-container">
                            <div class="section-header">
                                <div class="header-icon bg-danger text-white"><i class="fas fa-heartbeat"></i></div>
                                <h4 class="header-title text-danger">Comorbilidades</h4>
                            </div>
                            <div class="section-body p-4">
                                <div class="row g-3">
                                    @foreach(['HTA' => 'tiene_hta', 'Diabetes' => 'tiene_diabetes', 'EPOC/Asma' => 'tiene_epoc', 'Insuf. Card.' => 'tiene_icc', 'Demencia' => 'tiene_demencia', 'Artrosis' => 'tiene_artrosis', 'Hipoacusia' => 'tiene_audicion_baja', 'Dism. Visual' => 'tiene_vision_baja', 'Caídas Recientes' => 'caidas_recientes', 'Incontinencia' => 'tiene_incontinencia'] as $label => $name)
                                    <div class="col-6 col-md-3">
                                        <label class="selection-card p-3">
                                            <input type="checkbox" name="{{ $name }}" value="1" {{ ($vgi->$name ?? 0) ? 'checked' : '' }}>
                                            <div class="card-inner flex-row align-items-center gap-3 p-2 h-auto text-start">
                                                <div class="check-box-visual"></div>
                                                <span class="text fw-bold text-dark">{{ $label }}</span>
                                            </div>
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="mt-4">
                                    <label class="label-input">Otras Enfermedades</label>
                                    <input type="text" name="otras_enfermedades" class="form-control modern-input" placeholder="Especifique..." value="{{ $vgi->otras_enfermedades ?? '' }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="tab-funcional" class="vgi-tab-content">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="section-container h-100">
                            <div class="section-header bg-primary-light text-primary">
                                <div class="icon-box bg-primary text-white"><i class="fas fa-walking"></i></div>
                                <h5 class="m-0 fw-bold">Índice de Barthel</h5>
                            </div>
                            <div class="section-body p-4">
                                <div class="mb-3"><label class="label-input">Comer</label><select name="barthel_comer" class="form-select modern-input"><option value="10">Independiente (10)</option><option value="5">Ayuda (5)</option><option value="0">Dependiente (0)</option></select></div>
                                <div class="mb-3"><label class="label-input">Lavarse</label><select name="barthel_lavarse" class="form-select modern-input"><option value="5">Independiente (5)</option><option value="0">Dependiente (0)</option></select></div>
                                <div class="mb-3"><label class="label-input">Vestirse</label><select name="barthel_vestirse" class="form-select modern-input"><option value="10">Independiente (10)</option><option value="5">Ayuda (5)</option><option value="0">Dependiente (0)</option></select></div>
                                <div class="mb-3"><label class="label-input">Deambulación</label><select name="barthel_deambulacion" class="form-select modern-input"><option value="15">Independiente (15)</option><option value="10">Ayuda (10)</option><option value="5">Silla Ruedas (5)</option><option value="0">Inmóvil (0)</option></select></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="section-container h-100">
                            <div class="section-header bg-success-light text-success">
                                <div class="icon-box bg-success text-white"><i class="fas fa-home"></i></div>
                                <h5 class="m-0 fw-bold">Lawton & Brody</h5>
                            </div>
                            <div class="section-body p-4">
                                <div class="mb-3"><label class="label-input">Uso Teléfono</label><input type="number" min="0" max="1" name="lawton_telefono" class="form-control modern-input" placeholder="1 o 0"></div>
                                <div class="mb-3"><label class="label-input">Compras</label><input type="number" min="0" max="1" name="lawton_compras" class="form-control modern-input" placeholder="1 o 0"></div>
                                <div class="mb-3"><label class="label-input">Comida</label><input type="number" min="0" max="1" name="lawton_comida" class="form-control modern-input" placeholder="1 o 0"></div>
                                <div class="mb-3"><label class="label-input">Medicación</label><input type="number" min="0" max="1" name="lawton_medicacion" class="form-control modern-input" placeholder="1 o 0"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="tab-mental" class="vgi-tab-content">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="section-container border-warning h-100">
                            <div class="section-header bg-warning-light text-warning">
                                <div class="icon-box bg-warning text-white"><i class="fas fa-brain"></i></div>
                                <h5 class="m-0 fw-bold">Pfeiffer</h5>
                            </div>
                            <div class="section-body p-5 text-center">
                                <input type="number" name="pfeiffer_errores" min="0" max="10" class="form-control modern-input fs-1 text-center fw-bold text-warning border-warning" placeholder="0" value="{{ $vgi->pfeiffer_errores ?? '' }}">
                                <div class="text-muted mt-3 fw-bold">Errores cometidos (0-10)</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="section-container border-info h-100">
                            <div class="section-header bg-info-light text-info">
                                <div class="icon-box bg-info text-white"><i class="fas fa-cloud-rain"></i></div>
                                <h5 class="m-0 fw-bold">Yesavage (Depresión)</h5>
                            </div>
                            <div class="section-body p-5 text-center">
                                <input type="number" name="yesavage_total" min="0" class="form-control modern-input fs-1 text-center fw-bold text-info border-info" placeholder="0" value="{{ $vgi->yesavage_total ?? '' }}">
                                <div class="text-muted mt-3 fw-bold">Puntaje Total</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

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

    // Inicializar al cargar
    document.addEventListener("DOMContentLoaded", function() {
        const tieneCuidador = {{ ($vgi->cuidador_aplica ?? 0) == 1 ? 'true' : 'false' }};
        if(tieneCuidador) {
            document.getElementById('bloque_cuidador').style.display = 'block';
            document.getElementById('caregiverContainer').classList.add('active');
        }
    });
</script>
@endpush