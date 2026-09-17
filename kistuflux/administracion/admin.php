<?php
session_start();
include("../conexion.php");

// SEGURIDAD: Control de acceso
// Cambiamos 'admin' por 'administrador' porque así está en tu tabla 'rol'
if (!isset($_SESSION['id_rol']) || $_SESSION['id_rol'] != 1) {
    // Si no es admin, lo mandamos al inicio o al login
    header("Location: ../paginaPrincipal/paginaPrincipal.php?error=acceso_denegado");
    exit(); // ¡Importante! El exit detiene la carga de todo el resto del archivo
}

// El resto de tu código...
$seccion = isset($_GET['seccion']) ? $_GET['seccion'] : 'animes';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Control</title>
    <link rel="stylesheet" href="css/animes.css">
    <link rel="stylesheet" href="../estilos.css">
    <link rel="stylesheet" href="css/formulario.css">
    <link rel="icon" href="../imagenes/kitsuflux.ico" type="image/x-icon">
    <script src="./js/graficos.js"></script>
</head>
<body>

    <?php
        include("../header/header.php");
    ?>
    <nav class="sidebar">
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="admin.php?seccion=animes">Ver Animes</a></li>
            <li><a href="admin.php?seccion=episodios">Ver Episodios</a></li>
            <li><a href="admin.php?seccion=usuarios">Usuarios</a></li>
            <li><button id= "abrir-kitsu"> ver graficos</button></li>
        </ul>
    </nav>

    <main class="contenido-dinamico">
        <?php 
            // Según el valor de ?seccion=, cargamos un archivo de la carpeta "vistas"
            switch($seccion) {
                case 'episodios':
                    include("phpConsultas/episodios.php");
                    break;
                case 'usuarios':
                    include("phpConsultas/usuarios.php");
                    break;
                case 'animes':
                default:
                    include("phpConsultas/animes.php");
                    break;
            }
        ?>
    </main>

    <div id="modalGestion" class="modal-emergente">
    <div class="modal-contenido-box">
        <div class="modal-header">
            <h2 id="modalTitulo">KitsuFlux System</h2>
        </div>
        <div class="modal-body">
            <p id="modalMensaje"></p>
        </div>
        <div class="modal-footer">
            <button id="btnModalAceptar" class="btn-fuego">Aceptar</button>
            <button id="btnModalCancelar" class="btn-oscuro" style="display: none;">Cancelar</button>
        </div>
    </div>
</div>
    
<?php include "../footer/footer.php"; ?>
</body>
</html>