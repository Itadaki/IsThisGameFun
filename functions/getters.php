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

//$config['user_id'] = 1;
//Set the medoo DB connection
//header('Content-Type: application/json');
//echo json_encode(getAllGames());
//var_dump($db->select('users',[],'*',[],[]));
////////////////////
//d('s', getAllGames());
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
    $query = "select id, name, description, cover from {$config['t_games']} where name regexp '$regexp' order by name limit $limit;";
    return getGames($query);
}

/**
 * Devuelve juegos cuyo nombre encaja con el regexp
 * @param type $regexp
 * @param type $offset
 * @return array
 */
function getGamesAlphabetically($limit = 20, $offset = 0) {
    global $db, $config;
    $columns = ['id', 'name', 'description', 'cover'];
    $where = ['ORDER' => 'name ASC', 'LIMIT' => $limit];
    $table = $config['t_games'];
    $info = getGames($table, $columns, $where);
    return $info;
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
    $columns = ['id', 'name', 'description', 'cover', 'vote(user_vote)'];
    $where = ['user_id' => $user_id, 'ORDER' => 'id', 'LIMIT' => $limit];
    $table = $config['t_user_votes'];
    $info = getGames($table, $columns, $where, $join);
    return $info;
}

function getGame($game_id) {
    global $db, $config;
    $colummns = ['id', 'name', 'description', 'cover'];
    $where = ['id' => $game_id];
    $game = $db->get($config['t_games'], $colummns, $where);
    $error = $db->error();
    if ($error[0] == '00000' && $game) {
        $g = new Game($game_id, $game['name'], $game['description'], $game['cover'], getVoteBalance($game['id']), getPlatforms($game['id']), getSaga($game_id));
//        $data[$index]['id'] = $game['id'];
//        $data[$index]['name'] = $game['name'];
//        $data[$index]['cover'] = $game['cover'];
//        $data[$index]['platforms'] = getPlatforms($game['id']);
//        $data[$index]['vote_valance'] = getVoteBalance($game['id']);
//        $data[$index]['saga'] = getSaga($game['id']);
        if (isset($config['user_id'])) {
            $g->my_vote = (getVote($game['id'], $config['user_id']));
        }
        return $g;
    } else {
        return null;
    }
}

function getAllGames($limit = 20, $offset = 0) {
    global $db, $config;
    $columns = ['id', 'name', 'description', 'cover'];
    $where = ['ORDER' => 'id ASC', 'LIMIT' => $limit];
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
    $columns = ['id', 'name', 'description', 'cover'];
    $where = ['ORDER' => 'id DESC', 'LIMIT' => $limit];
    $table = $config['t_games'];
    $info = getGames($table, $columns, $where);
    return $info;
}

function getBestGames($limit = 20, $offset = 0) {
    global $db, $config;
    $join = ["[><]{$config['v_game_positive_percentage']}" => ['id' => 'game_id']];
    $columns = ['id', 'name', 'description', 'cover', 'positive_percentage'];
    $where = ["ORDER" => 'positive_percentage DESC', 'LIMIT' => $limit];
    $table = $config['t_games'];
    $info = getGames($table, $columns, $where, $join);
    return $info;
}

function getMostVotedGames($limit = 20, $offset = 0) {
    global $db, $config;
    $resultados = $db->query("SELECT id, name, description, cover, count(*) as votes from {$config['t_games']}, {$config['t_user_votes']} where id=user group by id order by count(*) desc limit $limit")->fetchAll();
    $error = $db->error();
    $info = [];
    if ($error[0] == '00000') {
        foreach ($resultados as $pos => $game) {
            $info[$pos]['id'] = $game['id'];
            $info[$pos]['name'] = $game['name'];
            $info[$pos]['description'] = $game['description'];
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
    global $db;
    $resultados = null;
    $error = $db->error();
    $info = [];
    if ($error[0] == '00000') {
        if (isset($join)) {
            $resultados = $db->select($table, $join, $columns, $where);
        } else {
            $resultados = $db->select($table, $columns, $where);
        }
        $error = $db->error();
        if ($resultados) {
            $info = fetchGames($resultados);
        }
        return $info;
    } else {
        var_dump($error);
        return null;
    }
}

function fetchGames($games) {
    global $config;
    $data = [];
    foreach ($games as $game) {
        $g = new Game($game['id'], $game['name'], $game['description'], $game['cover'], getVoteBalance($game['id']), getPlatforms($game['id']), getSaga($game['id']));
//        $data[$index]['id'] = $game['id'];
//        $data[$index]['name'] = $game['name'];
//        $data[$index]['cover'] = $game['cover'];
//        $data[$index]['platforms'] = getPlatforms($game['id']);
//        $data[$index]['vote_valance'] = getVoteBalance($game['id']);
//        $data[$index]['saga'] = getSaga($game['id']);
        if (isset($config['user_id'])) {
            $g->my_vote = (getVote($game['id'], $config['user_id']));
        }
        $data[] = $g;
    }
    return $data;
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
    $balance = new VoteBalance();
    $error = $db->error();
    if ($error[0] == '00000' && !empty($resultados)) {
        $balance = new VoteBalance($resultados[0]['votos_positivos'], $resultados[0]['votos_negativos']);
    }
    return $balance;
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
            return (int) $resultado[0]['vote'];
        } else {
            return null;
        }
    }
}

/**
 * Devuelve un array con todas las plataformas de un juego y sus datos.
 * Sin id, devuelve todas las plataformas
 * @global conection $conexion
 * @param type $game_id
 * @return array
 */
function getPlatforms($game_id = null) {
    global $db, $config;
    $join = ["[><]{$config['t_platforms']}" => ['platform' => 'id']];
    $columns = ['id', 'name', 'short_name', 'icon'];
    if ($game_id != null) {
        $where = ["game" => $game_id];
        $resultados = $db->select($config['t_game_platform'], $join, $columns, $where);
    } else {
        $resultados = $db->select($config['t_platforms'], $columns);
    }
//    $resultados = $db->select($config['t_game_platform'], $join, $columns, $where);
    $platforms = [];
    foreach ($resultados as $resultado) {
        $platforms[] = new Platform($resultado['id'], $resultado['name'], $resultado['short_name'], $resultado['icon']);
    }
//    return $resultados;
    return $platforms;
}

function getSagaById($saga_id) {
    global $db, $config;
    $columns = ['id', 'name', 'description', 'logo'];
    $where = ["id" => $saga_id];
    $resultados = $db->get($config['t_sagas'], $columns, $where);
    $error = $db->error();
    if ($error[0] == '00000' && !empty($resultados) && $saga_id) {
        $saga = new Saga($resultados['id'], $resultados['name'], $resultados['description'], $resultados['logo'], getSagaVoteBalance($resultados['id']));
        return $saga;
    } else {
        return null;
    }
}

function getSaga($game_id = null) {
    global $db, $config;
    $join = ["[><]{$config['t_sagas']}" => ['saga' => 'id']];
    $columns = ['id', 'name', 'description', 'logo'];
    if ($game_id != null) {
        $where = ["game" => $game_id];
        $resultados = $db->get($config['t_game_saga'], $join, $columns, $where);
    } else {
        $resultados = $db->select($config['t_sagas'], $columns);
    }
    $error = $db->error();
//    var_dump($resultados);
//    echo (empty($resultados)?"VACIO":"LLENO")."<br>";
    if ($error[0] == '00000' && !empty($resultados) && $game_id != null) {
        $saga = new Saga($resultados['id'], $resultados['name'], $resultados['description'], $resultados['logo'], getSagaVoteBalance($resultados['id']));
        return $saga;
    } else if (!empty($resultados) && $game_id == null) {
        $sagas = [];
        foreach ($resultados as $saga) {
            $sagas[] = new Saga($saga['id'], $saga['name'], $saga['description'], $saga['logo'], getSagaVoteBalance($saga['id']));
        }
        return $sagas;
    } else {
        return null;
    }
}

function getSagaVoteBalance($saga_id) {
    global $db, $config;
    $columns = ['votos_positivos', 'votos_negativos', 'votos_totales'];
    $where = ['id' => $saga_id];
    $resultados = $db->select($config['v_saga_vote_balance'], $columns, $where);
    $balance = new VoteBalance();
    $error = $db->error();
    if ($error[0] == '00000' && !empty($resultados)) {
        $balance = new VoteBalance($resultados[0]['votos_positivos'], $resultados[0]['votos_negativos']);
    }
    return $balance;
}

function getEnumUserLevel(){
    global $config, $db;
    $resultados = $db->query("SHOW COLUMNS FROM {$config['t_users']} WHERE Field = 'user_level'")->fetchAll()[0];
    $type = $resultados['Type'];
    preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
    $enum = explode("','", $matches[1]);
    return $enum;
}