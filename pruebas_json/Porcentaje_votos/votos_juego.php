<?php

include_once './config.php';
include_once 'conexion.php';

$datos = json_decode($_POST['json'], true);
$game = "'".$datos['game']."'";
$conexion = conexion();

function getVoteGame($game) {
    global $conexion, $config;
    $query= "select count(game) as votos_totales from user_votes where game in (select id from games where name =".$game.") group by game;";
    $resultado = mysqli_query($conexion, $query);
    $errorNo = mysqli_errno($conexion);
    $errorMsg = mysqli_error($conexion);
    while ($total = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
        $info[] = array(
        "juego" => $total
    );
    }
    $query= "select count(game) as votos_positivos from user_votes where game in (select id from games where name =".$game.") and vote = 1 group by game;";
    $resultado = mysqli_query($conexion, $query);
    $errorNo = mysqli_errno($conexion);
    $errorMsg = mysqli_error($conexion);
    while ($positivo = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
        $info[] = array(
        "positivos" => $positivo
    );
    }
    header('Content-Type: application/json');
    echo json_encode($info);
}

echo getVoteGame($game);
?>
