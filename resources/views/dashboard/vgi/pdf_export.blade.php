<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Informe VGI - {{ $adulto->dni }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { width: 100%; border-bottom: 2px solid #444; padding-bottom: 10px; margin-bottom: 20px; }
        .header-title { font-size: 18px; font-weight: bold; text-transform: uppercase; color: #2c3e50; }
        .meta { width: 100%; margin-bottom: 20px; }
        .meta td { padding: 5px; }
        .label { font-weight: bold; color: #555; }
        
        .section-title { 
            background-color: #f0f0f0; padding: 8px; font-weight: bold; 
            border-left: 4px solid #6f42c1; margin-top: 20px; margin-bottom: 10px; 
            font-size: 14px;
        }
        
        .results-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .results-table th, .results-table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .results-table th { background-color: #f8f9fa; font-weight: bold; }
        
        .score { font-weight: bold; font-size: 13px; }
        .badge-risk { color: #d9534f; font-weight: bold; }
        .badge-ok { color: #5cb85c; font-weight: bold; }
        
        .plan-box { 
            border: 1px solid #ccc; padding: 15px; min-height: 100px; 
            background-color: #fafafa; margin-top: 10px; 
        }
        
        .footer { position: fixed; bottom: 0; left: 0; right: 0; height: 30px; border-top: 1px solid #ccc; padding-top: 10px; font-size: 10px; text-align: center; color: #777; }
    </style>
</head>
<body>

    <table class="header">
        <tr>
            <td width="70%">
                <div class="header-title">Valoración Geriátrica Integral (VGI)</div>
                <div>Hospital / Clínica WasiQhari</div>
            </td>
            <td width="30%" align="right">
                <div>Fecha: {{ \Carbon\Carbon::parse($vgi->fecha_evaluacion)->format('d/m/Y') }}</div>
                <div>HCL: {{ $vgi->hcl ?? '---' }}</div>
            </td>
        </tr>
    </table>

    <table class="meta">
        <tr>
            <td width="15%" class="label">Paciente:</td>
            <td width="35%">{{ $adulto->nombres }} {{ $adulto->apellidos }}</td>
            <td width="15%" class="label">DNI:</td>
            <td width="35%">{{ $adulto->dni }}</td>
        </tr>
        <tr>
            <td class="label">Edad:</td>
            <td>{{ $adulto->edad }} años</td>
            <td class="label">Sexo:</td>
            <td>{{ $adulto->sexo }}</td>
        </tr>
        <tr>
            <td class="label">Cuidador:</td>
            <td>{{ ($vgi->cuidador_aplica) ? 'SÍ (' . $vgi->parentesco_cuidador . ')' : 'NO' }}</td>
            <td class="label">Evaluador:</td>
            <td>{{ Auth::user()->name ?? 'Personal de Salud' }}</td>
        </tr>
    </table>

    <div class="section-title">I. RESUMEN DE VALORACIÓN</div>
    
    <table class="results-table">
        <thead>
            <tr>
                <th>Escala / Prueba</th>
                <th>Puntaje</th>
                <th>Interpretación / Resultado</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Barthel</strong> (AVD Básicas)</td>
                <td class="score">{{ $vgi->barthel_total }} / 100</td>
                <td>{{ $vgi->barthel_valoracion ?? '-' }}</td>
            </tr>
            <tr>
                <td><strong>Lawton & Brody</strong> (AVD Inst.)</td>
                <td class="score">{{ $vgi->lawton_total }} / 8</td>
                <td>{{ $vgi->lawton_valoracion ?? '-' }}</td>
            </tr>
            
            <tr>
                <td><strong>Pfeiffer</strong> (Cognitivo)</td>
                <td class="score">{{ $vgi->pfeiffer_errores }} errores</td>
                <td>{{ $vgi->pfeiffer_valoracion ?? '-' }}</td>
            </tr>
            <tr>
                <td><strong>MMSE</strong> (Folstein)</td>
                <td class="score">{{ $vgi->mmse_total_final }} / 30</td>
                <td>{{ $vgi->mmse_valoracion_final ?? '-' }}</td>
            </tr>
            <tr>
                <td><strong>Mini-Cog</strong></td>
                <td class="score">{{ $vgi->minicog_total }} / 5</td>
                <td>{{ $vgi->minicog_valoracion ?? '-' }}</td>
            </tr>
            <tr>
                <td><strong>GDS / Yesavage</strong> (Afectivo)</td>
                <td class="score">{{ $vgi->yesavage_total }} / 15</td>
                <td>
                    @if($vgi->yesavage_total > 5) <span class="badge-risk">DEPRESIÓN / RIESGO</span>
                    @else <span class="badge-ok">NORMAL</span> @endif
                </td>
            </tr>

            <tr>
                <td><strong>MNA</strong> (Nutrición)</td>
                <td class="score">{{ $vgi->mna_puntaje }} / 14</td>
                <td>{{ $vgi->mna_valoracion ?? '-' }}</td>
            </tr>
            <tr>
                <td><strong>SARC-F</strong> (Sarcopenia)</td>
                <td class="score">{{ $vgi->sarcf_total }} / 10</td>
                <td>{{ $vgi->sarcf_valoracion ?? '-' }}</td>
            </tr>
            <tr>
                <td><strong>SPPB</strong> (Desempeño Físico)</td>
                <td class="score">{{ $vgi->sppb_total }} / 12</td>
                <td>{{ $vgi->sppb_valoracion ?? '-' }}</td>
            </tr>
            <tr>
                <td><strong>FRAIL</strong> (Fragilidad)</td>
                <td class="score">{{ $vgi->frail_puntaje }} pts</td>
                <td>{{ $vgi->frail_valoracion_texto ?? '-' }}</td>
            </tr>
            <tr>
                <td><strong>CFS</strong> (Escala Clínica)</td>
                <td class="score">Nivel {{ $vgi->cfs_puntaje }}</td>
                <td>{{ $vgi->cfs_valoracion ?? '-' }}</td>
            </tr>
        </tbody>
    </table>

    <div class="section-title">II. COMORBILIDADES Y SÍNDROMES</div>
    <p>
        <strong>Patologías:</strong>
        {{ $vgi->tiene_hta ? 'HTA, ' : '' }}
        {{ $vgi->tiene_diabetes ? 'Diabetes, ' : '' }}
        {{ $vgi->tiene_epoc ? 'EPOC, ' : '' }}
        {{ $vgi->tiene_icc ? 'ICC, ' : '' }}
        {{ $vgi->tiene_demencia ? 'Demencia, ' : '' }}
        {{ $vgi->tiene_artrosis ? 'Artrosis, ' : '' }}
        {{ $vgi->tiene_cancer ? 'Cáncer (' . $vgi->cancer_info . '), ' : '' }}
        {{ $vgi->otras_enfermedades }}
    </p>
    <p>
        <strong>Síndromes Geriátricos:</strong>
        @if($vgi->sindrome_caidas) <span class="badge-risk">Caídas Recientes</span> @endif
        @if($vgi->sindrome_incontinencia) <span class="badge-risk"> | Incontinencia</span> @endif
        @if($vgi->sindrome_delirio) <span class="badge-risk"> | Delirio Previo</span> @endif
    </p>

    <div class="section-title">III. PLAN DE TRABAJO Y RECOMENDACIONES</div>
    <div class="plan-box">
        {!! nl2br(e($vgi->plan_cuidados)) !!}
    </div>

    <div class="footer">
        Generado el {{ date('d/m/Y H:i') }} por el sistema WasiQhari. Documento confidencial para uso médico.
    </div>

</body>
</html>