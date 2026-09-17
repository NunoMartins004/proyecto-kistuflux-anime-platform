const cajaBusqueda = document.getElementById('cajaBusqueda');
const filtroGenero = document.getElementById('filtroGenero');
const listaAnimes = document.getElementById('listaAnimes');

let misAnimes = [];
let fuse; 

async function cargarDatosDesdeBD() {
    try {
        const respuesta = await fetch('phpConsultas/obtener_directorio.php');
        misAnimes = await respuesta.json();

        // CONFIGURACIÓN POTENTE DE FUSE.JS
        const opciones = {
            keys: ['titulo'], 
            threshold: 0.4,   // Bajamos la exigencia para que perdone errores como "Ona" por "One"
            distance: 100,    // Qué tan lejos puede estar el error
            location: 0,
            ignoreLocation: false
        };

        fuse = new Fuse(misAnimes, opciones);
        dibujarAnimes(misAnimes);
    } catch (error) {
        console.error("Error al cargar datos:", error);
    }
}

function dibujarAnimes(animesParaMostrar) {
    listaAnimes.innerHTML = "";
    animesParaMostrar.forEach(anime => {
        const div = document.createElement('div');
        div.classList.add('tarjeta-anime');
        div.innerHTML = `
            <img src="../${anime.imagen}" alt="${anime.titulo}">
            <div class="tarjeta-info">
                <span class="etiqueta-genero">${anime.genero}</span>
                <h3>${anime.titulo}</h3>
                <p>${anime.descripcion}</p>
                <button onclick="window.location.href='ver.php?id=${anime.id}'">Ver episodios</button>
            </div>
        `;
        listaAnimes.appendChild(div);
    });
}

function filtrar() {
    const texto = cajaBusqueda.value;
    const generoValue = filtroGenero.value;

    let resultados = misAnimes;

    // Si hay texto, usamos la búsqueda "borrosa" de Fuse
    if (texto.trim() !== "") {
        const busquedaFuse = fuse.search(texto);
        resultados = busquedaFuse.map(result => result.item);
    }

    // Filtrar por género sobre los resultados de la búsqueda
    const filtradosFinales = resultados.filter(anime => {
        return (generoValue === "todos" || anime.genero === generoValue);
    });

    dibujarAnimes(filtradosFinales);
}

cajaBusqueda.addEventListener('input', filtrar); // Cambiado a 'input' para que sea más reactivo
filtroGenero.addEventListener('change', filtrar);

cargarDatosDesdeBD();