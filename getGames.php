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

/**
 * Devuelve juegos cuyo nombre encaja con el regexp
 * @param type $regexp
 * @param type $offset
 * @return array
 */
function getGamesAlphabetical($regexp, $offset = 20) {
    $query = "select id, name, cover from isthisgamefun.games where name regexp '$regexp' order by name limit $offset;";
    return getGames($query);
}

/**
 * Devuelve un array con los datos de juegos votados
 * [(id, name, cover, vote), ..., (id, name, cover, vote)]
 * @global conection $conexion
 * @param type $user_id
 * @return array
 */
function getGamesVoted($user_id, $offset = 20) {
    global $conexion;
    $query = "select c.name, c.id, c.cover, b.vote from isthisgamefun.users a, isthisgamefun.user_votes b, isthisgamefun.games c
where a.user_id = b.user and b.game = c.id and a.user_id = $user_id order by c.id limit $offset;";
    $resultado = mysqli_query($conexion, $query);
    $errorNo = mysqli_errno($conexion);
    $errorMsg = mysqli_error($conexion);
    $info = array();
    while ($game = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
        $info[] = array(
            "id" => $game['id'],
            "name" => $game['name'],
            "cover" => $game['cover'],
            "vote" => $game['vote']
        );
    }
    return $info;
}

/**
 * Devuelve los ultimos 20 juegos añadidos a la BD en forma de array:<br>
 * [(id, name, cover), ..., (id, name, cover)]
 * @global conection $conexion
 * @param type $offset
 * @return type
 */
function getLatestGames($offset = 20) {
    $query = "select id, name, cover from isthisgamefun.games order by id desc limit $offset;";
    return getGames($query);
}

function getBestGames($offset = 20) {
    $query = "select id, name, cover from isthisgamefun.games, isthisgamefun.user_votes where id = game and vote !=0 group by id order by count(*) desc limit $offset";
    return getGames($query);
}

function getMostVotedGames($offset = 20) {
    $query = "select a.id from games a, user_votes b where a.id = b.game group by a.id limit $offset;";
    return getGames($query);
}

/**
 * Para atacar la base de datos de juegos<br>
 * Devuelve el array [(id, name, cover), ..., (id, name, cover)]
 * @global conection $conexion
 * @param type $query
 * @return array
 */
function getGames($query) {
    global $conexion, $config;
    $resultado = mysqli_query($conexion, $query);
    $errorNo = mysqli_errno($conexion);
    $errorMsg = mysqli_error($conexion);
//    echo "getGames: $errorNo-$errorMsg";
    $info = array();
    while ($game = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
        $gameInfo = array();
        $gameInfo["id"] = $game['id'];
        $gameInfo['name'] = $game['name'];
        $gameInfo['cover'] = $game['cover'];
        //SI TIENE BALANCE DE VOTOS
        if ($votes = getVoteBalance($game['id'])) {
            $gameInfo['totalVotos'] = $votes['total'];
            $gameInfo['totalPositivos'] = $votes['positivos'];
        }
        //SI HAY SESION SACAR EL VOTO DEL USUARIO A ESTE JUEGO
        if (isset($config['user_id'])) {
            $gameInfo['vote'] = getVote($game['id'], $config['user_id']);
        }
        $info[] = $gameInfo;
    }
    return $info;
}

/**
 * Devuelve un array con el total de votos y votos positivos de un juego.<br>
 * Si el id no existe devuelve array de 0's
 * indices: total, positivo
 * @global conection $conexion
 * @param int $game_id
 * @return array
 */
function getVoteBalance($game_id) {
    global $conexion;
    $votos = array();
    $querys = array(
        "total" => "select count(vote) as votos from isthisgamefun.user_votes where game=$game_id;", //Votos totales
        "positivos" => "select count(vote) as votos from isthisgamefun.user_votes where game=$game_id and vote!=0;" //Votos positivos
    );
    $errorNo = '';
    $errorMsg = '';
    foreach ($querys as $tipo => $query) {
        $resultado = mysqli_query($conexion, $query);
        if ($errorNo = mysqli_errno($conexion) != 0) {
            $errorMsg = mysqli_error($conexion);
            echo "Error sacando los votos totales:<br>$errorNo=$errorMsg en la query $query";
            break;
        }
        $votos[$tipo] = mysqli_fetch_array($resultado, MYSQLI_ASSOC)['votos'];
    }
    return $votos;
}

/**
 * Devulve el voto del usuario a un juego, o NULL si no ha votado
 * @global conection $conexion
 * @param type $game_id
 * @param type $user_id
 * @return type
 */
function getVote($game_id, $user_id) {
    global $conexion;
    $query = "select vote from isthisgamefun.user_votes where game=$game_id and user=$user_id;";
    $resultado = mysqli_query($conexion, $query);
    $errorNo = mysqli_errno($conexion);
    if ($errorNo != 0) {
        return array("error" => "$errorNo: " . mysqli_error($conexion));
    }
    return mysqli_fetch_array($resultado, MYSQLI_ASSOC)['vote'];
}

/**
 * Devuelve un array con todas las plataformas de un juego y sus datos
 * @global conection $conexion
 * @param type $game_id
 * @return array
 */
function getPlatforms($game_id) {
    global $conexion;
    $query = "select id, name, short_name, icon from isthisgamefun.game_platform, isthisgamefun.platforms where game_platform.platform=platforms.id and game_platform.game=$game_id;";
    $resultado = mysqli_query($conexion, $query);
    $errorNo = mysqli_errno($conexion);
    if ($errorNo != 0) {
        return array("error" => "$errorNo: " . mysqli_error($conexion));
    }
    $platforms = array();
    while ($platform = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
        $platforms[] = array(
            "id" => $platform["id"],
            "name" => $platform["name"],
            "short_name" => $platform["short_name"],
            "icon" => $platform["icon"]
        );
    }
    return $platforms;
}
