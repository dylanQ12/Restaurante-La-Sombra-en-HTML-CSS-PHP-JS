const urlBase = "<?php echo $url_base; ?>"; // Define la variable aquí

// Asegúrate de que este código se ejecute después de que el DOM esté completamente cargado
document.addEventListener('DOMContentLoaded', (event) => {
    const currentPath = window.location.pathname;

    document.querySelectorAll('.navbar-primary .nav-link').forEach(link => {
        link.classList.remove('active');
        const targetPath = new URL(link.href).pathname;
        // Si el enlace corresponde a la ruta actual, agrégale la clase 'active'.
        if (currentPath.startsWith(targetPath)) {
            link.classList.add('active');
        }
    });
});

