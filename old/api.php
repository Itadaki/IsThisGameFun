<?php
/*
 * Autor = Diego Rodríguez Suárez-Bustillo
 * Fecha = 23-feb-2015
 * Licencia = gpl30 
 * Version = 1.0
 * Descripcion = Contiene funciones para generar respuesta ajax xml
 */

/*
 * Copyright (C) 2015 Diego Rodríguez Suárez-Bustillo
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

include_once './config.php';
include_once 'conexion.php';
include_once 'getGames.php';

if (!empty($_COOKIE['allow_cookies'])) {
    session_start();
}
$conexion = conexion();

$json_salida = array();
//var_dump($_POST);
//RECIBIR
if ($_POST) {
    $json_entrada = json_decode($_POST['json'], true);
//    var_dump($json_entrada);
    $accion = $json_entrada['action'];
    switch ($accion) {
        case 'vote':
            if (isset($_SESSION['user_id']) && isset($json_entrada['game_id']) && isset($json_entrada['vote'])) {
                $json_salida = register_vote($_SESSION['user_id'], $json_entrada['game_id'], $json_entrada['vote']);
            }
            break;
        case 'getLatestGames':
            $games = getLatestGames(isset($json_entrada['offset']) ? $json_entrada['offset'] : 20);
            $json_salida = dumpGames($games);
            break;
        case 'getGamesVoted':
            $user_id = null;
            if (isset($_SESSION['user_id'])) {
                $user_id = $_SESSION['user_id'];
            }
            if (isset($json_entrada['user_id'])) {
                $user_id = $json_entrada['user_id'];
            }
            if ($user_id != null) {
                $games = getGamesVoted($user_id, isset($json_entrada['offset']) ? $json_entrada['offset'] : 20);
                $json_salida = dumpGames($games);
            } else {
                $json_salida['error'] = 'You are not an user or user not especified.';
            }
            break;
        default:
            break;
    }
} else {
    $json_salida['error'] = 'No data recieved.';
}

//ENVIAR
header('Content-Type: application/json');
echo json_encode($json_salida);

function register_vote($user_id, $game_id, $vote) {
//    if ($vote == 0 || $vote == 1) {
    $conexion = conexion();
    $query = "insert into {$config['t_user_votes']} values ($user_id, $game_id," . ($vote ? 1 : 0) . ");";
    $resultado = mysqli_query($conexion, $query);
    $errorNo = mysqli_errno($conexion);
    $errorMsg = mysqli_error($conexion);
//    echo $query;
    //Se produce un error
    if ($errorNo != 0) {
        $salida['error'] = true;
        switch ($errorNo) {
            case 1062:
                $salida['msg'] = 'User has already voted this game.';
                break;
        }
    }
    //Todo bien
    else {
        $salida = array(
            "error" => false,
            "msg" => "OK"
        );
    }
//    return json_encode($json_salida);
    return $salida;
//    }
}

function dumpGames($games = array()) {
    $salida = array();
    foreach ($games as $game) {
        $datos = array();
        $votes = getVoteBalance($game['id']);

        if (isset($_SESSION['user_id'])) {
            $datos['vote']  = getVote($game['id'], $_SESSION['user_id']);
        }
        $datos["id"] = $game['id'];
        $datos["name"] = $game['name'];
        $datos["cover"] = $game['cover'];
        $datos["totalVotes"] = $votes['total'];
        $datos["totalPositiveVotes"] = $votes['positives'];
        $datos["platforms"] = getPlatforms($game['id']);
        
        //Si viene desde el metodo getGamesVoted
        if (isset($game['vote'])) {
            $datos['vote'] = $game['vote'];
        }
        $salida[] = $datos;
    }
    return $salida;
}

//session_start();
//$_SESSION['user_id'] = 1;
//$msg = $_SESSION['user_id'] . " " . $_GET['game_id'] . " " . $_GET['vote'];



//if (isset($_SESSION['user_id']) && isset($_POST['game_id']) && isset($_POST['vote'])) {
//    $user_id = $_SESSION['user_id'];
//    $game_id = $json_entrada['game_id'];
//    $vote = $json_entrada['vote'] ? 1 : 0;
//    $json_salida = register_vote($user_id, $game_id, $vote);
//} else {
//    $json_salida = json_encode("{'error':true,'msg':user not logged in'}");
//}
//echo $msg;
//echo $json_salida;

//ENVIAR
//header('Content-Type: application/json');
// Generar contenidos JSON de respuesta
//$json_salida = array(
//    "error" => "OK",
//    "msg" => ""
//);
//$json_salida["ciudades"][] = 'ciudad';
//$ciudades = $a[$comunidad];
//foreach ($ciudades as $ciudad) {
//    $json_salida["ciudades"][] = $ciudad;
//}
//echo json_encode($json_salida);
