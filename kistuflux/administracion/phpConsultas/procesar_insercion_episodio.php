<?php
// phpConsultas/procesar_insercion_episodio.php

// 1. Configuración de errores (para que el JS reciba el error si algo falla)
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

include("../../conexion.php");

$response = ["status" => "error", "message" => "Error desconocido"];

try {
    // 2. Verificar conexión
    if (!isset($conn) || $conn->connect_error) {
        throw new Exception("Error de conexión a la base de datos.");
    }

    // 3. Recoger y limpiar datos del formulario
    $id_anime        = isset($_POST['ID_anime']) ? intval($_POST['ID_anime']) : 0;
    $titulo          = isset($_POST['titulo']) ? trim($_POST['titulo']) : '';
    $numero_episodio = isset($_POST['numero_episodio']) ? intval($_POST['numero_episodio']) : 0;
    $fecha_estreno   = isset($_POST['fecha_estreno']) ? $_POST['fecha_estreno'] : null;
    $url_video       = isset($_POST['url_video']) ? trim($_POST['url_video']) : '';

    // 4. Validaciones básicas
    if ($id_anime === 0 || empty($titulo) || $numero_episodio === 0) {
        throw new Exception("Por favor, rellena todos los campos obligatorios (Anime, Número y Título).");
    }

    // 5. Preparar la consulta SQL (Usamos Prepared Statements por seguridad)
    $sql = "INSERT INTO Episodio (ID_anime, titulo, numero_episodio, fecha_estreno, url_video) 
            VALUES (?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        throw new Exception("Error al preparar la consulta: " . $conn->error);
    }

    // "isiss" significa: integer, string, integer, string, string (los tipos de datos)
    $stmt->bind_param("isiss", $id_anime, $titulo, $numero_episodio, $fecha_estreno, $url_video);

    // 6. Ejecutar
    if ($stmt->execute()) {
        $response["status"] = "success";
        $response["message"] = "Episodio insertado correctamente.";
    } else {
        // Manejo específico para el UNIQUE KEY (ID_anime + numero_episodio)
        if ($conn->errno == 1062) {
            throw new Exception("Este número de episodio ya existe para este anime.");
        }
        throw new Exception("Error al insertar: " . $stmt->error);
    }

    $stmt->close();

} catch (Exception $e) {
    $response["message"] = $e->getMessage();
}

// 7. Retornar respuesta al fetch de episodios.js
echo json_encode($response);