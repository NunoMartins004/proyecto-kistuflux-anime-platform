<?php
require_once("../../conexion.php"); 

$sql = "SELECT A.ID_anime as id, A.titulo, A.descripcion, A.genero, I.url_imagen as imagen 
        FROM Anime A
        INNER JOIN ImagenesAnime I ON A.ID_anime = I.ID_anime
        WHERE I.es_banner = 0"; 

$resultado = $conn->query($sql);
$animes = [];

if ($resultado && $resultado->num_rows > 0) {
    while($row = $resultado->fetch_assoc()) {
        $animes[] = $row;
    }
}

echo json_encode($animes);
?>