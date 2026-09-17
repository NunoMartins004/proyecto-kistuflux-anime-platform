<?php
// 1. Configuración de errores
error_reporting(0);
ini_set('display_errors', 0);

include("../../conexion.php");
header('Content-Type: application/json');

$response = ["status" => "error", "message" => "Error desconocido"];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $id_imagen = isset($_POST['id_imagen']) ? intval($_POST['id_imagen']) : 0;
    $titulo = $_POST['titulo'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $es_banner = isset($_POST['es_banner']) ? intval($_POST['es_banner']) : 0;

    if ($id > 0 && $id_imagen > 0) {
        try {
            $conn->begin_transaction();

            // 3. Actualizar datos de texto
            $stmt1 = $conn->prepare("UPDATE Anime SET titulo = ?, descripcion = ? WHERE ID_anime = ?");
            $stmt1->bind_param("ssi", $titulo, $descripcion, $id);
            $stmt1->execute();

            $stmt2 = $conn->prepare("UPDATE imagenesAnime SET es_banner = ? WHERE id_imagen = ?");
            $stmt2->bind_param("ii", $es_banner, $id_imagen);
            $stmt2->execute();

            // 5. Lógica de Imagen: Usar el nombre original del archivo subido
            if (isset($_FILES['nueva_imagen']) && $_FILES['nueva_imagen']['error'] === UPLOAD_ERR_OK) {
                
                // A. Obtener el nombre original del archivo que estás subiendo
                $nombreOriginal = $_FILES['nueva_imagen']['name'];
                
                // B. Consultar la imagen actual para borrarla si el nombre es distinto
                $sqlVieja = "SELECT url_imagen FROM imagenesAnime WHERE id_imagen = ?";
                $stmtV = $conn->prepare($sqlVieja);
                $stmtV->bind_param("i", $id_imagen);
                $stmtV->execute();
                $resVieja = $stmtV->get_result()->fetch_assoc();

                $rutaParaBaseDatos = "imagenes/" . $nombreOriginal;
                $rutaFinalFisica = "../../" . $rutaParaBaseDatos;

                if ($resVieja && !empty($resVieja['url_imagen'])) {
                    $rutaAntigua = "../../" . $resVieja['url_imagen'];
                    
                    // Si el nombre nuevo es diferente al viejo, borramos el viejo
                    if (file_exists($rutaAntigua) && $rutaAntigua !== $rutaFinalFisica) {
                        unlink($rutaAntigua);
                    }
                }

                // C. Subir el archivo con su nombre original
                if (move_uploaded_file($_FILES['nueva_imagen']['tmp_name'], $rutaFinalFisica)) {
                    // Actualizar la nueva ruta en la BD
                    $stmt3 = $conn->prepare("UPDATE imagenesAnime SET url_imagen = ? WHERE id_imagen = ?");
                    $stmt3->bind_param("si", $rutaParaBaseDatos, $id_imagen);
                    $stmt3->execute();
                } else {
                    throw new Exception("No se pudo guardar el archivo con el nombre original.");
                }
            }

            $conn->commit();
            $response = ["status" => "success"];

        } catch (Exception $e) {
            $conn->rollback();
            $response = ["status" => "error", "message" => $e->getMessage()];
        }
    } else {
        $response["message"] = "IDs no válidos.";
    }
}

echo json_encode($response);
exit;