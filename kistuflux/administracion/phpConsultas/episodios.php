<?php
// phpConsultas/cargar_episodios.php
include("../conexion.php"); 

$listaEpisodios = [];

try {
    // Consulta con doble JOIN
    // Traemos e.* (datos episodio), a.titulo (nombre anime) e i.url_imagen (foto)
    $sql = "SELECT 
                e.ID_episodio, 
                e.titulo, 
                e.numero_episodio, 
                e.url_video, 
                a.titulo AS nombre_anime,
                i.url_imagen
            FROM Episodio e
            INNER JOIN Anime a ON e.ID_anime = a.ID_anime
            INNER JOIN ImagenesAnime i ON a.ID_anime = i.ID_anime AND i.es_banner = FALSE
            ORDER BY e.ID_episodio DESC";

    $result = $conn->query($sql);

    if (!$result) {
        throw new Exception("Error en SQL: " . $conn->error);
    }

    while($row = $result->fetch_assoc()) {
        // Si no tiene imagen, le ponemos un placeholder
        $row['url_imagen'] = $row['url_imagen'] ?? 'img/default-anime.png';
        $listaEpisodios[] = $row;
    }

} catch (Exception $e) {
    echo "<script>console.error('Error PHP: " . addslashes($e->getMessage()) . "');</script>";
    $listaEpisodios = [];
}

echo "<script> const DATOS_EPISODIOS = " . json_encode($listaEpisodios, JSON_UNESCAPED_UNICODE) . "; </script>";
echo "<script src='js/episodios.js'></script>";
?>