document.addEventListener('DOMContentLoaded', () => {
    const contenedor = document.querySelector('.contenido-dinamico');

    // --- REFERENCIAS AL MODAL ---
    const modalElement = document.getElementById('modalGestion');
    const modalTitulo = document.getElementById('modalTitulo');
    const modalMensaje = document.getElementById('modalMensaje');
    const btnModalAceptar = document.getElementById('btnModalAceptar');
    const btnModalCancelar = document.getElementById('btnModalCancelar');

    // --- FUNCIÓN PARA MOSTRAR EL MODAL (PROMISE) ---
    const mostrarModal = (titulo, mensaje, esConfirmacion = false) => {
        return new Promise((resolve) => {
            modalTitulo.innerText = titulo;
            modalMensaje.innerText = mensaje;
            
            // Si es confirmación (eliminar), mostramos cancelar, si no, lo ocultamos
            btnModalCancelar.style.display = esConfirmacion ? 'inline-block' : 'none';
            
            modalElement.classList.add('active');

            // Configurar clics
            btnModalAceptar.onclick = () => {
                modalElement.classList.remove('active');
                resolve(true);
            };

            btnModalCancelar.onclick = () => {
                modalElement.classList.remove('active');
                resolve(false);
            };
        });
    };

    // --- 1. GESTIÓN DE FORMULARIOS (INSERCIÓN Y EDICIÓN) ---
    const configurarFormulario = (esEdicion) => {
        const form = document.getElementById('formEpisodio');
        const btnCancelar = document.getElementById('btnCancelar');

        // Botón cancelar dentro del formulario (vuelve a la tabla)
        if (btnCancelar) {
            btnCancelar.addEventListener('click', () => window.location.reload());
        }

        if (form) {
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                const formData = new FormData(form);
                
                // Ruta según la acción
                const url = esEdicion 
                    ? 'phpConsultas/procesar_edicion_episodio.php' 
                    : 'phpConsultas/procesar_insercion_episodio.php';

                fetch(url, { method: 'POST', body: formData })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        // Mensaje personalizado según la acción
                        const mensajeExito = esEdicion 
                            ? "El episodio ha sido actualizado correctamente." 
                            : "¡El episodio ha sido insertado con éxito!";

                        mostrarModal("¡Operación exitosa!", mensajeExito)
                            .then(() => window.location.reload());
                    } else {
                        mostrarModal("Error", data.message);
                    }
                })
                .catch(error => {
                    console.error("Error en la petición:", error);
                    mostrarModal("Error", "No se pudo conectar con el servidor.");
                });
            });
        }
    };

    // --- 2. RENDERIZAR TABLA DE EPISODIOS ---
    const renderizarTabla = () => {
        let html = `
            <div class="header-gestion">
                <button id="btnAbrirInsertar" class="btn-insertar">Nuevo Episodio</button>
            </div>`;

        // Validar si hay datos
        if (typeof DATOS_EPISODIOS === 'undefined' || DATOS_EPISODIOS.length === 0) {
            contenedor.innerHTML = html + '<p>No hay episodios registrados.</p>';
            return;
        }

        html += `
            <table class="tabla-gestion">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Imagen</th>
                        <th>Anime</th>
                        <th>Nº</th>
                        <th>Título Episodio</th>
                        <th>URL Video</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>`;

        DATOS_EPISODIOS.forEach(ep => {
            const rutaImg = ep.url_imagen ? `../${ep.url_imagen}` : '../img/placeholder.png';
            const videoShort = ep.url_video ? ep.url_video.substring(0, 20) + '...' : 'Sin link';

            html += `
                <tr>
                    <td>${ep.ID_episodio}</td>
                    <td><img src="${rutaImg}" alt="Episodio" width="50" style="border-radius:4px;"></td>
                    <td>${ep.nombre_anime}</td>
                    <td>${ep.numero_episodio}</td>
                    <td>${ep.titulo}</td>
                    <td>${videoShort}</td>
                    <td>
                        <div class="btn-group">
                            <button class="btn-editar action-edit" data-id="${ep.ID_episodio}">Editar</button>
                            <button class="btn-eliminar action-delete" data-id="${ep.ID_episodio}">Eliminar</button>
                        </div>
                    </td>
                </tr>`;
        });

        html += `</tbody></table>`;
        contenedor.innerHTML = html;

        // Evento botón Insertar
        const btnInsertar = document.getElementById('btnAbrirInsertar');
        if (btnInsertar) {
            btnInsertar.addEventListener('click', () => {
                fetch('phpConsultas/formulario_insertar_episodio.php')
                    .then(res => res.text())
                    .then(formHtml => {
                        contenedor.innerHTML = formHtml;
                        configurarFormulario(false); // Modo Inserción
                    });
            });
        }
    };

    // --- 3. DELEGACIÓN DE EVENTOS (EDITAR Y ELIMINAR) ---
    contenedor.addEventListener('click', (e) => {
        const btnEdit = e.target.closest('.action-edit');
        const btnDelete = e.target.closest('.action-delete');

        // Acción Editar
        if (btnEdit) {
            const id = btnEdit.getAttribute('data-id');
            fetch(`phpConsultas/editar_episodio.php?id=${id}`)
                .then(res => res.text())
                .then(html => {
                    contenedor.innerHTML = html;
                    configurarFormulario(true); // Modo Edición
                });
        }

        // Acción Eliminar
        if (btnDelete) {
            const id = btnDelete.getAttribute('data-id');
            
            mostrarModal(
                "Confirmar Eliminación", 
                "¿Estás seguro de que deseas eliminar este episodio? Esta acción no se puede deshacer.", 
                true
            ).then((confirmado) => {
                if (confirmado) {
                    fetch(`phpConsultas/eliminar_episodio.php?id=${id}`)
                        .then(res => res.json())
                        .then(data => {
                            if (data.status === 'success') {
                                mostrarModal("Eliminado", "El episodio ha sido borrado correctamente.")
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

    // Iniciar
    renderizarTabla();
});