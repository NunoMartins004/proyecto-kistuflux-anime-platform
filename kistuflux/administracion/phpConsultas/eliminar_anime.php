<?php
// 1. Silenciamos errores de texto para evitar el "Unexpected token <" en JS
error_reporting(0);
ini_set('display_errors', 0);

include("../../conexion.php"); 

// 2. Limpiamos cualquier espacio o eco previo que pueda manchar el JSON
if (ob_get_length()) ob_clean(); 
header('Content-Type: application/json');

$response = [];

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Aseguramos que sea un número

    try {
        // 3. OBTENER RUTA DE IMAGEN (De la tabla imagenesAnime)
        $query_img = "SELECT url_imagen FROM imagenesAnime WHERE ID_anime = ?";
        $stmt_img = $conn->prepare($query_img);
        $stmt_img->bind_param("i", $id);
        $stmt_img->execute();
        $resultado = $stmt_img->get_result();
        $anime = $resultado->fetch_assoc();

        // Guardamos la ruta física antes de borrar nada
        $ruta_fisica = "";
        if ($anime) {
            $ruta_fisica = "../../" . $anime['url_imagen']; 
        }

        // 4. BORRAR EN LA BASE DE DATOS
        // Primero borramos la imagen (tabla imagenesAnime) por la relación de ID
        $query_del_img = "DELETE FROM imagenesAnime WHERE ID_anime = ?";
        $stmt_del_img = $conn->prepare($query_del_img);
        $stmt_del_img->bind_param("i", $id);
        $stmt_del_img->execute();

        // Luego borramos el anime (tabla Anime)
        $query_del_anime = "DELETE FROM Anime WHERE ID_anime = ?";
        $stmt_del_anime = $conn->prepare($query_del_anime);
        $stmt_del_anime->bind_param("i", $id);

        if ($stmt_del_anime->execute()) {
            
            // 5. BORRAR ARCHIVO FÍSICO
            // Solo si se borró de la BD y el archivo realmente existe en la carpeta
            if (!empty($ruta_fisica) && file_exists($ruta_fisica)) {
                unlink($ruta_fisica); 
            }
            
            $response['status'] = 'success';
            $response['message'] = 'Anime eliminado de la BD y archivo borrado de la carpeta.';
        } else {
            $response['status'] = 'error';
            $response['message'] = 'No se pudo eliminar el registro de la base de datos.';
        }

    } catch (Exception $e) {
        $response['status'] = 'error';
        $response['message'] = 'Error: ' . $e->getMessage();
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'No se recibió el ID.';
}

// 6. Enviamos la respuesta limpia
echo json_encode($response);
exit;