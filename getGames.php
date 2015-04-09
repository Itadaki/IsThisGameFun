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

function getLimit($limit, $offset=0){
    if($offset>0){
        return $limit.','.$offset;
    } else {
        return $limit;
    }
}

/**
 * Devuelve juegos cuyo nombre encaja con el regexp
 * @param type $regexp
 * @param type $offset
 * @return array
 */
function getGamesAlphabetical($regexp, $limit=20, $offset = 0) {
    $limit = getLimit($limit, $offset);
    $query = "select id, name, cover from isthisgamefun.games where name regexp '$regexp' order by name limit $limit;";
    return getGames($query);
}

/**
 * Devuelve un array con los datos de juegos votados
 * [(id, name, cover, vote), ..., (id, name, cover, vote)]
 * @global conection $conexion
 * @param type $user_id
 * @return array
 */
function getGamesVoted($user_id, $limit=20, $offset = 0) {
    global $conexion;
    $limit = getLimit($limit, $offset);
    $query = "select c.name, c.id, c.cover, b.vote from isthisgamefun.users a, isthisgamefun.user_votes b, isthisgamefun.games c where a.user_id = b.user and b.game = c.id and a.user_id = $user_id order by c.id limit $limit;";
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

function getGame($game_id) {
    $query = "select id, name, cover from isthisgamefun.games where id=$game_id;";
    return getGames($query)[0];
}

function getAllGames() {
    $query = "select id, name, cover from isthisgamefun.games order by id asc;";
    return getGames($query);
}

/**
 * Devuelve los ultimos 20 juegos añadidos a la BD en forma de array:<br>
 * [(id, name, cover), ..., (id, name, cover)]
 * @global conection $conexion
 * @param type $offset
 * @return type
 */
function getLatestGames($limit=20, $offset = 0) {
    $limit = getLimit($limit, $offset);
    $query = "select id, name, cover from isthisgamefun.games order by id desc limit $limit;";
    return getGames($query);
}

function getBestGames($limit=20, $offset = 0) {
    $limit = getLimit($limit, $offset);
    $query = "select id, name, cover from isthisgamefun.games, isthisgamefun.user_votes where id = game and vote !=0 group by id order by count(*) desc limit $limit";
    return getGames($query);
}

function getMostVotedGames($limit=20, $offset = 0) {
    $limit = getLimit($limit, $offset);
    $query = "select a.id from games a, user_votes b where a.id = b.game group by a.id limit $limit;";
    return getGames($query);
}

/**
 * Devuelve juegos ordenados por plataforma:<br>
 * Una plataforma: 'N64'.<br>
 * Varias plataformas: 'N64';
 * @param type $platform
 * @param type $offset
 * @return array|NULL
 */
function getGamesByPlatform($platform, $limit=20, $offset = 0) {
    $limit = getLimit($limit, $offset);
    $platform = strtoupper($platform);
    $platforms = getPlatforms();
    $available_platforms = array();
    foreach ($platforms as $p) {
        $available_platforms[] = $p['short_name'];
    }
    if (in_array($platform, $available_platforms)) {
        $query = "select a.id, a.name, a.cover from isthisgamefun.games a, isthisgamefun.game_platform b, isthisgamefun.platforms c  where a.id=b.game and b.platform = c.id and c.short_name = '$platform' limit $limit";
        return getGames($query);
    } else {
        return NULL;
    }
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
        //PLATAFORMAS
        if ($platforms = getPlatforms($game['id'])) {
            $gameInfo['platforms'] = $platforms;
        }

        //SI TIENE BALANCE DE VOTOS
        if ($votes = getVoteBalance($game['id'])) {
            $gameInfo['totalVotes'] = $votes['total'];
            $gameInfo['totalPositiveVotes'] = $votes['positives'];
        }
        //SI HAY SESION SACAR EL VOTO DEL USUARIO A ESTE JUEGO
        if (isset($config['user_id'])) {
            $gameInfo['userVote'] = getVote($game['id'], $config['user_id']);
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
        "positives" => "select count(vote) as votos from isthisgamefun.user_votes where game=$game_id and vote!=0;" //Votos positivos
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
function getPlatforms($game_id = 0) {
    global $conexion;
    if ($game_id) {
        $query = "select id, name, short_name, icon from isthisgamefun.game_platform, isthisgamefun.platforms where game_platform.platform=platforms.id and game_platform.game=$game_id;";
    } else {
        $query = "select id, name, short_name, icon from isthisgamefun.platforms order by name asc;";
    }
    $resultado = mysqli_query($conexion, $query);
    $errorNo = mysqli_errno($conexion);
    if ($errorNo != 0) {
        return array("error" => "$errorNo: " . mysqli_error($conexion), "query" => $query);
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

function getUser($user, $input_type = 'id') {
    global $conexion;
    if ($input_type == 'id') {
        $query = "select user_id, user_nick, user_avatar from isthisgamefun.users where user_id=$user;";
    }
    if ($input_type == 'name') {
        $query = "select user_id, user_nick, user_avatar from isthisgamefun.users where lower(user_name)=lower($user);";
    }
    if ($input_type == 'nick') {
        $query = "select user_id, user_nick, user_avatar from isthisgamefun.users where lower(user_nick) like lower('%$user%');";
    }
    if ($input_type == 'search') {
        $query = "select user_id, user_nick, user_avatar from isthisgamefun.users where lower(user_nick) like lower('%$user%') or lower(user_name) like lower('%$user%');";
    }
    $resultado = mysqli_query($conexion, $query);
    $errorNo = mysqli_errno($conexion);
    if ($errorNo != 0) {
        return array("error" => "$errorNo: " . mysqli_error($conexion));
    }
    $users = array();
    while ($user = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
        $users[] = $user;
    }
    return $users;
}
