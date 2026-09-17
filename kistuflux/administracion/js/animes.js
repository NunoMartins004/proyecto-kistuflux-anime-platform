document.addEventListener('DOMContentLoaded', () => {
    const contenedor = document.querySelector('.contenido-dinamico');

    // --- REFERENCIAS AL MODAL ---
    const modal = document.getElementById('modalGestion');
    const modalTitulo = document.getElementById('modalTitulo');
    const modalMensaje = document.getElementById('modalMensaje');
    const btnModalAceptar = document.getElementById('btnModalAceptar');
    const btnModalCancelar = document.getElementById('btnModalCancelar');

    // --- FUNCIÓN PARA MOSTRAR EL MODAL ---
 const mostrarModal = (titulo, mensaje, esConfirmacion = false) => {
    // Buscamos el contenedor por el ID 'modalGestion'
    const modalElement = document.getElementById('modalGestion'); 
    
    return new Promise((resolve) => {
        document.getElementById('modalTitulo').innerText = titulo;
        document.getElementById('modalMensaje').innerText = mensaje;
        
        const btnCan = document.getElementById('btnModalCancelar');
        btnCan.style.display = esConfirmacion ? 'inline-block' : 'none';
        
        // Añadimos la clase para que el CSS lo muestre como flex
        modalElement.classList.add('active');

        document.getElementById('btnModalAceptar').onclick = () => {
            modalElement.classList.remove('active');
            resolve(true);
        };

        btnCan.onclick = () => {
            modalElement.classList.remove('active');
            resolve(false);
        };
    });
};

    // --- 1. FUNCIÓN PARA GESTIONAR FORMULARIOS (EDICIÓN E INSERCIÓN) ---
    const configurarFormulario = (esEdicion) => {
        const form = document.getElementById('formAnime');
        const btnCancelar = document.getElementById('btnCancelar');

        if (btnCancelar) {
            btnCancelar.addEventListener('click', () => window.location.reload());
        }

        if (form) {
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                const formData = new FormData(form);
                const url = esEdicion ? 'phpConsultas/procesar_edicion.php' : 'phpConsultas/procesar_insercion.php';

                fetch(url, { method: 'POST', body: formData })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'success') {
                            // SUSTITUIMOS EL ALERT POR MOSTRARMODAL
                            mostrarModal("¡Operación exitosa!", "Los cambios se han guardado correctamente.")
                                .then(() => window.location.reload());
                        } else {
                            mostrarModal("Error", data.message);
                        }
                    })
                    .catch(error => console.error("Error:", error));
            });
        }
    };

    // --- 2. RENDERIZAR TABLA CON BOTÓN INSERTAR ---
    const renderizarTabla = () => {
        let html = `
            <div class="header-gestion">
                <button id="btnAbrirInsertar" class="btn-insertar">Insertar Nuevo Anime</button>
            </div>`;

        if (typeof DATOS_ANIMES === 'undefined' || DATOS_ANIMES.length === 0) {
            contenedor.innerHTML = html + '<p>No hay datos.</p>';
            return;
        }

        html += `
            <table class="tabla-gestion">
                <thead>
                    <tr>
                        <th>ID</th><th>Imagen</th><th>Tipo</th><th>Título</th><th>Acciones</th>
                    </tr>
                </thead>
                <tbody>`;

        DATOS_ANIMES.forEach(anime => {
            const rutaImg = anime.url_imagen ? `../${anime.url_imagen}` : '../img/placeholder.png';
            html += `
                <tr>
                    <td>${anime.ID_anime}</td>
                    <td><img src="${rutaImg}" width="50"></td>
                    <td>${parseInt(anime.es_banner) === 1 ? 'BANNER' : 'PORTADA'}</td>
                    <td>${anime.titulo}</td>
                    <td>
                        <button class=" btn-editar action-edit" data-id="${anime.ID_anime}">Editar</button>
                        <button class="action-delete" data-id="${anime.ID_anime}">Eliminar</button>
                    </td>
                </tr>`;
        });

        html += `</tbody></table>`;
        contenedor.innerHTML = html;

        document.getElementById('btnAbrirInsertar').addEventListener('click', () => {
            fetch('phpConsultas/formulario_insertar.php')
                .then(res => res.text())
                .then(formHtml => {
                    contenedor.innerHTML = formHtml;
                    configurarFormulario(false);
                });
        });
    };

    // --- 3. DELEGACIÓN PARA EDITAR Y ELIMINAR ---
    contenedor.addEventListener('click', (e) => {
        const btnEdit = e.target.closest('.action-edit');
        const btnDelete = e.target.closest('.action-delete');

        if (btnEdit) {
            const id = btnEdit.getAttribute('data-id');
            fetch(`phpConsultas/editar_anime.php?id=${id}`)
                .then(res => res.text())
                .then(html => {
                    contenedor.innerHTML = html;
                    configurarFormulario(true);
                });
        }

        // Lógica para Eliminar (Sustituimos confirm por mostrarModal)
        if (btnDelete) {
            const id = btnDelete.getAttribute('data-id');
            
            // Llamamos al modal con el modo confirmación (true)
            mostrarModal("¿Estás seguro?", "¿Deseas eliminar este anime? También se borrará su imagen.", true)
                .then((confirmado) => {
                    if (confirmado) {
                        fetch(`phpConsultas/eliminar_anime.php?id=${id}`)
                            .then(res => res.json())
                            .then(data => {
                                if (data.status === 'success') {
                                    mostrarModal("Eliminado", "Anime eliminado correctamente.")
                                        .then(() => window.location.reload());
                                } else {
                                    mostrarModal("Error", data.message);
                                }
                            })
                            .catch(err => console.error("Error:", err));
                    }
                });
        }
    });

    renderizarTabla();
});