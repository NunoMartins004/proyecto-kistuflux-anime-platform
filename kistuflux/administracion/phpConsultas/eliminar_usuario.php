<?php
// phpConsultas/eliminar_usuario.php

header('Content-Type: application/json');
include("../../conexion.php");

$response = ["status" => "error", "message" => "Error desconocido"];

try {
    if (!isset($_GET['id']) || empty($_GET['id'])) {
        throw new Exception("ID de usuario no proporcionado.");
    }

    $id_usuario = intval($_GET['id']);

    // 1. OBTENER LA RUTA DE LA FOTO ANTES DE BORRAR AL USUARIO
    $sqlFoto = "SELECT foto_perfil FROM Usuario WHERE ID_usuario = ?";
    $stmtFoto = $conn->prepare($sqlFoto);
    $stmtFoto->bind_param("i", $id_usuario);
    $stmtFoto->execute();
    $resultadoFoto = $stmtFoto->get_result();
    $datosUsuario = $resultadoFoto->fetch_assoc();

    // 2. Iniciar Transacción
    $conn->begin_transaction();

    // A) Eliminar roles asignados
    $sqlRoles = "DELETE FROM UsuarioRol WHERE ID_usuario = ?";
    $stmtRoles = $conn->prepare($sqlRoles);
    $stmtRoles->bind_param("i", $id_usuario);
    $stmtRoles->execute();

    // B) Eliminar al usuario de la tabla principal
    $sqlUser = "DELETE FROM Usuario WHERE ID_usuario = ?";
    $stmtUser = $conn->prepare($sqlUser);
    $stmtUser->bind_param("i", $id_usuario);

    if ($stmtUser->execute()) {
        // C) BORRADO FÍSICO DE LA IMAGEN (Con protección)
        if ($datosUsuario && !empty($datosUsuario['foto_perfil'])) {
            
            // Extraemos solo el nombre del archivo (ej: "usuario123.jpg")
            $nombreArchivo = basename($datosUsuario['foto_perfil']); 
            $rutaCompleta = "../../header/img/" . $nombreArchivo;

            /**
             * PROTECCIÓN:
             * No borramos si el nombre del archivo es exactamente 'default-user.png'
             * o si contiene la frase 'default-user' (por si tiene extensiones .jpg, .jpeg, etc)
             */
            $esImagenDefault = (strpos($nombreArchivo, 'default-user') !== false);

            if (!$esImagenDefault && file_exists($rutaCompleta)) {
                unlink($rutaCompleta);
            }
        }

        $conn->commit();
        $response["status"] = "success";
        $response["message"] = "Usuario e imagen personal eliminados correctamente.";
    } else {
        throw new Exception("No se pudo eliminar el usuario: " . $conn->error);
    }

} catch (Exception $e) {
    if (isset($conn)) { $conn->rollback(); }
    $response["message"] = $e->getMessage();
}

echo json_encode($response);