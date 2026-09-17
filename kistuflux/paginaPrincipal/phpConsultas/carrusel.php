<?php
require_once("../../conexion.php");


$sql = "SELECT A.titulo, A.descripcion, I.url_imagen 
        FROM Anime A
        INNER JOIN ImagenesAnime I ON A.ID_anime = I.ID_anime 
        WHERE I.es_banner = 1 
        ORDER BY I.fecha_subida DESC, A.fecha_estreno DESC 
        LIMIT 3";

$resultado = $conn->query($sql);
$animes = [];

if ($resultado && $resultado->num_rows > 0) {
    while($row = $resultado->fetch_assoc()) {
        
        $animes[] = $row; 
    }
}


echo json_encode($animes);
?>