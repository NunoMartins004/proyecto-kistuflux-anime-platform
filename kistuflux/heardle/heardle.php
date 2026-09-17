<?php
session_start();
if (!isset($_SESSION['id_usuario'])) { header("Location: ../login/login.php"); exit(); }

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>KitsuHeardle - Openings</title>
    <link rel="stylesheet" href="../estilos.css">
    <link rel="stylesheet" href="css/heardle.css">
    <link rel="icon" href="../imagenes/kitsuflux.ico" type="image/x-icon">
    <script src="https://www.youtube.com/iframe_api"></script>
</head>
<body style="background-color: #1a1a1a; color: white;">
    <?php include("../header/header.php"); ?>

    <div class="contenedor-heardle">
        <h1>Kitsu<span style="color:red;">Heardle</span></h1>
        <p>Escucha el fragmento y adivina el anime</p>

        <div id="reproductor-container">
            <div id="player"></div> 
            <div id="overlay-video">?</div> 
        </div>

        <div class="controles-audio">
            <button id="btn-play" class="btn-circular">▶</button>
            <div class="control-volumen" style="margin-top: 15px; display: flex; align-items: center; justify-content: center; gap: 10px;">
                <span>🔈</span>
                <input type="range" id="volumen-slider" min="0" max="100" value="30" style="cursor: pointer; accent-color: red;">
                <span>🔊</span>
            </div>
            <div class="barra-progreso">
                <div id="progreso-actual"></div>
                <div class="marcas-intentos">
                    <span>2s</span><span>4s</span><span>6s</span><span>8s</span><span>10s</span><span>12s</span>
                </div>
            </div>
        </div>

        <div class="buscador-anime">
            <input type="text" id="input-busqueda" placeholder="Busca el anime..." list="lista-animes" autocomplete="off">
            <datalist id="lista-animes">
                <option value="NARUTO">
                <option value="ONE PIECE">
                <option value="BLEACH">
                <option value="FRIEREN">
                <option value="AKIRA">
            </datalist>
            <button id="btn-enviar-heardle">ENVIAR</button>
        </div>

        <p id="mensaje-heardle"></p>
    </div>

<?php include "../footer/footer.php"; ?>
    <script>const idUsuarioActual = "<?php echo $_SESSION['id_usuario']; ?>";</script>
    <script src="<?php echo $base_url; ?>heardle/js/heardle.js"></script>
</body>
</html>