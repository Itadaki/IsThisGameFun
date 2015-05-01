<?php

include_once './config.php';
include_once 'conexion.php';

$datos = json_decode($_POST['json'], true);

$conexion = conexion();

function validateUser($datos) {
    global $conexion, $config;
    $query = "select user_nick from users;";
    $resultado = mysqli_query($conexion, $query);
    $errorNo = mysqli_errno($conexion);
    $errorMsg = mysqli_error($conexion);
    $info = array();
    while ($user = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
        if ($datos["user"] == $user['user_nick']) {
            $existe=true;
            break;
        } else {
            $existe=false;
        }
    }
    $info[] = array(
        "existe" => $existe
    );


    header('Content-Type: application/json');
    echo json_encode($info);
}

echo validateUser($datos);
?>
