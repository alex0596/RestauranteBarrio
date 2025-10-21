// Función para cambiar el tema
function cambiarTema() {
    const tema = document.getElementById('selector-tema').value;
    document.body.className = tema;
    
    // Efecto de transición suave
    document.body.style.transform = 'scale(0.95)';
    setTimeout(() => {
        document.body.style.transform = 'scale(1)';
    }, 150);
}

// Escuchar el evento de cambio en el selector de tema
document.getElementById('selector-tema').addEventListener('change', cambiarTema);

// Animaciones al cargar
document.addEventListener('DOMContentLoaded', function() {
    const elements = document.querySelectorAll('.fade-in');
    elements.forEach((el, index) => {
        el.style.animationDelay = `${index * 0.2}s`;
        el.style.animationName = 'fadeInUp';
    });
});

// Efecto paralaje suave en el scroll
window.addEventListener('scroll', () => {
    const scrolled = window.pageYOffset;
    const parallax = document.querySelector('.floating-elements');
    const speed = scrolled * 0.5;
    
    if (parallax) {
        parallax.style.transform = `translateY(${speed}px)`;
    }
});