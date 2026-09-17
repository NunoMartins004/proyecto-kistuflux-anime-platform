<?php

    $host = "localHost";

    $user = "root";

    $pass = "";

    $db = "AnimeApp";



    try{



       

        $conn = mysqli_connect($host, $user, $pass, $db);



       

        mysqli_set_charset($conn, "utf8mb4");



    } catch (Exception $e){



        exit("ERROR de la conexion: no se pudo conectar correctamente a la base de datos ");

    }



?>