<?php
// 1. SESIÓN: Si la sesión no ha empezado, la arrancamos para poder leer los datos del usuario
if (session_status() === PHP_SESSION_NONE) {
    // La cookie de sesión durará 0 segundos (hasta que se cierre el navegador)
    ini_set('session.cookie_lifetime', 0);
    ini_set('session.gc_maxlifetime', 0);
    session_start();
}


// 2. RUTAS: Definimos dónde está cada página para no perdernos al navegar
$base_url = "/proyecto/";

$carpeta_fotos = $base_url . "header/img/";
$enlace_login = $base_url . "login/login.php";
$enlace_logout = $base_url . "logout/logout.php";
$enlace_inicio = $base_url . "paginaPrincipal/paginaPrincipal.php";
$enlace_directorio = $base_url . "directorio/directorio.php";
$enlace_heardle = $base_url . "heardle/heardle.php";
$enlace_wordle = $base_url . "wordle/wordle.php";
$enlace_premium = $base_url . "pagar/suscripcion.php";
$enlace_admin = $base_url . "administracion/admin.php";
// 3. ESTADO POR DEFECTO: Si nadie ha entrado, mostramos "Iniciar sesión" y una foto genérica
$nombre_mostrar = "Iniciar sesión";
$foto_perfil = $carpeta_fotos . "default-user.png";
$enlace_perfil = $enlace_login;
$es_admin = false;
$es_suscriptor = false;

// 4. VERIFICACIÓN: ¿Hay alguien logueado?
if (isset($_SESSION['id_usuario'])) {
    // Si existe la sesión, cambiamos el nombre por el del usuario real
    $nombre_mostrar = $_SESSION['nombre'];
    $enlace_perfil = $base_url . "perfil.php"; 

    // COMPROBAR SI ES ADMIN (ID_rol 1 en tu DB)
    if (isset($_SESSION['id_rol']) && $_SESSION['id_rol'] == 1) {
        $es_admin = true;
    }

    if (isset($_SESSION['id_rol']) && $_SESSION['id_rol'] == 4) {
        $es_suscriptor = true;
    }

    // Si el usuario tiene una foto personalizada en la base de datos, la usamos
    if (!empty($_SESSION['foto'])) {
        $solo_nombre_archivo = basename($_SESSION['foto']);
        $foto_perfil = $carpeta_fotos . $solo_nombre_archivo;
    }
}
?>
<header>
    <h1>Kitsu<span id="titulo">Flux</span></h1>
    
    <nav class="nav-principal">
        <a href="<?php echo $enlace_inicio; ?>">Inicio</a>
        <a href="<?php echo $enlace_directorio; ?>">Directorio</a>
        <a href="<?php echo $enlace_wordle; ?>">Jugar Wordle</a>
        <a href="<?php echo $enlace_heardle; ?>">Jugar Heardle</a>
        <?php if (!$es_suscriptor && !$es_admin): ?>
            <a href="../pagar/planes.php" class="premium-btn">✨ Hazte Premium</a>
        <?php endif; ?>
        
        
        
    </nav>

    <div class="user-control">
        <div class="user-dropdown">
            <div class="avatar-container">
                <span class="user-name"><?php echo htmlspecialchars($nombre_mostrar); ?></span>
                <div class="avatar-fuego">
                    <img src="<?php echo $foto_perfil; ?>" alt="Usuario">
                </div>
            </div>

            <ul class="dropdown-menu">
                
                
                <li class="divider"></li>
                    <?php if ($es_admin): ?>
                       <li><a href="<?php echo $enlace_admin; ?>" class="btn-admin">Panel Admin</a></li>
                    <?php endif; ?>
                <?php if (isset($_SESSION['id_usuario'])): ?>
                    <?php if ($es_suscriptor): ?>
                        <li><span class="badge-pro-menu">⭐ Miembro PRO</span></li>
                        <li><a href="#" class="btn-logout-dropdown">Calendario  Animes</a></li>
                        <li><a href="#" class="btn-logout-dropdown">Visto recientemente</a></li>
                        <li><a href="#" class="btn-logout-dropdown">Siguiendo</a></li>
                    <?php endif; ?>
                    
                    <li class="divider"></li>
                    <li><a href="<?php echo $enlace_logout; ?>" class="btn-logout-dropdown">Cerrar Sesión</a></li>
                <?php else: ?>
                    <li><a href="<?php echo $enlace_login; ?>">Iniciar Sesión</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</header>