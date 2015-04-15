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

include_once './config.php';
include_once './medoo.min.php';

$db_config = [
    'database_type' => 'mysql',
    'database_name' => $config['db_name'],
    'server' => 'localhost',
    'username' => $config['db_user'],
    'password' => $config['db_pass'],
    'charset' => $config['db_charset']
];



$db = new medoo($db_config);
//$db->debug()->select($table, $join);
/* * getGame "select id, name, cover from {$config['t_games']} where id=$game_id;";* */
$columns = ['id', 'name', 'cover'];
$where = ['id' => '$game_id'];
$db->debug()->select($config['t_games'], $columns, $where);
echo'<br>';

//getAllGames "select id, name, cover from {$config['t_games']} order by id asc;"
$columns = ['id', 'name', 'cover'];
$where = ['ORDER' => 'id ASC', 'LIMIT' => '20'];
$db->debug()->select($config['t_games'], $columns, $where);
//Importante las ordenes SQL en mayusculas (p.e. ORDER)
echo'<br>';


//getLatestGames "select id, name, cover from {$config['t_games']} order by id desc limit $limit;";
$columns = ['id', 'name', 'cover'];
$where = ['ORDER' => 'id DESC', 'LIMIT' => '20'];
$db->debug()->select($config['t_games'], $columns, $where);
echo'<br>';


//getBestGames "select id, name, cover from {$config['t_games']}, {$config['t_user_votes']} where id = game and vote !=0 group by id order by count(*) desc limit $limit";
$join = ["[><]{$config['v_game_positive_percentage']}" => ['id' => 'game_id']];
$columns = ['id', 'name', 'cover', 'positive_percentage'];
$where = ["ORDER" => 'positive_percentage DESC', 'LIMIT' => '20'];
$db->debug()->select($config['t_games'], $join, $columns, $where);
echo'<br>';


//getPlatforms "select id, name, short_name, icon from {$config['t_game_platform']}, {$config['t_platforms']} where game_platform.platform=platforms.id and game_platform.game=$game_id;";
$join = ["[><]{$config['t_platforms']}" => ['platform' => 'id']];
$columns = ['id', 'name', 'short_name', 'icon'];
$where = ["game" => '1'];
$db->debug()->select($config['t_game_platform'], $join, $columns, $where);
echo'<br>';



// getVote "select vote from {$config['t_user_votes']} where game=$game_id and user=$user_id;";
$columns = ['vote'];
$where = ["AND" => ["game" => '1', "user" => '1']];
$db->debug()->select($config['t_user_votes'], $columns, $where);
echo'<br>';


//getUser "select user_id, user_nick, user_avatar from {$config['t_users']} where user_id=$user;";
$join = [];
$columns = ['user_id', 'user_nick', 'user_avatar'];
$where = ['user_id' => '1'];
$db->debug()->select($config['t_users'], $columns, $where);
echo'<br>';

//getGamesVoted "select c.name, c.id, c.cover, b.vote from {$config['t_users']} a, {$config['t_user_votes']} b, {$config['t_games']} c where a.user_id = b.user and b.game = c.id and a.user_id = $user_id order by c.id limit $limit;";
$join = [
    "[><]{$config['t_users']}" => ['user' => 'user_id'],
    "[><]{$config['t_games']}" => ['game' => 'id']
];
$columns = ['id', 'name', 'cover', 'vote'];
$where = ['user_id' => '1', 'ORDER' => 'id', 'LIMIT' => '20'];
$db->debug()->select($config['t_user_votes'], $join, $columns, $where);
echo'<br>';


//getVoteBalance
$columns = ['votos_positivos', 'votos_negativos', 'votos_totales'];
$where = ['id' => '1'];
$db->debug()->select($config['v_game_vote_balance'], $columns, $where);
echo'<br>';





//var_dump($db->select('users',[],'*',[],[]));
////////////////////
$config['user_id'] = 1;

var_dump(getAllGames());
//var_dump(getMostVotedGames());
//echo $db->last_query();

/**
 * @desription Devuelve un LIMIT para una query.<br>
 * Hay que pasarle un array con 1 o 2 numeros max
 * @param array $limit
 * @return string
 */
function getLimit($limit = array(20)) {
    if (is_array($limit) && count($limit) == 2 && $limit[1] != 0) {
        return "{$limit[0]},{$limit[1]}";
    } else if (is_int($limit)) {
        return $limit[0];
    } else {
        return "";
    }
}

/**
 * Devuelve juegos cuyo nombre encaja con el regexp
 * @param type $regexp
 * @param type $offset
 * @return array
 */
function getGamesAlphabetical($regexp, $limit = 20, $offset = 0) {
    global $config;
    $limit = getLimit($limit, $offset);
    $query = "select id, name, cover from {$config['t_games']} where name regexp '$regexp' order by name limit $limit;";
    return getGames($query);
}

/**
 * Devuelve un array con los datos de juegos votados
 * [(id, name, cover, vote), ..., (id, name, cover, vote)]
 * @global conection $conexion
 * @param type $user_id
 * @return array
 */
function getGamesVoted($user_id, $limit = 20, $offset = 0) {
    global $db, $config;
//    $limit = getLimit($limit, $offset);
    $join = [
        "[><]{$config['t_users']}" => ['user' => 'user_id'],
        "[><]{$config['t_games']}" => ['game' => 'id']
    ];
    $columns = ['id', 'name', 'cover', 'vote(userVote)'];
    $where = ['user_id' => $user_id, 'ORDER' => 'id', 'LIMIT' => $limit];
//    $games = $db->select($config['t_user_votes'], $join, $columns, $where);
//    $error = $db->error();
//    $info = array();
//    if ($error[0] == '00000') {
//        foreach ($games as $pos => $game) {
////            foreach ($game as $campo => $valor) {
////                $info[$pos][$campo]=$valor;
////            }
//            $info[$pos]['id'] = $game['id'];
//            $info[$pos]['name'] = $game['name'];
//            $info[$pos]['cover'] = $game['cover'];
//            $info[$pos]['userVote'] = $game['vote'];
//            if (isset($config['user_id'])) {
//                $info[$pos]['myVote'] = getVote($game['id'], $config['user_id']);
//            }
//        }
//    }
    $table = $config['t_user_votes'];
    $info = getGames($table, $columns, $where, $join);
    return $info;
}

function getGame($game_id) {
    global $db, $config;
    $colummns = ['id', 'name', 'cover'];
    $where = ['id' => $game_id];
    $resultado = $db->get($config['t_games'], $colummns, $where);
    $error = $db->error();
    if ($error[0] == '00000') {
        $resultado['platforms'] = getPlatforms($resultado['id']);
        $resultado['vote_valance'] = getVoteBalance($game['id']);
        if (isset($config['user_id'])) {
            $resultado['myVote'] = getVote($resultado['id'], $config['user_id']);
        }
        return $resultado;
    } else {
        return null;
    }
}

function getAllGames() {
    global $db, $config;
    $columns = ['id', 'name', 'cover'];
    $where = ['ORDER' => 'id ASC', 'LIMIT' => '20'];
//    $resultados = $db->select($config['t_games'], $columns, $where);
//    $error = $db->error();
//    $info = [];
//    if ($error[0] == '00000') {
//        foreach ($resultados as $pos => $game) {
//            $info[$pos]['id'] = $game['id'];
//            $info[$pos]['name'] = $game['name'];
//            $info[$pos]['cover'] = $game['cover'];
//            if (isset($config['user_id'])) {
//                $info[$pos]['myVote'] = getVote($game['id'], $config['user_id']);
//            }
//        }
//        return $info;
//    }
    $table = $config['t_games'];
    $info = getGames($table, $columns, $where);
    return $info;
}

/**
 * Devuelve los ultimos 20 juegos añadidos a la BD en forma de array:<br>
 * [(id, name, cover), ..., (id, name, cover)]
 * @global conection $conexion
 * @param type $offset
 * @return type
 */
function getLatestGames($limit = 20, $offset = 0) {
    global $db, $config;
    $columns = ['id', 'name', 'cover'];
    $where = ['ORDER' => 'id DESC', 'LIMIT' => '20'];
//    $resultados = $db->select($config['t_games'], $columns, $where);
//    $error = $db->error();
//    $info = [];
//    if ($error[0] == '00000') {
//        foreach ($resultados as $pos => $game) {
//            $info[$pos]['id'] = $game['id'];
//            $info[$pos]['name'] = $game['name'];
//            $info[$pos]['cover'] = $game['cover'];
//            if (isset($config['user_id'])) {
//                $info[$pos]['myVote'] = getVote($game['id'], $config['user_id']);
//            }
//        }
//        return $info;
//    }
    $table = $config['t_games'];
    $info = getGames($table, $columns, $where);
    return $info;
}

function getBestGames($limit = 20, $offset = 0) {
    global $db, $config;
    $join = ["[><]{$config['v_game_positive_percentage']}" => ['id' => 'game_id']];
    $columns = ['id', 'name', 'cover', 'positive_percentage'];
    $where = ["ORDER" => 'positive_percentage DESC', 'LIMIT' => '20'];
//    $resultados = $db->select($config['t_games'], $join, $columns, $where);
//    $error = $db->error();
//    $info = [];
//    if ($error[0] == '00000') {
//        foreach ($resultados as $pos => $game) {
//            $info[$pos]['id'] = $game['id'];
//            $info[$pos]['name'] = $game['name'];
//            $info[$pos]['cover'] = $game['cover'];
//            $info[$pos]['positive_percentage'] = $game['positive_percentage'];
//            if (isset($config['user_id'])) {
//                $info[$pos]['myVote'] = getVote($game['id'], $config['user_id']);
//            }
//        }
//        return $info;
//    }
    $table = $config['t_games'];
    $info = getGames($table, $columns, $where, $join);
    return $info;
}

function getMostVotedGames($limit = 20, $offset = 0) {
    global $db, $config;
    $resultados = $db->query("SELECT id, name, cover, count(*) as votes from {$config['t_games']}, {$config['t_user_votes']} where id=user group by id order by count(*) desc")->fetchAll();
    $error = $db->error();
    $info = [];
    if ($error[0] == '00000') {
        foreach ($resultados as $pos => $game) {
            $info[$pos]['id'] = $game['id'];
            $info[$pos]['name'] = $game['name'];
            $info[$pos]['cover'] = $game['cover'];
            $info[$pos]['votes'] = $game['votes'];
            if (isset($config['user_id'])) {
                $info[$pos]['myVote'] = getVote($game['id'], $config['user_id']);
            }
        }
        return $info;
    }
}

/**
 * Devuelve juegos ordenados por plataforma:<br>
 * Una plataforma: 'N64'.<br>
 * Varias plataformas: 'N64';
 * @param type $platform
 * @param type $offset
 * @return array|NULL
 */
function getGamesByPlatform($platform, $limit = 20, $offset = 0) {
    global $config;
    $limit = getLimit($limit, $offset);
    $platform = strtoupper($platform);
    $platforms = getPlatforms();
    $available_platforms = array();
    foreach ($platforms as $p) {
        $available_platforms[] = $p['short_name'];
    }
    if (in_array($platform, $available_platforms)) {
        $query = "select a.id, a.name, a.cover from {$config['t_games']} a, {$config['t_game_platform']} b, {$config['t_platforms']} c  where a.id=b.game and b.platform = c.id and c.short_name = '$platform' limit $limit";
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
function getGames($table, $columns = '*', $where = NULL, $join = NULL) {
    global $db, $config;
    $resultados = null;
    $error = $db->error();
    $info = [];
    if ($error[0] == '00000') {
        if (isset($join)) {
            $resultados = $db->select($table, $join, $columns, $where);
        } else {
            $resultados = $db->select($table, $columns, $where);
        }
        echo $db->last_query();
        if ($resultados) {
            foreach ($resultados as $pos => $game) {
                foreach ($game as $campo => $valor) {
                    $info[$pos][$campo] = $valor;
                }
                $info[$pos]['platforms'] = getPlatforms($game['id']);
                $info[$pos]['vote_valance'] = getVoteBalance($game['id']);
                $info[$pos]['saga'] = getSaga($game['id']);
                if (isset($config['user_id'])) {
                    $info[$pos]['myVote'] = getVote($game['id'], $config['user_id']);
                }
            }
        }
        return $info;
    } else {
        var_dump($error);
        return null;
    }
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
    global $db, $config;
    $columns = ['votos_positivos', 'votos_negativos', 'votos_totales'];
    $where = ['id' => $game_id];
    $resultados = $db->select($config['v_game_vote_balance'], $columns, $where);
    $info = [];
    $error = $db->error();
    if ($error[0] == '00000') {
        foreach ($resultados[0] as $campo => $valor) {
            $info[$campo] = $valor;
        }
    }
    return $info;




//    global $conexion, $config;
//    $votos = array();
//    $querys = array(
//        "total" => "select count(vote) as votos from {$config['t_user_votes']} where game=$game_id;", //Votos totales
//        "positives" => "select count(vote) as votos from {$config['t_user_votes']} where game=$game_id and vote!=0;" //Votos positivos
//    );
//    $errorNo = '';
//    $errorMsg = '';
//    foreach ($querys as $tipo => $query) {
//        $resultado = mysqli_query($conexion, $query);
//        if ($errorNo = mysqli_errno($conexion) != 0) {
//            $errorMsg = mysqli_error($conexion);
//            echo "Error sacando los votos totales:<br>$errorNo=$errorMsg en la query $query";
//            break;
//        }
//        $votos[$tipo] = mysqli_fetch_array($resultado, MYSQLI_ASSOC)['votos'];
//    }
//    return $votos;
}

/**
 * Devulve el voto del usuario a un juego, o NULL si no ha votado
 * @global conection $conexion
 * @param type $game_id
 * @param type $user_id
 * @return type
 */
function getVote($game_id, $user_id) {
    global $db, $config;
    $colummns = ['vote'];
    $where = ["AND" => ["game" => $game_id, "user" => $user_id]];
    $resultado = $db->select($config['t_user_votes'], $colummns, $where);
    $error = $db->error();
    if ($error[0] == '00000') {
        if (!empty($resultado)) {
            return $resultado[0]['vote'];
        } else {
            return null;
        }
    }

//
//    $query = "select vote from {$config['t_user_votes']} where game=$game_id and user=$user_id;";
//    $resultado = mysqli_query($conexion, $query);
//    $errorNo = mysqli_errno($conexion);
//    if ($errorNo != 0) {
//        return array("error" => "$errorNo: " . mysqli_error($conexion));
//    }
//    return mysqli_fetch_array($resultado, MYSQLI_ASSOC)['vote'];
}

/**
 * Devuelve un array con todas las plataformas de un juego y sus datos
 * @global conection $conexion
 * @param type $game_id
 * @return array
 */
function getPlatforms($game_id = 0) {
    global $db, $config;
    $join = ["[><]{$config['t_platforms']}" => ['platform' => 'id']];
    $columns = ['id', 'name', 'short_name', 'icon'];
    $where = ["game" => $game_id];
    $resultados = $db->select($config['t_game_platform'], $join, $columns, $where);
    return $resultados;
//    global $conexion, $config;
//    if ($game_id) {
//        $query = "select id, name, short_name, icon from {$config['t_game_platform']}, {$config['t_platforms']} where game_platform.platform=platforms.id and game_platform.game=$game_id;";
//    } else {
//        $query = "select id, name, short_name, icon from {$config['t_platforms']} order by name asc;";
//    }
//    $resultado = mysqli_query($conexion, $query);
//    $errorNo = mysqli_errno($conexion);
//    if ($errorNo != 0) {
//        return array("error" => "$errorNo: " . mysqli_error($conexion), "query" => $query);
//    }
//    $platforms = array();
//    while ($platform = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
//        $platforms[] = array(
//            "id" => $platform["id"],
//            "name" => $platform["name"],
//            "short_name" => $platform["short_name"],
//            "icon" => $platform["icon"]
//        );
//    }
//    return $platforms;
}

function getUser($user, $input_type = 'id') {
    global $conexion, $config;
    if ($input_type == 'id') {
        $query = "select user_id, user_nick, user_avatar from {$config['t_users']} where user_id=$user;";
    }
    if ($input_type == 'name') {
        $query = "select user_id, user_nick, user_avatar from {$config['t_users']} where lower(user_name)=lower($user);";
    }
    if ($input_type == 'nick') {
        $query = "select user_id, user_nick, user_avatar from {$config['t_users']} where lower(user_nick) like lower('%$user%');";
    }
    if ($input_type == 'search') {
        $query = "select user_id, user_nick, user_avatar from {$config['t_users']} where lower(user_nick) like lower('%$user%') or lower(user_name) like lower('%$user%');";
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

function getSaga($game_id) {
    global $db, $config;
    $join = ["[><]{$config['t_sagas']}" => ['saga' => 'id']];
    $columns = ['id', 'name', 'logo'];
    $where = ["game" => $game_id];
    $resultados = $db->select($config['t_game_saga'], $join, $columns, $where);
    $error = $db->error();
    if ($error[0] == '00000' && !empty($resultados)) {
        $resultados[0]['vote_balance'] = getSagaValanceVote($resultados[0]['id']);
        return $resultados[0];
    } else {
        return NULL;
    }
}

function getSagaValanceVote($saga_id){
    global $db, $config;
    $columns = ['votos_positivos', 'votos_negativos', 'votos_totales'];
    $where = ['id' => $saga_id];
    $resultados = $db->select($config['v_saga_vote_balance'], $columns, $where);
    $info = [];
    $error = $db->error();
    if ($error[0] == '00000') {
        foreach ($resultados[0] as $campo => $valor) {
            $info[$campo] = $valor;
        }
    }
    return $info;
}
