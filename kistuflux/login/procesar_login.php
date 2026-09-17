<?php
// Configura la cookie para que tenga vida "0" (muere al cerrar el navegador)
ini_set('session.cookie_lifetime', 0);
ini_set('session.gc_maxlifetime', 0);

session_start();
include '../conexion.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $identificador = mysqli_real_escape_string($conn, $_POST['username']); 
    $password = $_POST['password'];

    // 1. Modificamos la consulta para traer también el ID_rol numérico
    $sql = "SELECT U.*, R.nombre AS nombre_rol, UR.ID_rol 
            FROM Usuario U
            JOIN UsuarioRol UR ON U.ID_usuario = UR.ID_usuario
            JOIN Rol R ON UR.ID_rol = R.ID_rol
            WHERE U.nombre = ? LIMIT 1";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $identificador);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);

    if ($usuario = mysqli_fetch_assoc($resultado)) {
        if (password_verify($password, $usuario['contrasena'])) {
            
            $_SESSION['id_usuario'] = $usuario['ID_usuario'];
            $_SESSION['nombre'] = $usuario['nombre'];
            $_SESSION['foto'] = $usuario['foto_perfil'];
            $_SESSION['rol_nombre'] = $usuario['nombre_rol'];
            $_SESSION['id_rol'] = $usuario['ID_rol'];
            $_SESSION['puntos'] = $usuario['puntos_totales'];

            header("Location: ../paginaPrincipal/paginaPrincipal.php");
            exit();
        } else {
            header("Location: login.php?error=password");
            exit();
        }
    } else {
        header("Location: login.php?error=user_not_found");
        exit();
    }
} else {
    header("Location: login.php");
    exit();
}
?>