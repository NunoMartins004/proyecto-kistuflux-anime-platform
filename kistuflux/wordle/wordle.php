<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../login/login.php");
    exit();
}


?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anime Wordle - KitsuFlux</title>
    <link rel="stylesheet" href="../estilos.css">
    <link rel="stylesheet" href= "css/wordle.css">
    <link rel="icon" href="../imagenes/kitsuflux.ico" type="image/x-icon">
</head>
<body style="background-color: #1a1a1a; margin: 0; font-family: Arial, sans-serif;">

    <?php include("../header/header.php"); ?>

    <div class="contenedor-wordle">
        <h1 style="color: white;">Anime <span style="color:red;">Wordle</span></h1>
        <p style="color: #aaa;">Adivina el anime de 6 letras del día</p>
        
        <div id="tablero"></div>
        
        <div class="controles">
            <input type="text" id="intento" maxlength="6" placeholder="Escribe...">
            <button id="btn-comprobar" onclick="comprobar()">ENVIAR</button>
        </div>
        <p id="mensaje" style="color: #ff4d4d; font-weight: bold; margin-top: 20px;"></p>
    </div>

    <script>
        const idUsuarioActual = "<?php echo $_SESSION['id_usuario']; ?>";
    </script>
    <?php include "../footer/footer.php"; ?>
    <script src="<?php echo $base_url; ?>wordle/js/wordle.js"></script>
</body>
</html>