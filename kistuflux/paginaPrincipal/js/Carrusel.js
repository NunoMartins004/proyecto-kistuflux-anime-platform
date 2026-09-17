// Función para cargar los animes desde la base de datos
async function cargarAnimes() {

    try {
        const respuesta = await fetch('phpConsultas/carrusel.php');
        const datos = await respuesta.json();

        // 1. dibujamos los animes en el HTML
        renderizarCarrusel(datos);

        

        // 2.  AHORA inicializamos Swiper
        const swiper = new Swiper(".mySwiper", {
            loop: true,
             // Permite que sea infinito
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            //Autoplay para ver si se mueve solo
            autoplay: {
                delay: 3000,
            },
        });
    } catch (error) {
        console.error("Error al cargar el carrusel:", error);
    }

}

function renderizarCarrusel(listaAnimes) {
    const contenedor = document.querySelector('.swiper-wrapper');
    contenedor.innerHTML = ""; // Solo para limpiar el contenedor al inicio

    listaAnimes.forEach(anime => {
        // 1. Crear el slide principal
        const slide = document.createElement('div');
        slide.classList.add('swiper-slide');

        // 2. Crear la imagen
        const img = document.createElement('img');
        img.src = "../" + anime.url_imagen; 
        img.alt = anime.titulo;

        // 3. Crear el contenedor de información
        const info = document.createElement('div');
        info.classList.add('info');

        // 4. Crear título y descripción
        const h3 = document.createElement('h3');
        h3.textContent = anime.titulo;

        const p = document.createElement('p');
        p.textContent = anime.descripcion;

        // 5. Ensamblar las piezas
        info.appendChild(h3);
        info.appendChild(p);

        slide.appendChild(img);
        slide.appendChild(info);

        // 6. Meter el slide completo al contenedor del carrusel
        contenedor.appendChild(slide);
    });
}

// Ejecutamos la función


document.addEventListener("DOMContentLoaded", () => {
    cargarAnimes();
});
