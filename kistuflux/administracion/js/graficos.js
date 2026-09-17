// Esperamos a que todo el HTML esté cargado y procesado
document.addEventListener('DOMContentLoaded', () => {
    

    // Buscamos el enlace o botón por su ID
    const enlaceAdmin = document.getElementById('abrir-kitsu');

    // Si el elemento existe en esta página, le asignamos el evento
    if (enlaceAdmin) {
        enlaceAdmin.addEventListener('click', (e) => {
            e.preventDefault(); // Evita que el enlace recargue la página o salte
            lanzarApp();
        });
    }
});

// Función lógica para la API
async function lanzarApp() {
    const API_URL = 'http://127.0.0.1:8000/abrir-cliente';
    
    try {
        // Usamos un fetch normal sin esperar una respuesta compleja
        const response = await fetch(API_URL, { 
            method: 'POST',
            mode: 'cors' 
        });

        if (response.ok) {
            console.log("¡KitsuFlux iniciado correctamente!");
        }
    } catch (error) {
        // En lugar de un alert que bloquea la pantalla, lo ponemos en consola
        console.warn("Petición enviada. Si el programa se abrió, ignora este mensaje.");
        
        // Solo mostramos error si realmente sospechamos que la API está muerta
        // Pero como dices que SÍ se abre, podemos omitir el alert molesto.
    }
}