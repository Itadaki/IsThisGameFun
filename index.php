<?php

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
include_once './conexion.php';
include_once 'getGames.php';

if (isset($_COOKIE['allow_cookies'])) {
    session_start();
    echo "<h3>SESSION</h3>";
    var_dump($_SESSION);
    if (isset($_SESSION['user_id'])){
        $config['user_id'] = $_SESSION['user_id'];
    }
}
if(isset($config['user_id']))
    echo "<h1>USER_ID: {$config['user_id']}</h1>";
echo "<h3>COOKIE</h3>";
var_dump($_COOKIE);

echo "<h3>GET</h3>";
var_dump($_GET);


//CONTROLADOR

if (isset($_GET['section'])) {
    $section = $_GET['section'];
    switch ($section) {
        case 'games':
            break;
        case 'user':
            break;
        case 'login':
            break;
        case 'register':
            break;
        case 'sitemap':
            break;
        case 'about':
            break;
        case 'contact':
            break;
        case 'cookies':
            break;
        default:
            break;
    }
} else {
    
}

function displayGames($order = 'latest') {
    switch ($order) {
        case 'latest':
            $games = getLatestGames();
            break;
        case 'best':
            $games = getBestGames();
            break;
        case 'top':

            break;
        default:
            break;
    }
}

$conexion = conexion();
//$latestGames = getLatestGames(5);
//$salida = '';
//foreach ($latestGames as $game) {
//    $votes = getVoteBalance($game['id']);
//    $datos = array(
//        "id" => $game['id'],
//        "name" => $game['name'],
//        "cover" => $game['cover'],
//        "totalVotos" => $votes['total'],
//        "totalPositivos" => $votes['positivos'],
//        "vote" => ''
//    );
//    $plantilla = "plantillas/pastilla.html";
//    $salida .= respuesta($datos, $plantilla);
//}
//echo $salida;
//
////var_dump(getLatestGames());
////var_dump(getVoteBalance(1));
//
//var_dump(getGamesVoted(1));
//
//var_dump(getGamesAlphabetical('^a+'));
//
//var_dump(getVote(1, 1));

echo "<h3>Best games</h3>";
//var_dump(getBestGames());
$s='<h1>Query de votos</h1>';
for ($i = 0; $i < 1; $i++) {
    $user = rand(1, 94);
    $game = rand(1, 89);
    $vote = rand(0, 1)?"true":"false";
    $s.="INSERT IGNORE INTO `isthisgamefun`.`user_votes` (`user`, `game`, `vote`) VALUES ($user, $game, $vote);<br>";
}
echo $s;


function getUser($user_id=''){
    if (empty($user_id)){
        return null;
    }
    var_dump(getGamesVoted($user_id));
}
getUser(50);