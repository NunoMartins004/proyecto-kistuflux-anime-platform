<?php
session_start();

// Solo procesar si hay sesión activa
if (!isset($_SESSION['id_usuario'])) {
    die(json_encode(['status' => 'error', 'msg' => 'No autorizado']));
}

$conexion = new mysqli("localhost", "root", "", "animeapp");

// Leemos los datos que nos manda el JS (ahora incluimos 'monto')
$datos = json_decode(file_get_contents('php://input'), true);

if (!empty($datos['orderID']) && isset($_SESSION['ID_usuario'])) {
    $orderID = $datos['orderID'];
    $usuarioID = $_SESSION['ID_usuario'];
    
    // Capturamos el monto enviado desde el JS. 
    // Si por algún motivo no llega, ponemos 5.99 por seguridad.
    $monto = isset($datos['monto']) ? floatval($datos['monto']) : 5.99;

    // 1. Actualizamos al usuario a Premium
    $update = "UPDATE usuario SET es_premium = 1 WHERE ID_usuario = $usuarioID";
    $conexion->query($update);

    // 2. Registramos el pago con el MONTO DINÁMICO
    // Usamos la variable $monto en lugar del número fijo
    $insert = "INSERT INTO pagos (ID_usuario, id_transaccion, monto) 
               VALUES ($usuarioID, '$orderID', $monto)";
    
    if ($conexion->query($insert)) {
        // Actualizamos la sesión para que el cambio sea instantáneo en la web
        $_SESSION['es_premium'] = 1;
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'msg' => $conexion->error]);
    }
}
?>