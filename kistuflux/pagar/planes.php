<?php
session_start();

// 1. Bloqueo para invitados: Si no hay ID de usuario, al login
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../login/login.php");
    exit();
}

// 2. Protección: Si ya es premium, no necesita comprar de nuevo
if (isset($_SESSION['es_premium']) && $_SESSION['es_premium'] == 1) {
    header("Location: ../paginaPrincipal/paginaPrincipal.php");
    exit();
}

include("../conexion.php") 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planes Premium - KitsuFlux</title>
    <link rel="stylesheet" href="../estilos.css"> 
    <link rel="stylesheet" href="css/estilos_planes.css"> 
    <link rel="icon" href="../imagenes/kitsuflux.ico" type="image/x-icon">
</head>
<body class="body-suscripcion">

    <?php include("../header/header.php"); ?>

    <main class="contenedor-planes">
        <h1 class="titulo-planes">Mejora tu cuenta en KitsuFlux</h1>
        <p style="color: #888; margin-bottom: 40px;">Elige el plan que mejor se adapte a ti</p>
        
        <div class="grid-planes">
            <div class="tarjeta-plan">
                <h2>Mensual</h2>
                <div class="precio">5,99€<span>/mes</span></div>
                <ul class="beneficios-lista">
                    <li>Sin anuncios</li>
                    <li>Calidad HD</li>
                    <li>Soporte prioritario</li>
                </ul>
                <a href="suscripcion.php?plan=mensual" class="btn-elegir">Elegir Mensual</a>
            </div>

            <div class="tarjeta-plan destacado">
                <div class="badge-popular">MEJOR VALOR</div>
                <h2>Anual</h2>
                <div class="precio">52,50€<span>/año</span></div>
                <ul class="beneficios-lista">
                    <li>Todo lo del plan mensual</li>
                    <li>precio total mas barato</li>
                    <li>Insignia dorada en perfil</li>
                </ul>
                <a href="suscripcion.php?plan=anual" class="btn-elegir">Elegir Anual</a>
            </div>
        </div>
        <a href="../paginaPrincipal/paginaPrincipal.php" class="btn-volver">Ahora no, gracias</a>
    </main>
<?php include "../footer/footer.php"; ?>

</body>
</html>