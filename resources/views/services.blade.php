<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'WasiQhari - Servicios' }}</title>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Estilos CSS -->
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>
    @include('header')

    <!-- Hero Section -->
    <section class="page-hero">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title animate-fade-in">Nuestros <span class="highlight">Servicios</span></h1>
                <p class="hero-description animate-slide-up">
                    Descubre todas las funcionalidades que ofrece WasiQhari para el cuidado de adultos mayores
                </p>
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

    <!-- Servicios Principales -->
    <section class="services-main">
        <div class="container">
            <div class="service-hero">
                <div class="service-hero-content" data-aos="fade-right">
                    <h2>WasiQhari Platform</h2>
                    <p class="service-tagline">Sistema integral de monitoreo y apoyo social</p>
                    <p>
                        Plataforma central que permite el registro, seguimiento y monitoreo 
                        continuo de adultos mayores en situación de vulnerabilidad, integrando 
                        tecnología de geolocalización, alertas inteligentes y reportes automáticos.
                    </p>
                    <div class="service-features">
                        <div class="feature-item">
                            <i class="fas fa-check-circle"></i>
                            <span>Registro completo de datos personales y de salud</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-check-circle"></i>
                            <span>Geolocalización en tiempo real</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-check-circle"></i>
                            <span>Sistema de alertas de emergencia</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-check-circle"></i>
                            <span>Reportes periódicos automáticos</span>
                        </div>
                    </div>
                </div>
                <div class="service-hero-image" data-aos="fade-left">
                    <img src="https://www.questionpro.com/blog/wp-content/uploads/2024/01/2619-seguimiento-de-datos.jpg" alt="Dashboard WasiQhari" class="service-image">
                </div>
            </div>
        </div>
    </section>

    <!-- Módulo AyniConnect -->
    <section class="ayni-section">
        <div class="container">
            <div class="service-hero reverse">
                <div class="service-hero-image" data-aos="fade-right">
                    <img src="https://aiij.org/wp-content/uploads/2021/12/RSV-Home-AIIJ.jpg" alt="Red de Voluntarios" class="service-image">
                </div>
                <div class="service-hero-content" data-aos="fade-left">
                    <h2>AyniConnect</h2>
                    <p class="service-tagline">Red solidaria de voluntarios</p>
                    <p>
                        Módulo especializado que conecta a voluntarios comprometidos con adultos 
                        mayores que necesitan acompañamiento, creando una comunidad basada en 
                        los principios de reciprocidad y apoyo mutuo.
                    </p>
                    <div class="service-features">
                        <div class="feature-item">
                            <i class="fas fa-check-circle"></i>
                            <span>Registro y verificación de voluntarios</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-check-circle"></i>
                            <span>Asignación inteligente por proximidad</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-check-circle"></i>
                            <span>Gestión de eventos solidarios</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-check-circle"></i>
                            <span>Sistema de calificaciones y reviews</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Características Detalladas -->
    <section class="features-detailed">
        <div class="container">
            <h2 class="section-title">Características Avanzadas</h2>
            <p class="section-subtitle">Tecnología al servicio del bienestar social</p>
            
            <div class="features-grid-detailed">
                <div class="feature-detailed" data-aos="fade-up">
                    <div class="feature-icon-detailed">
                        <i class="fas fa-map-marked-alt"></i>
                    </div>
                    <h3>Geolocalización Inteligente</h3>
                    <p>Sistema de ubicación que permite localizar adultos mayores y voluntarios cercanos para una respuesta rápida y eficiente.</p>
                </div>
                
                <div class="feature-detailed" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-icon-detailed">
                        <i class="fas fa-bell"></i>
                    </div>
                    <h3>Alertas Automáticas</h3>
                    <p>Sistema de notificaciones que alerta a familiares y centros de salud en caso de emergencias o situaciones de riesgo.</p>
                </div>
                
                <div class="feature-detailed" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-icon-detailed">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <h3>Dashboard de Impacto</h3>
                    <p>Panel de control con métricas en tiempo real que muestra el impacto social de todas las actividades realizadas.</p>
                </div>
                
                <div class="feature-detailed" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature-icon-detailed">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h3>App Móvil</h3>
                    <p>Aplicación móvil disponible para iOS y Android que permite a voluntarios reportar visitas y recibir alertas.</p>
                </div>
                
                <div class="feature-detailed" data-aos="fade-up" data-aos-delay="400">
                    <div class="feature-icon-detailed">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>Seguridad Avanzada</h3>
                    <p>Sistema de encriptación y autenticación de dos factores para proteger la información sensible de los usuarios.</p>
                </div>
                
                <div class="feature-detailed" data-aos="fade-up" data-aos-delay="500">
                    <div class="feature-icon-detailed">
                        <i class="fas fa-comments"></i>
                    </div>
                    <h3>Comunicación Integrada</h3>
                    <p>Sistema de mensajería interna que facilita la comunicación entre voluntarios, familiares y adultos mayores.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Planes -->
    <section class="plans-section">
        <div class="container">
            <h2 class="section-title">Planes de Implementación</h2>
            <p class="section-subtitle">Soluciones adaptadas a cada necesidad</p>
            
            <div class="plans-grid">
                <div class="plan-card" data-aos="flip-left">
                    <div class="plan-header">
                        <h3>Municipalidades</h3>
                        <div class="plan-price">Personalizado</div>
                    </div>
                    <div class="plan-features">
                        <div class="plan-feature">
                            <i class="fas fa-check"></i>
                            <span>Gestión completa de adultos mayores</span>
                        </div>
                        <div class="plan-feature">
                            <i class="fas fa-check"></i>
                            <span>Reportes estadísticos por distrito</span>
                        </div>
                        <div class="plan-feature">
                            <i class="fas fa-check"></i>
                            <span>Integración con programas sociales</span>
                        </div>
                        <div class="plan-feature">
                            <i class="fas fa-check"></i>
                            <span>Soporte técnico dedicado</span>
                        </div>
                    </div>
                    <a href="{{ route('contact') }}" class="btn btn-primary btn-block">Solicitar Demo</a>
                </div>
                
                <div class="plan-card featured" data-aos="flip-left" data-aos-delay="100">
                    <div class="plan-badge">Más Popular</div>
                    <div class="plan-header">
                        <h3>ONGs & Organizaciones</h3>
                        <div class="plan-price">Gratuito</div>
                    </div>
                    <div class="plan-features">
                        <div class="plan-feature">
                            <i class="fas fa-check"></i>
                            <span>Acceso completo a AyniConnect</span>
                        </div>
                        <div class="plan-feature">
                            <i class="fas fa-check"></i>
                            <span>Gestión de voluntarios ilimitada</span>
                        </div>
                        <div class="plan-feature">
                            <i class="fas fa-check"></i>
                            <span>Dashboard de impacto social</span>
                        </div>
                        <div class="plan-feature">
                            <i class="fas fa-check"></i>
                            <span>Soporte comunitario</span>
                        </div>
                    </div>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-block">Registrarse Gratis</a>
                </div>
                
                <div class="plan-card" data-aos="flip-left" data-aos-delay="200">
                    <div class="plan-header">
                        <h3>Voluntarios Individuales</h3>
                        <div class="plan-price">Gratuito</div>
                    </div>
                    <div class="plan-features">
                        <div class="plan-feature">
                            <i class="fas fa-check"></i>
                            <span>Acceso a la app móvil</span>
                        </div>
                        <div class="plan-feature">
                            <i class="fas fa-check"></i>
                            <span>Asignación de visitas cercanas</span>
                        </div>
                        <div class="plan-feature">
                            <i class="fas fa-check"></i>
                            <span>Sistema de recompensas</span>
                        </div>
                        <div class="plan-feature">
                            <i class="fas fa-check"></i>
                            <span>Certificados de voluntariado</span>
                        </div>
                    </div>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-block">Ser Voluntario</a>
                </div>
            </div>
        </div>
    </section>

    @include('footer')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // ===============================================
        // 1. ANIMACIÓN DE TEXTO HERO (¡ESTO FALTABA!)
        // ===============================================
        const heroObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-visible');
                }
            });
        }, { threshold: 0.1 });

        // Observamos el título y la descripción
        document.querySelectorAll('.animate-fade-in, .animate-slide-up').forEach(el => {
            heroObserver.observe(el);
        });

        // ===============================================
        // 2. ANIMACIÓN DE NÚMEROS (STATS)
        // ===============================================
        const stats = document.querySelectorAll('.impact-stat h3');
        
        const statsObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    stats.forEach(stat => {
                        const target = parseInt(stat.getAttribute('data-target'));
                        animateNumber(stat, target);
                    });
                }
            });
        });
        
        const impactSection = document.querySelector('.impact-stats');
        if(impactSection) {
            statsObserver.observe(impactSection);
        }
        
        function animateNumber(element, target) {
            let current = 0;
            const increment = target / 100;
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    element.textContent = target + (target === 98 ? '%' : '+');
                    clearInterval(timer);
                } else {
                    element.textContent = Math.floor(current) + (target === 98 ? '%' : '+');
                }
            }, 20);
        }
    });
    </script>

    <style>
    .page-hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 150px 0 100px;
        position: relative;
    }

    .service-hero {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 60px;
        align-items: center;
        margin: 80px 0;
    }

    .service-hero.reverse {
        grid-template-columns: 1fr 1fr;
        direction: rtl;
    }

    .service-hero.reverse > * {
        direction: ltr;
    }

    .service-tagline {
        color: var(--primary-color);
        font-weight: 600;
        font-size: 1.2rem;
        margin-bottom: 15px;
    }

    .service-features {
        margin-top: 30px;
    }

    .feature-item {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
        gap: 10px;
    }

    .feature-item i {
        color: var(--success-color);
    }

    .service-image {
        max-width: 100%;
        border-radius: 15px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    }

    .features-grid-detailed {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 40px;
        margin-top: 60px;
    }

    .feature-detailed {
        text-align: center;
        padding: 40px 30px;
        background: white;
        border-radius: 15px;
        box-shadow: var(--shadow);
        transition: var(--transition);
    }

    .feature-detailed:hover {
        transform: translateY(-10px);
    }

    .feature-icon-detailed {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 25px;
    }

    .feature-icon-detailed i {
        font-size: 2rem;
        color: white;
    }

    .plans-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
        margin-top: 60px;
    }

    .plan-card {
        background: white;
        border-radius: 15px;
        padding: 40px 30px;
        box-shadow: var(--shadow);
        position: relative;
        transition: var(--transition);
        border: 2px solid transparent;
    }

    .plan-card:hover {
        transform: translateY(-10px);
        border-color: var(--primary-color);
    }

    .plan-card.featured {
        border-color: var(--primary-color);
        transform: scale(1.05);
    }

    .plan-badge {
        position: absolute;
        top: -10px;
        left: 50%;
        transform: translateX(-50%);
        background: var(--primary-color);
        color: white;
        padding: 5px 20px;
        border-radius: 25px;
        font-size: 0.9rem;
        font-weight: 600;
    }

    .plan-header {
        text-align: center;
        margin-bottom: 30px;
        border-bottom: 1px solid #eee;
        padding-bottom: 20px;
    }

    .plan-price {
        font-size: 2.5rem;
        font-weight: bold;
        color: var(--primary-color);
        margin-top: 10px;
    }

    .plan-features {
        margin-bottom: 30px;
    }

    .plan-feature {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
        gap: 10px;
    }

    .plan-feature i {
        color: var(--success-color);
    }

    .btn-block {
        display: block;
        width: 100%;
        text-align: center;
    }

    @media (max-width: 768px) {
        .service-hero {
            grid-template-columns: 1fr;
            text-align: center;
        }
        
        .service-hero.reverse {
            grid-template-columns: 1fr;
            direction: ltr;
        }
        
        .plan-card.featured {
            transform: none;
        }
    }
    </style>
</body>
</html>