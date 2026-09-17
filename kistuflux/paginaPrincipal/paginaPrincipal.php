<?php

session_start();

include("../conexion.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KitsuFlux</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js" defer></script>

    <link rel="icon" href="../imagenes/kitsuflux.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/carrusel.css">
    <link rel="stylesheet" href="css/episodios.css">
    <link rel="stylesheet" href="../estilos.css">

    
    <script src="js/Carrusel.js"></script>
    <script src="js/ultimosEpisodios.js"></script>


</head>
<?php
    include("../header/header.php");
    
?>


<div class="swiper mySwiper">
    <div class="swiper-wrapper">
    </div>

    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>

    <div class="swiper-pagination"></div>
</div>

<main>
    <section class="seccion-recientes">

        <div class="centrar">

            <h2 class="titulo-seccion">Ultimos episodios añadidos</h2>
        </div>

        <div id="contenedor-episodios" class="grid-episodios"></div>
    </section>
</main>

<?php include "../footer/footer.php"; ?>
<body>

</body>

</html>