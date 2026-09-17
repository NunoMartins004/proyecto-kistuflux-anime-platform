<?php
// phpConsultas/eliminar_episodio.php

// 1. Configuración de errores para que no ensucien el JSON
error_reporting(0);
ini_set('display_errors', 0);

header('Content-Type: application/json');

include("../../conexion.php");

$response = ["status" => "error", "message" => "No se pudo eliminar el episodio."];

try {
    // 2. Verificar si se recibió el ID
    if (!isset($_GET['id']) || empty($_GET['id'])) {
        throw new Exception("ID de episodio no proporcionado.");
    }

    $id_episodio = intval($_GET['id']);

    // 3. Verificar la conexión
    if (!isset($conn) || $conn->connect_error) {
        throw new Exception("Error de conexión con la base de datos.");
    }

    // 4. Preparar la consulta de eliminación
    $sql = "DELETE FROM Episodio WHERE ID_episodio = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        throw new Exception("Error al preparar la consulta: " . $conn->error);
    }

    $stmt->bind_param("i", $id_episodio);

    // 5. Ejecutar y verificar si se eliminó algo
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            $response["status"] = "success";
            $response["message"] = "Episodio eliminado correctamente.";
        } else {
            throw new Exception("No se encontró el episodio o ya ha sido eliminado.");
        }
    } else {
        throw new Exception("Error al ejecutar la eliminación: " . $stmt->error);
    }

    $stmt->close();

} catch (Exception $e) {
    $response["message"] = $e->getMessage();
}

// 6. Devolver respuesta al fetch
echo json_encode($response);
?>