@extends('layouts.app')

{{-- Definimos el título específico para esta página --}}
@section('title', $title ?? 'WasiQhari - Inicio')

{{-- Esta sección es el contenido que se inyectará en @yield('content') --}}
@section('content')

    <section class="hero">
        <div class="hero-content">
            <div class="hero-text">
                <h1 class="hero-title animate-fade-in">
                    <span class="highlight">WasiQhari</span> - Hogar Solidario
                </h1>
                <p class="hero-description animate-slide-up">
                    Conectamos a adultos mayores en situación de vulnerabilidad con voluntarios 
                    y organizaciones sociales, promoviendo el cuidado, la compañía y la acción solidaria.
                </p>
                <div class="hero-buttons animate-slide-up">
                    <a href="{{ route('register') }}" class="btn btn-primary">
                        <i class="fas fa-hands-helping"></i> Únete como Voluntario
                    </a>
                    <a href="#features" class="btn btn-secondary">
                        <i class="fas fa-info-circle"></i> Conoce Más
                    </a>
                </div>
            </div>
            <div class="hero-image animate-fade-in">
                <img src="https://i.postimg.cc/XY7jQ7Zj/elderly-care.png" alt="Cuidado de adultos mayores" class="floating">
            </div>
        </div>
        
        <div class="hero-waves">
            <svg viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z" opacity=".25" class="shape-fill"></path>
                <path d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z" opacity=".5" class="shape-fill"></path>
                <path d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z" class="shape-fill"></path>
            </svg>
        </div>
    </section>

    <section class="stats">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-card animate-number" data-target="4700000">
                    <i class="fas fa-users"></i>
                    <h3 class="stat-number">4.7M+</h3>
                    <p class="stat-text">Adultos mayores en el Perú</p>
                </div>
                <div class="stat-card animate-number" data-target="80">
                    <i class="fas fa-heart-broken"></i>
                    <h3 class="stat-number">80%</h3>
                    <p class="stat-text">En estado de abandono en Juliaca</p>
                </div>
                <div class="stat-card animate-number" data-target="25">
                    <i class="fas fa-home"></i>
                    <h3 class="stat-number">25%</h3>
                    <p class="stat-text">Viven solos en Lima</p>
                </div>
                <div class="stat-card">
                    <i class="fas fa-hand-holding-heart"></i>
                    <h3 class="stat-number">100%</h3>
                    <p class="stat-text">Comprometidos con el cambio</p>
                </div>
            </div>
        </div>
    </section>

    <section id="features" class="features">
        <div class="container">
            <h2 class="section-title">Nuestros Servicios</h2>
            <p class="section-subtitle">Plataforma integral de apoyo y monitoreo social</p>
            
            <div class="features-grid">
                <div class="feature-card" data-aos="fade-up">
                    <div class="feature-icon">
                        <i class="fas fa-user-friends"></i>
                    </div>
                    <h3>WasiQhari</h3>
                    <p>Registro y monitoreo de adultos mayores vulnerables con geolocalización, alertas y reportes periódicos.</p>
                    <ul class="feature-list">
                        <li><i class="fas fa-check"></i> Registro de datos personales</li>
                        <li><i class="fas fa-check"></i> Seguimiento de salud</li>
                        <li><i class="fas fa-check"></i> Alertas de emergencia</li>
                        <li><i class="fas fa-check"></i> Geolocalización</li>
                    </ul>
                </div>
                
                <div class="feature-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-icon">
                        <i class="fas fa-hands-helping"></i>
                    </div>
                    <h3>AyniConnect</h3>
                    <p>Red de voluntarios que conecta personas dispuestas a ayudar con adultos mayores que necesitan apoyo.</p>
                    <ul class="feature-list">
                        <li><i class="fas fa-check"></i> Registro de voluntarios</li>
                        <li><i class="fas fa-check"></i> Gestión de organizaciones</li>
                        <li><i class="fas fa-check"></i> Eventos solidarios</li>
                        <li><i class="fas fa-check"></i> Asignación inteligente</li>
                    </ul>
                </div>
                
                <div class="feature-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3>Panel de Impacto</h3>
                    <p>Dashboard con métricas sociales para medir el impacto real de nuestra labor en la comunidad.</p>
                    <ul class="feature-list">
                        <li><i class="fas fa-check"></i> Métricas en tiempo real</li>
                        <li><i class="fas fa-check"></i> Reportes personalizados</li>
                        <li><i class="fas fa-check"></i> Visualización de datos</li>
                        <li><i class="fas fa-check"></i> Análisis de tendencias</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section class="cta">
        <div class="container">
            <div class="cta-content">
                <h2>¿Listo para marcar la diferencia?</h2>
                <p>Únete a nuestra comunidad solidaria y ayuda a construir un futuro mejor para nuestros adultos mayores.</p>
                <div class="cta-buttons">
                    <a href="{{ route('register') }}" class="btn btn-large btn-primary">
                        <i class="fas fa-user-plus"></i> Regístrate Ahora
                    </a>
                    <button id="startTour" class="btn btn-large btn-secondary">
                        <i class="fas fa-play-circle"></i> Tour Virtual
                    </button>
                </div>
            </div>
        </div>
    </section>

@endsection

{{-- Empujamos los scripts específicos de esta página al final del <body> --}}
@push('scripts')
    <script>
    // Tour Virtual con Driver.js
    document.getElementById('startTour')?.addEventListener('click', function() {
        const driver = new Driver();
        
        driver.defineSteps([
            {
                element: '.hero',
                popover: {
                    title: 'Bienvenido a WasiQhari',
                    description: 'Plataforma de apoyo y monitoreo social para adultos mayores',
                    position: 'bottom'
                }
            },
            {
                element: '.stats',
                popover: {
                    title: 'Impacto Real',
                    description: 'Conoce las estadísticas que motivan nuestro trabajo',
                    position: 'top'
                }
            },
            {
                element: '.features',
                popover: {
                    title: 'Nuestros Servicios',
                    description: 'Descubre todas las funcionalidades de nuestra plataforma',
                    position: 'top'
                }
            },
            {
                element: '.nav-menu',
                popover: {
                    title: 'Navegación',
                    description: 'Accede a todas las secciones de la plataforma',
                    position: 'bottom'
                }
            }
        ]);
        
        driver.start();
    });

    // Animación de números
    const animateNumbers = () => {
        const statCards = document.querySelectorAll('.animate-number');
        
        statCards.forEach(card => {
            const target = parseInt(card.getAttribute('data-target'));
            const numberElement = card.querySelector('.stat-number');
            let current = 0;
            const increment = target / 100;
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                numberElement.textContent = Math.floor(current).toLocaleString() + (card.getAttribute('data-target') === '80' ? '%' : '');
            }, 20);
        });
    };

    // Intersection Observer para animaciones
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                if (entry.target.classList.contains('stats')) {
                    animateNumbers();
                }
                entry.target.classList.add('animate-visible');
            }
        });
    }, { threshold: 0.1 });

    // Observar elementos
    document.querySelectorAll('.animate-fade-in, .animate-slide-up, .stats').forEach(el => {
        observer.observe(el);
    });
    </script>
@endpush