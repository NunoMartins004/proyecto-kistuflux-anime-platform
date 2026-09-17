<?php
// phpConsultas/procesar_edicion_episodio.php

header('Content-Type: application/json');
include("../../conexion.php");

$response = ["status" => "error", "message" => "Error al actualizar"];

try {
    if (!isset($conn) || $conn->connect_error) {
        throw new Exception("Error de conexión.");
    }

    // 1. Recoger datos del POST
    $id_episodio     = isset($_POST['ID_episodio']) ? intval($_POST['ID_episodio']) : 0;
    $id_anime        = isset($_POST['ID_anime']) ? intval($_POST['ID_anime']) : 0;
    $titulo          = isset($_POST['titulo']) ? trim($_POST['titulo']) : '';
    $numero_episodio = isset($_POST['numero_episodio']) ? intval($_POST['numero_episodio']) : 0;
    $fecha_estreno   = !empty($_POST['fecha_estreno']) ? $_POST['fecha_estreno'] : null;
    $url_video       = isset($_POST['url_video']) ? trim($_POST['url_video']) : '';

    // 2. Validación mínima
    if ($id_episodio === 0 || $id_anime === 0 || empty($titulo)) {
        throw new Exception("Faltan datos obligatorios para actualizar.");
    }

    // 3. Consulta de actualización
    $sql = "UPDATE Episodio SET 
                ID_anime = ?, 
                titulo = ?, 
                numero_episodio = ?, 
                fecha_estreno = ?, 
                url_video = ? 
            WHERE ID_episodio = ?";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Error en la preparación: " . $conn->error);
    }

    // Tipos: i (int), s (string), i (int), s (string), s (string), i (int)
    $stmt->bind_param("isissi", $id_anime, $titulo, $numero_episodio, $fecha_estreno, $url_video, $id_episodio);

    if ($stmt->execute()) {
        $response["status"] = "success";
        $response["message"] = "Episodio actualizado correctamente.";
    } else {
        if ($conn->errno == 1062) {
            throw new Exception("Error: Ya existe ese número de episodio para ese anime.");
        }
        throw new Exception("Error al ejecutar: " . $stmt->error);
    }

    $stmt->close();

} catch (Exception $e) {
    $response["message"] = $e->getMessage();
}

echo json_encode($response);