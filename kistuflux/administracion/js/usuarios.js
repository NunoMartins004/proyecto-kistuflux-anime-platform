document.addEventListener('DOMContentLoaded', () => {
    const contenedor = document.querySelector('.contenido-dinamico');

    // --- REFERENCIAS AL MODAL ---
    const modalElement = document.getElementById('modalGestion');
    const modalTitulo = document.getElementById('modalTitulo');
    const modalMensaje = document.getElementById('modalMensaje');
    const btnModalAceptar = document.getElementById('btnModalAceptar');
    const btnModalCancelar = document.getElementById('btnModalCancelar');

    // --- FUNCIÓN PARA MOSTRAR EL MODAL ---
    const mostrarModal = (titulo, mensaje, esConfirmacion = false) => {
        return new Promise((resolve) => {
            modalTitulo.innerText = titulo;
            modalMensaje.innerText = mensaje;
            
            btnModalCancelar.style.display = esConfirmacion ? 'inline-block' : 'none';
            modalElement.classList.add('active');

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

    // --- 1. RENDERIZAR TABLA DE USUARIOS ---
    const renderizarTablaUsuarios = () => {
        let html = `
            <div class="header-gestion">
                <h2>Gestión de Usuarios</h2>
            </div>`;

        if (typeof DATOS_USUARIOS === 'undefined' || DATOS_USUARIOS.length === 0) {
            contenedor.innerHTML = html + '<p style="color:white; padding:20px;">No hay usuarios registrados.</p>';
            return;
        }

        html += `
            <table class="tabla-gestion">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Perfil</th>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Rol</th>
                        <th>Puntos</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>`;

        DATOS_USUARIOS.forEach(user => {
            let nombreArchivo = user.foto_perfil.split('/').pop(); 
            const fotoURL = `../header/img/${nombreArchivo}`;

            html += `
                <tr>
                    <td>${user.ID_usuario}</td>
                    <td>
                        <img src="${fotoURL}" 
                             width="40" height="40" 
                             style="border-radius: 50%; object-fit: cover;"
                             onerror="this.src='../header/img/default-user.png';">
                    </td>
                    <td>${user.nombre}</td>
                    <td>${user.correo}</td>
                    <td><span class="badge-rol">${user.roles || 'Usuario'}</span></td>
                    <td>${user.puntos_totales} pts</td>
                    <td>
                        <button class="btn-editar action-edit-user" data-id="${user.ID_usuario}">Editar</button>
                        <button class="btn-eliminar action-delete" data-id="${user.ID_usuario}">Banear</button>
                    </td>
                </tr>`;
        });

        html += `</tbody></table>`;
        contenedor.innerHTML = html;
    };

    // --- 2. DELEGACIÓN DE EVENTOS DE CLIC (EDITAR, BANEAR, CANCELAR) ---
    contenedor.addEventListener('click', (e) => {
        const btnEdit = e.target.closest('.action-edit-user');
        const btnDelete = e.target.closest('.action-delete');

        // BOTÓN EDITAR
        if (btnEdit) {
            const id = btnEdit.getAttribute('data-id');
            fetch(`phpConsultas/editar_usuario.php?id=${id}`)
                .then(res => res.text())
                .then(html => {
                    contenedor.innerHTML = html;
                })
                .catch(err => console.error("Error al cargar edición:", err));
        }

        // BOTÓN BANEAR (Sustituido confirm por mostrarModal)
        if (btnDelete) {
            const id = btnDelete.getAttribute('data-id');
            
            mostrarModal(
                "Confirmar Ban", 
                `¿Estás seguro de que deseas banear al usuario con ID ${id}? Perderá el acceso permanentemente.`, 
                true
            ).then((confirmado) => {
                if (confirmado) {
                    fetch(`phpConsultas/eliminar_usuario.php?id=${id}`)
                        .then(res => res.json())
                        .then(data => {
                            if (data.status === 'success') {
                                mostrarModal("Usuario Baneado", "El usuario ha sido desactivado correctamente.")
                                    .then(() => window.location.reload());
                            } else {
                                mostrarModal("Error", data.message);
                            }
                        })
                        .catch(err => console.error("Error en la eliminación:", err));
                }
            });
        }

        // BOTÓN CANCELAR (Dentro del formulario de edición)
        if (e.target.id === 'btnCancelar') {
            renderizarTablaUsuarios();
        }
    });

    // --- 3. EVENTO PARA PROCESAR LA EDICIÓN (SUBMIT) ---
    contenedor.addEventListener('submit', (e) => {
        if (e.target.id === 'formEditarUsuario') {
            e.preventDefault(); 

            const formData = new FormData(e.target);

            fetch('phpConsultas/procesar_edicion_usuario.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    mostrarModal("¡Éxito!", "Los datos del usuario se han actualizado correctamente.")
                        .then(() => {
                            // Intentamos recargar la sección sin recargar toda la web
                            const linkUsuarios = document.querySelector('[data-seccion="usuarios"]');
                            if (linkUsuarios) {
                                linkUsuarios.click(); 
                            } else {
                                window.location.reload();
                            }
                        });
                } else {
                    mostrarModal("Error", data.message);
                }
            })
            .catch(error => {
                console.error("Error:", error);
                mostrarModal("Error", "Hubo un fallo al procesar la petición.");
            });
        }
    });

    // Iniciar tabla
    renderizarTablaUsuarios();
});