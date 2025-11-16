// wasiqhari/assets/js/main.js

// Navegación móvil
const navToggle = document.getElementById('navToggle');
const navMenu = document.getElementById('navMenu');

navToggle?.addEventListener('click', () => {
    navMenu.classList.toggle('active');
    navToggle.classList.toggle('active');
});

// Cerrar menú al hacer clic en un enlace
document.querySelectorAll('.nav-link').forEach(link => {
    link.addEventListener('click', () => {
        navMenu.classList.remove('active');
        navToggle.classList.remove('active');
    });
});

// Scroll suave
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Efecto de escritura en el hero
function typeWriter(element, text, speed = 100) {
    let i = 0;
    element.innerHTML = '';
    
    function type() {
        if (i < text.length) {
            element.innerHTML += text.charAt(i);
            i++;
            setTimeout(type, speed);
        }
    }
    type();
}

// Cambiar navbar en scroll
window.addEventListener('scroll', () => {
    const navbar = document.querySelector('.navbar');
    if (window.scrollY > 100) {
        navbar.style.background = 'rgba(255, 255, 255, 0.95)';
        navbar.style.backdropFilter = 'blur(10px)';
    } else {
        navbar.style.background = 'var(--white)';
        navbar.style.backdropFilter = 'none';
    }
});

// Inicialización cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar animaciones
    AOS.init({
        duration: 1000,
        once: true,
        offset: 100
    });
    
    // Tour virtual
    const startTourBtn = document.getElementById('startTour');
    if (startTourBtn) {
        startTourBtn.addEventListener('click', startTour);
    }
});

// Función del tour virtual
function startTour() {
    const driver = new Driver({
        animate: true,
        opacity: 0.75,
        padding: 10,
        allowClose: true,
        overlayClickNext: false,
        doneBtnText: 'Finalizar',
        closeBtnText: 'Cerrar',
        stageBackground: '#ffffff',
        nextBtnText: 'Siguiente',
        prevBtnText: 'Anterior'
    });
    
    driver.defineSteps([
        {
            element: '.hero',
            popover: {
                title: '¡Bienvenido a WasiQhari! 🏠',
                description: 'Conoce nuestra plataforma de apoyo y monitoreo social para adultos mayores',
                position: 'bottom'
            }
        },
        {
            element: '.stats',
            popover: {
                title: 'Impacto Real 📊',
                description: 'Estadísticas que demuestran la importancia de nuestro trabajo',
                position: 'top'
            }
        },
        {
            element: '.features',
            popover: {
                title: 'Nuestros Servicios 💫',
                description: 'Descubre todas las funcionalidades que ofrecemos',
                position: 'top'
            }
        },
        {
            element: '.nav-menu',
            popover: {
                title: 'Navegación 🧭',
                description: 'Accede a todas las secciones de nuestra plataforma',
                position: 'bottom'
            }
        },
        {
            element: '.cta',
            popover: {
                title: '¡Únete! 🤝',
                description: 'Forma parte de nuestra comunidad solidaria',
                position: 'top'
            }
        }
    ]);
    
    driver.start();
}

// Contador animado para estadísticas
function animateCounter(element, target, duration = 2000) {
    let start = 0;
    const increment = target / (duration / 16);
    const timer = setInterval(() => {
        start += increment;
        if (start >= target) {
            element.textContent = target.toLocaleString();
            clearInterval(timer);
        } else {
            element.textContent = Math.floor(start).toLocaleString();
        }
    }, 16);
}

// Observar elementos para animaciones
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            if (entry.target.classList.contains('stats')) {
                // Animar contadores
                document.querySelectorAll('.stat-number').forEach(stat => {
                    const target = parseInt(stat.textContent.replace(/[^0-9]/g, ''));
                    animateCounter(stat, target);
                });
            }
            entry.target.classList.add('animate-visible');
        }
    });
}, observerOptions);

// Observar elementos que necesitan animación
document.querySelectorAll('.animate-fade-in, .animate-slide-up, .stats').forEach(el => {
    observer.observe(el);
});