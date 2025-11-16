<!-- resources/views/footer.blade.php -->

<footer class="footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-section">
                <div class="footer-logo">
                    <i class="fas fa-heart"></i>
                    <span>WasiQhari</span>
                </div>
                <p class="footer-description">
                    Red de apoyo y monitoreo social que conecta a adultos mayores 
                    con voluntarios y organizaciones solidarias.
                </p>
                <div class="social-links">
                    <a href="#" class="social-link"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-linkedin"></i></a>
                </div>
            </div>
            
            <div class="footer-section">
                <h3>Enlaces Rápidos</h3>
                <ul class="footer-links">
                    <li><a href="{{ route('home') }}"><i class="fas fa-chevron-right"></i> Inicio</a></li>
                    <li><a href="{{ route('about') }}"><i class="fas fa-chevron-right"></i> Nosotros</a></li>
                    <li><a href="{{ route('services') }}"><i class="fas fa-chevron-right"></i> Servicios</a></li>
                    <li><a href="{{ route('contact') }}"><i class="fas fa-chevron-right"></i> Contacto</a></li>
                </ul>
            </div>
            
            <div class="footer-section">
                <h3>Servicios</h3>
                <ul class="footer-links">
                    <li><a href="#"><i class="fas fa-chevron-right"></i> WasiQhari</a></li>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> AyniConnect</a></li>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> Panel de Impacto</a></li>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> Voluntariado</a></li>
                </ul>
            </div>
            
            <div class="footer-section">
                <h3>Contacto</h3>
                <div class="contact-info">
                    <p><i class="fas fa-map-marker-alt"></i> Cusco, Perú</p>
                    <p><i class="fas fa-phone"></i> +51 984 123 456</p>
                    <p><i class="fas fa-envelope"></i> info@wasiqhari.org</p>
                </div>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>&copy; 2025 WasiQhari. Todos los derechos reservados. | Desarrollado con <i class="fas fa-heart"></i> por estudiantes de la Universidad Continental</p>
        </div>
    </div>
</footer>

<!-- Scripts -->
<script src="{{ asset('js/main.js') }}"></script>
<script src="{{ asset('js/prediction-riesgo.js') }}"></script>

@if (session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: '{{ session('success') }}'
            timer: 3000,
            showConfirmButton: false
        });
    </script>
@endif

@if (session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '{{ session('error') }}'
            timer: 3000,
            showConfirmButton: false
        });
    </script>
@endif