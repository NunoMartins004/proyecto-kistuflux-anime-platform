<?php
// 1. Configuración de errores
error_reporting(0);
ini_set('display_errors', 0);

include("../../conexion.php");
header('Content-Type: application/json');

$response = ["status" => "error", "message" => "Error desconocido"];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $titulo = $_POST['titulo'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $es_banner = isset($_POST['es_banner']) ? intval($_POST['es_banner']) : 0;

    // Validar que el título y la imagen existan
    if (!empty($titulo) && isset($_FILES['nueva_imagen']) && $_FILES['nueva_imagen']['error'] === UPLOAD_ERR_OK) {
        
        try {
            $conn->begin_transaction();

            // 2. INSERTAR EN LA TABLA ANIME
            $stmt1 = $conn->prepare("INSERT INTO Anime (titulo, descripcion) VALUES (?, ?)");
            $stmt1->bind_param("ss", $titulo, $descripcion);
            $stmt1->execute();
            
            // Obtener el ID que se acaba de generar automáticamente
            $id_nuevo_anime = $conn->insert_id;

            // 3. PROCESAR LA IMAGEN
            $nombreOriginal = $_FILES['nueva_imagen']['name'];
            $rutaParaBaseDatos = "imagenes/" . $nombreOriginal;
            $rutaFinalFisica = "../../" . $rutaParaBaseDatos;

            if (move_uploaded_file($_FILES['nueva_imagen']['tmp_name'], $rutaFinalFisica)) {
                
                // 4. INSERTAR EN LA TABLA IMAGENESANIME
                // Aquí usamos el ID obtenido en el paso 2
                $stmt2 = $conn->prepare("INSERT INTO imagenesAnime (ID_anime, url_imagen, es_banner) VALUES (?, ?, ?)");
                $stmt2->bind_param("isi", $id_nuevo_anime, $rutaParaBaseDatos, $es_banner);
                $stmt2->execute();

                $conn->commit();
                $response = ["status" => "success"];
            } else {
                throw new Exception("No se pudo guardar la imagen en el servidor.");
            }

        } catch (Exception $e) {
            $conn->rollback();
            $response = ["status" => "error", "message" => $e->getMessage()];
        }
    } else {
        $response["message"] = "Faltan datos obligatorios (Título o Imagen).";
    }
}

echo json_encode($response);
exit;