<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'WasiQhari - Sobre Nosotros' }}</title>
    
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
                <h1 class="hero-title animate-fade-in">Sobre <span class="highlight">WasiQhari</span></h1>
                <p class="hero-description animate-slide-up">
                    Conoce nuestra misión, visión y el equipo detrás de esta iniciativa solidaria
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

    <!-- Misión y Visión -->
    <section class="about-section">
        <div class="container">
            <div class="about-grid">
                <div class="about-card" data-aos="fade-right">
                    <div class="about-icon">
                        <i class="fas fa-bullseye"></i>
                    </div>
                    <h3>Nuestra Misión</h3>
                    <p>
                        Conectar a adultos mayores en situación de vulnerabilidad con una red solidaria 
                        de voluntarios y organizaciones, brindando acompañamiento, monitoreo y apoyo 
                        continuo para mejorar su calidad de vida y bienestar integral.
                    </p>
                </div>
                
                <div class="about-card" data-aos="fade-left">
                    <div class="about-icon">
                        <i class="fas fa-eye"></i>
                    </div>
                    <h3>Nuestra Visión</h3>
                    <p>
                        Ser la plataforma líder en Latinoamérica para el cuidado y acompañamiento 
                        de adultos mayores, replicando nuestro modelo en todas las regiones del Perú 
                        y convirtiéndonos en referente de innovación social tecnológica.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Valores -->
    <section class="values-section">
        <div class="container">
            <h2 class="section-title">Nuestros Valores</h2>
            <p class="section-subtitle">Principios que guían nuestro trabajo diario</p>
            
            <div class="values-grid">
                <div class="value-card" data-aos="zoom-in">
                    <div class="value-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h4>Solidaridad</h4>
                    <p>Actuamos con empatía y compromiso hacia quienes más nos necesitan</p>
                </div>
                
                <div class="value-card" data-aos="zoom-in" data-aos-delay="100">
                    <div class="value-icon">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <h4>Reciprocidad</h4>
                    <p>Inspirados en el Ayni andino, creemos en el apoyo mutuo y comunitario</p>
                </div>
                
                <div class="value-card" data-aos="zoom-in" data-aos-delay="200">
                    <div class="value-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h4>Confianza</h4>
                    <p>Garantizamos la seguridad y privacidad de todos nuestros usuarios</p>
                </div>
                
                <div class="value-card" data-aos="zoom-in" data-aos-delay="300">
                    <div class="value-icon">
                        <i class="fas fa-rocket"></i>
                    </div>
                    <h4>Innovación</h4>
                    <p>Utilizamos tecnología de punta para crear soluciones sociales efectivas</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Equipo -->
    <section class="team-section">
        <div class="container">
            <h2 class="section-title">Nuestro Equipo</h2>
            <p class="section-subtitle">Jóvenes profesionales comprometidos con el cambio social</p>
            
            <div class="team-grid">
                <div class="team-card" data-aos="flip-left">
                    <div class="team-image">
                        <img src="https://media.licdn.com/dms/image/v2/D4E03AQEsCx9Rvk1JFg/profile-displayphoto-shrink_200_200/B4EZQ56Bf6GwAY-/0/1736138299749?e=2147483647&v=beta&t=DboA7RV8Nt_guE2jn0yNKGOGwCO3HI1jsyU4QM92XZw" alt="Renzo Costa">
                    </div>
                    <div class="team-info">
                        <h4>Renzo Costa</h4>
                        <p class="team-role">Ing. de Sistemas</p>
                        <p class="team-bio">Especialista en desarrollo backend y arquitectura de software - Especialista en IA y análisis de datos</p>
                        <div class="team-social">
                            <a href="#"><i class="fab fa-linkedin"></i></a>
                            <a href="#"><i class="fab fa-github"></i></a>
                        </div>
                    </div>
                </div>
                
                <div class="team-card" data-aos="flip-left" data-aos-delay="100">
                    <div class="team-image">
                        <img src="https://i.postimg.cc/8cFCTxvW/unnamedvc.webp" alt="Brian Villavicencio">
                    </div>
                    <div class="team-info">
                        <h4>Brian Villavicencio</h4>
                        <p class="team-role">Ing. de Sistemas</p>
                        <p class="team-bio">Experto en bases de datos y seguridad informática</p>
                        <div class="team-social">
                            <a href="#"><i class="fab fa-linkedin"></i></a>
                            <a href="#"><i class="fab fa-github"></i></a>
                        </div>
                    </div>
                </div>
                
                <div class="team-card" data-aos="flip-left" data-aos-delay="200">
                    <div class="team-image">
                        <img src="https://i.postimg.cc/g2h6VLnK/Whats-App-Image-2025-11-22-at-9-38-38-PM.jpg" alt="Mikaela Mendoza">
                    </div>
                    <div class="team-info">
                        <h4>Mikaela Mendoza</h4>
                        <p class="team-role">Ing. de Sistemas</p>
                        <p class="team-bio">Desarrolladora frontend y diseñadora UX/UI</p>
                        <div class="team-social">
                            <a href="#"><i class="fab fa-linkedin"></i></a>
                            <a href="#"><i class="fab fa-github"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats -->
    <section class="impact-stats">
        <div class="container">
            <h2 class="section-title">Nuestro Impacto</h2>
            <div class="stats-grid">
                <div class="impact-stat">
                    <i class="fas fa-users"></i>
                    <h3 data-target="500">0</h3>
                    <p>Adultos Mayores Registrados</p>
                </div>
                <div class="impact-stat">
                    <i class="fas fa-hands-helping"></i>
                    <h3 data-target="150">0</h3>
                    <p>Voluntarios Activos</p>
                </div>
                <div class="impact-stat">
                    <i class="fas fa-map-marker-alt"></i>
                    <h3 data-target="25">0</h3>
                    <p>Comunidades Atendidas</p>
                </div>
                <div class="impact-stat">
                    <i class="fas fa-smile"></i>
                    <h3 data-target="98">0</h3>
                    <p>Satisfacción de Usuarios</p>
                </div>
            </div>
        </div>
    </section>

    @include('footer')

    <script>
    // Animación de números para stats
    document.addEventListener('DOMContentLoaded', function() {
        const stats = document.querySelectorAll('.impact-stat h3');
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    stats.forEach(stat => {
                        const target = parseInt(stat.getAttribute('data-target'));
                        animateNumber(stat, target);
                    });
                }
            });
        });
        
        observer.observe(document.querySelector('.impact-stats'));
        
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
</body>
</html>