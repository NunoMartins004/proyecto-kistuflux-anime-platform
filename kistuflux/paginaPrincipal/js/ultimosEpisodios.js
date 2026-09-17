document.addEventListener("DOMContentLoaded", () => {
    cargarEpisodios();
});

function cargarEpisodios() {

    fetch("phpConsultas/obtener_episodios.php")
        .then((respuesta) => {

            if (respuesta.ok && respuesta.status == 200) {

                return respuesta.json();
            }
            else {
                throw new Error("Error al conectarse con la API de consulta de los episodios")
            }
        })
        .then((miJSON) => {

            if (miJSON && Array.isArray(miJSON)) {
               

                const contenedor = document.getElementById('contenedor-episodios');
                contenedor.innerHTML = ''; // Limpiamos el contenedor antes de llenar

                miJSON.forEach(ep => {
                    // Creamos la tarjeta principal
                    const tarjeta = document.createElement("a");
                    tarjeta.classList.add("card-episodio");

                    // Configuramos el destino del enlace
                   tarjeta.href = `reproductor.php?v=${encodeURIComponent(ep.url_video)}&t=${encodeURIComponent(ep.nombre_anime)}&e=${ep.numero_episodio}`;


                    const posterContainer = document.createElement('div');
                    posterContainer.classList.add('poster-container');

                    const img = document.createElement('img');
                    img.src = `../${ep.url_imagen}`;
                    img.alt = ep.nombre_anime;

                    const badge = document.createElement('span');
                    badge.classList.add('badge-episodio');
                    badge.textContent = `EP ${ep.numero_episodio}`;

                   
                    posterContainer.appendChild(img);
                    posterContainer.appendChild(badge);
                

                    const infoEpisodio = document.createElement('div');
                    infoEpisodio.classList.add('info-episodio');

                    const h4 = document.createElement('h4');
                    h4.classList.add('anime-titulo');
                    h4.textContent = ep.nombre_anime;

                    const p = document.createElement('p');
                    p.classList.add('episodio-titulo');
                    p.textContent = ep.nombre_episodio;

                    infoEpisodio.appendChild(h4);
                    infoEpisodio.appendChild(p);

                    tarjeta.appendChild(posterContainer);
                    tarjeta.appendChild(infoEpisodio);

                    contenedor.appendChild(tarjeta);
                });

            } else {
                throw new Error("Error: Los datos no son un array válido o están vacíos.");
            }
        })
        .catch((error) => {

            console.error(error.message);
        })
}

function reproducir(evento) {

    window.location.href = `ver_episodio.php?video=${ep.url_video}`;
}