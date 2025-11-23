<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'WasiQhari - Contacto' }}</title>
    
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
                <h1 class="hero-title animate-fade-in">Queremos <span class="highlight">Escucharte</span></h1>
                <p class="hero-description animate-slide-up">
                    ¿Tienes preguntas? Estamos aquí para ayudarte. Juntos construyamos una comunidad más solidaria.
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

    <!-- Contact Section -->
    <section class="contact-section">
        <div class="container">
            <div class="contact-grid">
                <!-- Información de Contacto -->
                <div class="contact-info" data-aos="fade-right">
                    <h2>Hablemos</h2>
                    <p class="contact-description">
                        Estamos comprometidos con brindarte la mejor atención. No dudes en contactarnos 
                        para cualquier consulta sobre nuestros servicios, voluntariado o colaboraciones.
                    </p>
                    
                    <div class="contact-methods">
                        <div class="contact-method">
                            <div class="method-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="method-info">
                                <h4>Ubicación</h4>
                                <p>Av. de la Cultura 123<br>Cusco, Perú</p>
                            </div>
                        </div>
                        
                        <div class="contact-method">
                            <div class="method-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="method-info">
                                <h4>Teléfono</h4>
                                <p>+51 984 123 456<br>Lun - Vie: 9:00 - 18:00</p>
                            </div>
                        </div>
                        
                        <div class="contact-method">
                            <div class="method-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="method-info">
                                <h4>Email</h4>
                                <p>info@wasiqhari.org<br>voluntarios@wasiqhari.org</p>
                            </div>
                        </div>
                        
                        <div class="contact-method">
                            <div class="method-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="method-info">
                                <h4>Horario de Atención</h4>
                                <p>Lunes a Viernes: 9:00 - 18:00<br>Sábados: 9:00 - 13:00</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="social-contact">
                        <h4>Síguenos en:</h4>
                        <div class="social-links-contact">
                            <a href="#" class="social-link-contact">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="social-link-contact">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="social-link-contact">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" class="social-link-contact">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                            <a href="#" class="social-link-contact">
                                <i class="fab fa-youtube"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Formulario de Contacto -->
                <div class="contact-form-container" data-aos="fade-left">
                    <form class="contact-form" id="contactForm" action="{{ route('contact.submit') }}" method="POST">
                        @csrf
                        <h3>Envíanos un Mensaje</h3>
                        
                        <div class="form-group">
                            <label for="name">Nombre Completo *</label>
                            <input type="text" id="name" name="name" required>
                            <i class="fas fa-user form-icon"></i>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email *</label>
                            <input type="email" id="email" name="email" required>
                            <i class="fas fa-envelope form-icon"></i>
                        </div>
                        
                        <div class="form-group">
                            <label for="phone">Teléfono</label>
                            <input type="tel" id="phone" name="phone">
                            <i class="fas fa-phone form-icon"></i>
                        </div>
                        
                        <div class="form-group">
                            <label for="subject">Asunto *</label>
                            <select id="subject" name="subject" required>
                                <option value="">Selecciona un asunto</option>
                                <option value="volunteer">Quiero ser voluntario</option>
                                <option value="info">Solicitar información</option>
                                <option value="partnership">Colaboración institucional</option>
                                <option value="support">Soporte técnico</option>
                                <option value="other">Otro</option>
                            </select>
                            <i class="fas fa-tag form-icon"></i>
                        </div>
                        
                        <div class="form-group">
                            <label for="message">Mensaje *</label>
                            <textarea id="message" name="message" rows="5" required placeholder="Cuéntanos cómo podemos ayudarte..."></textarea>
                            <i class="fas fa-comment form-icon"></i>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-full">
                            <i class="fas fa-paper-plane"></i> Enviar Mensaje
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq-section">
        <div class="container">
            <h2 class="section-title">Preguntas Frecuentes</h2>
            <p class="section-subtitle">Encuentra respuestas a las dudas más comunes</p>
            
            <div class="faq-grid">
                <div class="faq-item" data-aos="fade-up">
                    <div class="faq-question">
                        <h4>¿Cómo puedo ser voluntario en WasiQhari?</h4>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Es muy sencillo! Solo necesitas registrarte en nuestra plataforma, completar tu perfil de voluntario y asistir a una breve sesión de orientación. No se requiere experiencia previa, solo muchas ganas de ayudar.</p>
                    </div>
                </div>
                
                <div class="faq-item" data-aos="fade-up" data-aos-delay="100">
                    <div class="faq-question">
                        <h4>¿Qué tipo de apoyo brindan a los adultos mayores?</h4>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Brindamos acompañamiento emocional, visitas domiciliarias, ayuda en trámites, conexión con servicios de salud, y sobre todo, combatimos la soledad mediante una red de apoyo constante y comprometida.</p>
                    </div>
                </div>
                
                <div class="faq-item" data-aos="fade-up" data-aos-delay="200">
                    <div class="faq-question">
                        <h4>¿Es seguro compartir información personal en la plataforma?</h4>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Absolutamente. Utilizamos encriptación de última generación y cumplimos con todas las normativas de protección de datos. La información sensible solo es accesible para personal autorizado y voluntarios verificados.</p>
                    </div>
                </div>
                
                <div class="faq-item" data-aos="fade-up" data-aos-delay="300">
                    <div class="faq-question">
                        <h4>¿Pueden las organizaciones usar WasiQhari?</h4>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Sí! Ofrecemos planes especiales para ONGs, municipalidades y otras organizaciones. Contáctanos para una demostración personalizada y conocer cómo podemos adaptar la plataforma a tus necesidades.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Mapa -->
    <section class="map-section">
        <div class="container">
            <h2 class="section-title">Encuéntranos</h2>
            <div class="map-container" data-aos="zoom-in">
                <div class="map-placeholder">
                    <i class="fas fa-map-marked-alt"></i>
                    <h3>Mapa de Ubicación</h3>
                    <p>Av. de la Cultura 123, Cusco, Perú</p>
                    <div class="map-actions">
                        <a href="#" class="btn btn-secondary">
                            <i class="fas fa-directions"></i> Cómo Llegar
                        </a>
                    </div>
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

    <script>
    // FAQ Accordion
    document.querySelectorAll('.faq-question').forEach(question => {
        question.addEventListener('click', () => {
            const faqItem = question.parentElement;
            const answer = faqItem.querySelector('.faq-answer');
            const icon = question.querySelector('i');
            
            // Cerrar otros FAQs abiertos
            document.querySelectorAll('.faq-item').forEach(item => {
                if (item !== faqItem) {
                    item.classList.remove('active');
                    item.querySelector('.faq-answer').style.maxHeight = null;
                    item.querySelector('i').classList.remove('fa-chevron-up');
                    item.querySelector('i').classList.add('fa-chevron-down');
                }
            });
            
            // Toggle current FAQ
            faqItem.classList.toggle('active');
            if (faqItem.classList.contains('active')) {
                answer.style.maxHeight = answer.scrollHeight + 'px';
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-up');
            } else {
                answer.style.maxHeight = null;
                icon.classList.remove('fa-chevron-up');
                icon.classList.add('fa-chevron-down');
            }
        });
    });

    // Form Submission
    document.getElementById('contactForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Simular envío del formulario
        Swal.fire({
            title: '¡Mensaje Enviado!',
            text: 'Hemos recibido tu mensaje. Te contactaremos dentro de 24 horas.',
            icon: 'success',
            confirmButtonText: 'Entendido'
        });
        
        // Limpiar formulario
        this.reset();
    });
    </script>

    <style>
    .contact-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 60px;
        margin: 80px 0;
    }

    .contact-info h2 {
        color: var(--dark-color);
        margin-bottom: 20px;
        font-size: 2.5rem;
    }

    .contact-description {
        color: var(--text-light);
        margin-bottom: 40px;
        font-size: 1.1rem;
        line-height: 1.6;
    }

    .contact-methods {
        margin-bottom: 40px;
    }

    .contact-method {
        display: flex;
        align-items: flex-start;
        margin-bottom: 30px;
        gap: 20px;
    }

    .method-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .method-icon i {
        font-size: 1.5rem;
        color: white;
    }

    .method-info h4 {
        color: var(--dark-color);
        margin-bottom: 5px;
        font-size: 1.2rem;
    }

    .method-info p {
        color: var(--text-light);
        line-height: 1.5;
    }

    .social-contact h4 {
        margin-bottom: 15px;
        color: var(--dark-color);
    }

    .social-links-contact {
        display: flex;
        gap: 15px;
    }

    .social-link-contact {
        width: 45px;
        height: 45px;
        background: var(--light-color);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--dark-color);
        text-decoration: none;
        transition: var(--transition);
    }

    .social-link-contact:hover {
        background: var(--primary-color);
        color: white;
        transform: translateY(-3px);
    }

    /* Form Styles */
    .contact-form-container {
        background: white;
        padding: 40px;
        border-radius: 15px;
        box-shadow: var(--shadow);
    }

    .contact-form h3 {
        color: var(--dark-color);
        margin-bottom: 30px;
        text-align: center;
        font-size: 1.8rem;
    }

    .form-group {
        position: relative;
        margin-bottom: 25px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        color: var(--dark-color);
        font-weight: 500;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 12px 45px 12px 15px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 1rem;
        transition: var(--transition);
        background: white;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(231, 76, 60, 0.1);
    }

    .form-icon {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-light);
    }

    .form-group textarea + .form-icon {
        top: 25px;
        transform: none;
    }

    .btn-full {
        width: 100%;
        justify-content: center;
    }

    /* FAQ Styles */
    .faq-section {
        background: var(--light-color);
        padding: 80px 0;
    }

    .faq-grid {
        max-width: 800px;
        margin: 0 auto;
    }

    .faq-item {
        background: white;
        border-radius: 10px;
        margin-bottom: 15px;
        box-shadow: var(--shadow);
        overflow: hidden;
    }

    .faq-question {
        padding: 20px 25px;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: var(--transition);
    }

    .faq-question:hover {
        background: #f8f9fa;
    }

    .faq-question h4 {
        color: var(--dark-color);
        margin: 0;
        font-size: 1.1rem;
    }

    .faq-question i {
        color: var(--primary-color);
        transition: var(--transition);
    }

    .faq-answer {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease;
    }

    .faq-answer p {
        padding: 0 25px 20px;
        color: var(--text-light);
        margin: 0;
        line-height: 1.6;
    }

    .faq-item.active .faq-question {
        background: var(--primary-color);
    }

    .faq-item.active .faq-question h4 {
        color: white;
    }

    .faq-item.active .faq-question i {
        color: white;
    }

    /* Map Styles */
    .map-section {
        padding: 80px 0;
    }

    .map-container {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        padding: 60px 40px;
        text-align: center;
        color: white;
    }

    .map-placeholder i {
        font-size: 4rem;
        margin-bottom: 20px;
        opacity: 0.8;
    }

    .map-placeholder h3 {
        margin-bottom: 10px;
        font-size: 1.8rem;
    }

    .map-placeholder p {
        margin-bottom: 30px;
        opacity: 0.9;
        font-size: 1.1rem;
    }

    @media (max-width: 768px) {
        .contact-grid {
            grid-template-columns: 1fr;
            gap: 40px;
        }
        
        .contact-form-container {
            padding: 30px 20px;
        }
        
        .contact-method {
            flex-direction: column;
            text-align: center;
            gap: 15px;
        }
        
        .method-icon {
            align-self: center;
        }
    }
    </style>
</body>
</html>