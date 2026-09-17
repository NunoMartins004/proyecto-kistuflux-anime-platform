<?php
session_start();

// 1. Protección: Solo usuarios logueados
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../login/login.php");
    exit();
}

// 2. Protección: Evitar acceso directo sin parámetro de plan
if (!isset($_GET['plan'])) {
    header("Location: planes.php");
    exit();
}

include("../conexion.php");

// Detectar el plan
$plan = isset($_GET['plan']) ? $_GET['plan'] : 'mensual';
$precio = ($plan === 'anual') ? '52.50' : '5.99';
$texto_plan = ($plan === 'anual') ? 'Suscripción Anual' : 'Suscripción Mensual';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="color-scheme" content="dark">
    <title>Finalizar Pago - KitsuFlux</title>
    <link rel="stylesheet" href="../estilos.css">
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body class="body-suscripcion">
    <?php include("../header/header.php"); ?>

    <main class="area-centrada">
        <div class="contenedor-pago">
            <h1><?php echo $texto_plan; ?></h1>
            <p>Total a pagar: <span style="color: #ffb100; font-size: 1.5rem; font-weight: bold;"><?php echo $precio; ?>€</span></p>
            
            <div id="paypal-button-container"></div>
            
            <a href="planes.php" class="btn-volver">⬅ Volver a elegir plan</a>
        </div>
    </main>

    <script src="https://www.paypal.com/sdk/js?client-id=test&currency=EUR"></script>
    <script src="js/paypal-logic.js?v=1.1"></script>
    <?php include "../footer/footer.php"; ?>
</body>
</html>