@extends('layouts.dashboard')

@section('title', 'Reportes y Estadísticas')

@section('content')
<div class="dashboard-container">
    
    <div class="dashboard-header">
        <div class="header-content">
            <h1>Centro de Reportes</h1>
            <p>Descarga la información oficial de WasiQhari en tiempo real.</p>
        </div>
    </div>

    <div class="metrics-grid">
        <div class="metric-card">
            <span>Total Beneficiarios</span>
            <h2>{{ App\Models\AdultoMayor::count() }}</h2>
        </div>
        <div class="metric-card">
            <span>Visitas este Mes</span>
            <h2>{{ App\Models\Visita::whereMonth('fecha_visita', date('m'))->count() }}</h2>
        </div>
        <div class="metric-card">
            <span>Voluntarios Activos</span>
            <h2>{{ App\Models\Voluntario::where('estado', 'Activo')->count() }}</h2>
        </div>
    </div>

    <div class="report-cards-container">
        
        <div class="report-card">
            <div class="report-icon excel">
                <i class="fas fa-file-csv"></i>
            </div>
            <div class="report-info">
                <h3>Base de Datos General</h3>
                <p>Listado completo de adultos mayores con todos sus datos sociodemográficos.</p>
            </div>
            <div class="report-actions">
                <a href="{{ route('reportes.excel.general') }}" class="btn-download excel">
                    <i class="fas fa-download"></i> Excel (CSV)
                </a>
                <a href="{{ route('reportes.imprimir', ['tipo' => 'general']) }}" target="_blank" class="btn-download pdf">
                    <i class="fas fa-print"></i> Imprimir PDF
                </a>
            </div>
        </div>

        <div class="report-card">
            <div class="report-icon excel">
                <i class="fas fa-list-alt"></i>
            </div>
            <div class="report-info">
                <h3>Registro de Visitas</h3>
                <p>Histórico detallado de todas las visitas realizadas, tipos y emergencias.</p>
            </div>
            <div class="report-actions">
                <a href="{{ route('reportes.excel.visitas') }}" class="btn-download excel">
                    <i class="fas fa-download"></i> Excel (CSV)
                </a>
                <a href="{{ route('reportes.imprimir', ['tipo' => 'visitas']) }}" target="_blank" class="btn-download pdf">
                    <i class="fas fa-print"></i> Imprimir PDF
                </a>
            </div>
        </div>

        <div class="report-card">
            <div class="report-icon excel">
                <i class="fas fa-users-cog"></i>
            </div>
            <div class="report-info">
                <h3>Equipo de Voluntarios</h3>
                <p>Información de contacto y estado actual de todos los voluntarios.</p>
            </div>
            <div class="report-actions">
                <a href="{{ route('reportes.excel.voluntarios') }}" class="btn-download excel">
                    <i class="fas fa-download"></i> Excel (CSV)
                </a>
                <a href="{{ route('reportes.imprimir', ['tipo' => 'voluntarios']) }}" target="_blank" class="btn-download pdf">
                    <i class="fas fa-print"></i> Imprimir PDF
                </a>
            </div>
        </div>

    </div>
</div>
@endsection

@push('styles')
<style>
    .metrics-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 40px;
    }
    .metric-card {
        background: white;
        padding: 20px;
        border-radius: 12px;
        border: 1px solid #eee;
        text-align: center;
        box-shadow: 0 2px 10px rgba(0,0,0,0.03);
    }
    .metric-card span { color: #7f8c8d; font-size: 0.9rem; font-weight: 600; text-transform: uppercase; }
    .metric-card h2 { color: var(--dark-color); font-size: 2.5rem; margin: 5px 0 0 0; font-weight: 700; }

    .report-cards-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
    }
    .report-card {
        background: white;
        border-radius: 15px;
        padding: 30px;
        border: 1px solid #e0e0e0;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
    .report-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        border-color: var(--primary-color);
    }
    .report-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        margin-bottom: 20px;
    }
    .report-icon.excel { background: #e6ffe6; color: #27ae60; }
    
    .report-info h3 { margin: 0 0 10px 0; color: var(--dark-color); }
    .report-info p { color: #7f8c8d; font-size: 0.95rem; margin-bottom: 25px; line-height: 1.5; }
    
    .report-actions {
        display: flex;
        gap: 10px;
        width: 100%;
    }
    .btn-download {
        flex: 1;
        padding: 10px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.2s;
    }
    .btn-download.excel {
        background: #27ae60;
        color: white;
    }
    .btn-download.excel:hover { background: #219150; }
    
    .btn-download.pdf {
        background: #fff;
        color: #e74c3c;
        border: 2px solid #e74c3c;
    }
    .btn-download.pdf:hover { background: #e74c3c; color: white; }
</style>
@endpush