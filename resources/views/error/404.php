<section class="error-section">
    <div class="error-container">
        <div class="error-content" data-aos="zoom-in">
            <div class="error-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h1>404</h1>
            <h2>Página No Encontrada</h2>
            <p>Lo sentimos, la página que estás buscando no existe o ha sido movida.</p>
            
            <div class="error-actions">
                <a href="index.php?c=home&a=index" class="btn btn-primary">
                    <i class="fas fa-home"></i> Volver al Inicio
                </a>
                <a href="javascript:history.back()" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Regresar
                </a>
            </div>
            
            <div class="error-search">
                <p>¿O quizás prefieres buscar lo que necesitas?</p>
                <div class="search-box">
                    <input type="text" placeholder="Buscar en WasiQhari...">
                    <button class="btn btn-primary">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <div class="error-graphic" data-aos="fade-left">
            <img src="https://images.vexels.com/media/users/3/153988/isolated/preview/948fa94b504782eef35dbfb511443868-icono-de-trazo-de-color-de-mantenimiento-del-sitio-web.png" alt="Error 404" class="error-image">
        </div>
    </div>
    
    <div class="error-background">
        <div class="floating-elements">
            <div class="floating-element el1"><i class="fas fa-heart"></i></div>
            <div class="floating-element el2"><i class="fas fa-hands-helping"></i></div>
            <div class="floating-element el3"><i class="fas fa-home"></i></div>
            <div class="floating-element el4"><i class="fas fa-users"></i></div>
            <div class="floating-element el5"><i class="fas fa-hand-holding-heart"></i></div>
        </div>
    </div>
</section>

<style>
.error-section {
    min-height: 100vh;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 100px 20px 50px;
    position: relative;
    overflow: hidden;
}

.error-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    max-width: 1000px;
    width: 100%;
    align-items: center;
    gap: 60px;
    z-index: 2;
    position: relative;
}

.error-content {
    text-align: center;
    color: white;
}

.error-icon {
    font-size: 4rem;
    margin-bottom: 30px;
    opacity: 0.8;
}

.error-content h1 {
    font-size: 8rem;
    font-weight: bold;
    margin-bottom: 10px;
    line-height: 1;
    text-shadow: 0 10px 30px rgba(0,0,0,0.3);
}

.error-content h2 {
    font-size: 2.5rem;
    margin-bottom: 20px;
    font-weight: 600;
}

.error-content p {
    font-size: 1.2rem;
    margin-bottom: 40px;
    opacity: 0.9;
    line-height: 1.6;
    max-width: 400px;
    margin-left: auto;
    margin-right: auto;
}

.error-actions {
    display: flex;
    gap: 15px;
    justify-content: center;
    margin-bottom: 40px;
    flex-wrap: wrap;
}

.error-search {
    background: rgba(255,255,255,0.1);
    padding: 30px;
    border-radius: 15px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.2);
}

.error-search p {
    margin-bottom: 20px !important;
    font-size: 1.1rem;
}

.search-box {
    display: flex;
    max-width: 400px;
    margin: 0 auto;
}

.search-box input {
    flex: 1;
    padding: 12px 20px;
    border: none;
    border-radius: 50px 0 0 50px;
    font-size: 1rem;
    outline: none;
}

.search-box button {
    border-radius: 0 50px 50px 0;
    padding: 12px 25px;
}

.error-graphic {
    text-align: center;
}

.error-image {
    max-width: 100%;
    animation: float 3s ease-in-out infinite;
}

.error-background {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1;
}

.floating-elements {
    position: relative;
    width: 100%;
    height: 100%;
}

.floating-element {
    position: absolute;
    color: rgba(255,255,255,0.1);
    font-size: 2rem;
    animation: float-random 10s ease-in-out infinite;
}

.el1 { top: 10%; left: 10%; animation-delay: 0s; }
.el2 { top: 20%; right: 15%; animation-delay: 2s; }
.el3 { bottom: 30%; left: 20%; animation-delay: 4s; }
.el4 { bottom: 20%; right: 25%; animation-delay: 6s; }
.el5 { top: 50%; left: 50%; animation-delay: 8s; }

@keyframes float-random {
    0%, 100% { 
        transform: translate(0, 0) rotate(0deg); 
    }
    25% { 
        transform: translate(20px, -20px) rotate(5deg); 
    }
    50% { 
        transform: translate(-15px, 15px) rotate(-5deg); 
    }
    75% { 
        transform: translate(10px, -10px) rotate(3deg); 
    }
}

@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-20px); }
}

@media (max-width: 768px) {
    .error-container {
        grid-template-columns: 1fr;
        text-align: center;
        gap: 40px;
    }
    
    .error-content h1 {
        font-size: 6rem;
    }
    
    .error-content h2 {
        font-size: 2rem;
    }
    
    .error-actions {
        flex-direction: column;
        align-items: center;
    }
    
    .error-actions .btn {
        width: 200px;
    }
    
    .floating-element {
        display: none;
    }
}
</style>