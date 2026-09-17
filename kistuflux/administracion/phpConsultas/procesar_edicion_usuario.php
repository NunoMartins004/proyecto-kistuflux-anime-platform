<?php
// phpConsultas/procesar_edicion_usuario.php
header('Content-Type: application/json');

// Desactivar errores visibles que rompen el JSON, pero capturarlos internamente
ini_set('display_errors', 0);
error_reporting(E_ALL);

include("../../conexion.php");

$response = ["status" => "error", "message" => "Error desconocido"];

try {
    // Verificar que los datos existan en $_POST
    if (!isset($_POST['ID_usuario']) || empty($_POST['nombre'])) {
        throw new Exception("Faltan datos obligatorios (ID o Nombre)");
    }

    $id_usuario = intval($_POST['ID_usuario']);
    $nombre     = mysqli_real_escape_string($conn, $_POST['nombre']);
    $puntos     = intval($_POST['puntos_totales']);
    $id_rol     = intval($_POST['ID_rol']);

    // 1. Actualizar tabla Usuario
    $sql1 = "UPDATE Usuario SET nombre = '$nombre', puntos_totales = $puntos WHERE ID_usuario = $id_usuario";
    if (!mysqli_query($conn, $sql1)) {
        throw new Exception("Error al actualizar usuario: " . mysqli_error($conn));
    }

    // 2. Actualizar tabla UsuarioRol
    // Borramos el anterior e insertamos el nuevo
    mysqli_query($conn, "DELETE FROM UsuarioRol WHERE ID_usuario = $id_usuario");
    
    $sql3 = "INSERT INTO UsuarioRol (ID_usuario, ID_rol) VALUES ($id_usuario, $id_rol)";
    if (!mysqli_query($conn, $sql3)) {
        throw new Exception("Error al actualizar el rol: " . mysqli_error($conn));
    }

    $response = ["status" => "success", "message" => "Usuario actualizado correctamente"];

} catch (Exception $e) {
    $response["message"] = $e->getMessage();
}

echo json_encode($response);