<?php
// Capturamos los datos enviados por la URL
$video_url = isset($_GET['v']) ? $_GET['v'] : '';
$anime_titulo = isset($_GET['t']) ? $_GET['t'] : 'Reproductor';
$episodio_num = isset($_GET['e']) ? $_GET['e'] : '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Viendo: <?php echo htmlspecialchars($anime_titulo); ?></title>
    <style>
        body { background: #000; color: #fff; font-family: sans-serif; text-align: center; margin: 0; }
        .container { max-width: 900px; margin: 50px auto; }
        video { width: 100%; border: 2px solid #333; border-radius: 8px; }
        h1 { font-size: 24px; margin-top: 20px; }
        .back-btn { display: inline-block; margin-top: 20px; color: #ff0000; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($video_url): ?>
            <video controls autoplay>
                <source src="<?php echo htmlspecialchars($video_url); ?>" type="video/mp4">
                Tu navegador no soporta el formato de video.
            </video>
            <h1><?php echo htmlspecialchars($anime_titulo); ?> - Episodio <?php echo htmlspecialchars($episodio_num); ?></h1>
        <?php else: ?>
            <h1>Error: No se encontró el video.</h1>
        <?php endif; ?>

        <a href="paginaPrincipal.php" class="back-btn">← Volver al inicio</a>
    </div>
</body>
</html>