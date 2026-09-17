<?php
// phpConsultas/usuarios.php
include("../conexion.php");

$listaUsuarios = [];

try {
    // Consulta para traer los datos del usuario y su nombre de Rol
    // Usamos GROUP_CONCAT por si un usuario tiene varios roles (aunque tu DB parece de 1 a 1)
// ... dentro de usuarios.php ...
$sql = "SELECT 
            u.ID_usuario, 
            u.nombre, 
            u.correo, 
            u.puntos_totales, 
            u.foto_perfil, 
            GROUP_CONCAT(r.nombre SEPARATOR ', ') AS roles
        FROM Usuario u
        LEFT JOIN UsuarioRol ur ON u.ID_usuario = ur.ID_usuario
        LEFT JOIN Rol r ON ur.ID_rol = r.ID_rol
        GROUP BY u.ID_usuario
        ORDER BY u.ID_usuario DESC";

    $result = $conn->query($sql);

    if ($result) {
        while($row = $result->fetch_assoc()) {
            $listaUsuarios[] = $row;
        }
    }

} catch (Exception $e) {
    echo "<script>console.error('Error en Usuarios: " . addslashes($e->getMessage()) . "');</script>";
}

// Pasamos los datos a la variable Global de JavaScript
echo "<script> const DATOS_USUARIOS = " . json_encode($listaUsuarios, JSON_UNESCAPED_UNICODE) . "; </script>";

// Llamamos al archivo JS de usuarios
echo "<script src='js/usuarios.js'></script>";
?>