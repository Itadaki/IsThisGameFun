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
 * @deprecated since version 2
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
 * Return games ordered by name
 * @param int $limit How many games to return
 * @param int $offset Starting from which position
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
 * Returns an array with the games voted by user
 * @param type $user_id ID from the user
 * @param int $limit How many games to return
 * @param int $offset Starting from which position
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

/**
 * Returns a game specified by id
 * @param type $game_id Game ID
 * @return Game
 */
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

/**
 * Return games ordered by id
 * @param int $limit How many games to return
 * @param int $offset Starting from which position
 * @return array
 */
function getAllGames($limit = 20, $offset = 0) {
    global $db, $config;
    $columns = ['id', 'name', 'description', 'cover'];
    $where = ['ORDER' => 'id ASC', 'LIMIT' => $limit];
    $table = $config['t_games'];
    $info = getGames($table, $columns, $where);
    return $info;
}

/**
 * Return games ordered by ID desc. (new->older)
 * @param int $limit How many games to return
 * @param int $offset Starting from which position
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

/**
 * Return games ordered by Positive Percentage
 * @param int $limit How many games to return
 * @param int $offset Starting from which position
 * @return type
 */
function getBestGames($limit = 20, $offset = 1) {
    global $db;
//    $join = ["[><]{$config['v_game_positive_percentage']}" => ['id' => 'game_id']];
//    $columns = ['id', 'name', 'description', 'cover', 'positive_percentage'];
//    $where = ["ORDER" => 'positive_percentage DESC', 'LIMIT' => $limit];
//    $table = $config['t_games'];

    $resultados = $db->query("call getGamesOrderByPositive($offset, $limit)")->fetchAll();
    if ($resultados) {
        $info = fetchGames($resultados);
    }
//    $info = getGames($table, $columns, $where, $join);
    return $info;
}

/**
 * Return games ordered number of votes
 * @deprecated since version 1
 * @param int $limit How many games to return
 * @param int $offset Starting from which position
 * @return type
 */
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
 * Devuelve juegos ordenados por plataforma
 * @deprecated since version 1
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
 * Executes the query against db
 * @param type $table The table name
 * @param type $columns Array with column names | '*'
 * @param type $where Array with conditions
 * @param type $join Array with join data
 * @return array
 */
function getGames($table, $columns = '*', $where = NULL, $join = NULL) {
    global $db;
    $resultados = null;
    handleDbError();
    $info = [];
    if (isset($join)) {
        $resultados = $db->select($table, $join, $columns, $where);
    } else {
        $resultados = $db->select($table, $columns, $where);
    }
    handleDbError();
    if ($resultados) {
        $info = fetchGames($resultados);
    }
    return $info;
}

/**
 * Fecths each game from bd query into array<br>
 * Generates a Game object array
 * @param type $table The table name
 * @param type $columns Array with column names | '*'
 * @param type $where Array with conditions
 * @param type $join Array with join data
 * @return Game[]
 */
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
 * Returns a VoteBalance object based on game Id
 * @global conection $conexion
 * @param int $game_id
 * @return array
 */
function getVoteBalance($game_id) {
    global $db;
//    $columns = ['votos_positivos', 'votos_negativos', 'votos_totales'];
//    $where = ['id' => $game_id];
//    $resultados = $db->select($config['v_game_vote_balance'], $columns, $where);
    $resultados = $db->query("call getGameVoteBalance($game_id)")->fetchAll();
    handleDbError();
    $balance = new VoteBalance();

    if ($resultados) {
        $balance = new VoteBalance($resultados[0]['votos_positivos'], $resultados[0]['votos_negativos']);
    }
    return $balance;
}

/**
 * Returns the user vote for a game
 * @param type $game_id Id of the game
 * @param type $user_id Id of the user
 * @return int|null
 */
function getVote($game_id, $user_id) {
    global $db, $config;
    $colummns = ['vote'];
    $where = ["AND" => ["game" => $game_id, "user" => $user_id]];
    $resultado = $db->select($config['t_user_votes'], $colummns, $where);
    handleDbError();
    if (!empty($resultado)) {
        return (int) $resultado[0]['vote'];
    }
}

/**
 * Returns an array of Platform objects of a game<br>
 * If no Id is especified returns all platforms from db
 * @param int $game_id The Id of the game
 * @return Platform[]
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

    handleDbError();

    $platforms = [];
    foreach ($resultados as $resultado) {
        $platforms[] = new Platform($resultado['id'], $resultado['name'], $resultado['short_name'], $resultado['icon']);
    }
//    return $resultados;
    return $platforms;
}

/**
 * Returns a Saga object based on ID
 * @param int $saga_id The Id of the Saga
 * @return Saga
 */
function getSagaById($saga_id) {
    global $db, $config;
    $columns = ['id', 'name', 'description', 'logo'];
    $where = ["id" => $saga_id];
    $resultados = $db->get($config['t_sagas'], $columns, $where);
    handleDbError();
    if (!empty($resultados) && $saga_id) {
        $saga = new Saga($resultados['id'], $resultados['name'], $resultados['description'], $resultados['logo']/* , getSagaVoteBalance($resultados['id']) */);
        return $saga;
    } else {
        return null;
    }
}

/**
 * Returns an array of Saga objects of a game
 * @param int $game_id The Id of the Game
 * @return Saga[]
 */
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
    handleDbError();
//    var_dump($resultados);
//    echo (empty($resultados)?"VACIO":"LLENO")."<br>";
    if ($resultados) {
        if ($game_id != null) {
            $saga = new Saga($resultados['id'], $resultados['name'], $resultados['description'], $resultados['logo']/* , getSagaVoteBalance($resultados['id']) */);
            return $saga;
        } else if ($game_id == null) {
            $sagas = [];
            foreach ($resultados as $saga) {
                $sagas[] = new Saga($saga['id'], $saga['name'], $saga['description'], $saga['logo']/* , getSagaVoteBalance($saga['id']) */);
            }
            return $sagas;
        }
    }
    return null;
}

//function getSagaVoteBalance($saga_id) {
//    global $db, $config;
//    $columns = ['votos_positivos', 'votos_negativos', 'votos_totales'];
//    $where = ['id' => $saga_id];
//    $resultados = $db->select($config['v_saga_vote_balance'], $columns, $where);
//    $balance = new VoteBalance();
//    $error = $db->error();
//    if ($error[0] == '00000' && !empty($resultados)) {
//        $balance = new VoteBalance($resultados[0]['votos_positivos'], $resultados[0]['votos_negativos']);
//    }
//    return $balance;
//}

/**
 * Returns the different user levels based on the ENUM condition of db
 * @return array
 */
function getEnumUserLevel() {
    global $config, $db;
    $resultados = $db->query("SHOW COLUMNS FROM {$config['t_users']} WHERE Field = 'user_level'")->fetchAll()[0];
    handleDbError();
    $type = $resultados['Type'];
    preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
    $enum = explode("','", $matches[1]);
    return $enum;
}

//function getVoteActivity($user_id) {
//    global $db, $config;
//    $columns = ['votos_positivos', 'votos_negativos', 'votos_totales'];
//    $where = ['id' => $saga_id];
//    $resultados = $db->select($config['v_saga_vote_balance'], $columns, $where);
//    $balance = new VoteBalance();
//    $error = $db->error();
//    if ($error[0] == '00000' && !empty($resultados)) {
//        $balance = new VoteBalance($resultados[0]['votos_positivos'], $resultados[0]['votos_negativos']);
//    }
//    return $balance;
//}

/**
 * Returns a Platform object based on Id
 * @param int $platform_id The Id of the Platform
 * @return array
 */
function getPlatformById($platform_id) {
    global $db, $config;
    $columns = '*';
    $where = ["id" => $platform_id];
    $resultados = $db->get($config['t_platforms'], $columns, $where);
    handleDbError();
    $platform = new Platform($resultados['id'], $resultados['name'], $resultados['short_name'], $resultados['icon']);
    return $platform;
}

/**
 * Returns a nick is used or not
 * @param string $user_nick The nick to be checked
 * @return boolean
 */
function nickExists($user_nick) {
    global $config, $db;
    $data['user_nick'] = $user_nick;
    $where = ['user_nick' => $user_nick];
    $exist = $db->has($config['t_users'], $where);
    ;
    handleDbError();
    return $exist;
}

/**
 * Returns a formatted string containing the difference between two dates<br>
 * Types of time:<br>
 * "m": Returns the difference in months
 * "d": Returns the difference in days
 * "h": Returns the difference in hours
 * @param string $oldTime Past time
 * @param int $newTime Future time
 * @param string $timeType
 * @return string
 */
function xTimeAgo($oldTime, $newTime, $timeType) {
    $timeCalc = $newTime - $oldTime;
    if ($timeType == "x") {
        if ($timeCalc = 60) {
            $timeType = "m";
        }
        if ($timeCalc = (60 * 60)) {
            $timeType = "h";
        }
        if ($timeCalc = (60 * 60 * 24)) {
            $timeType = "d";
        }
    }
    if ($timeType == "s") {
        $timeCalc .= " seconds ago";
    }
    if ($timeType == "m") {
        $timeCalc = round($timeCalc / 60) . " minutes ago";
    }
    if ($timeType == "h") {
        $timeCalc = round($timeCalc / 60 / 60) . " hours ago";
    }
    if ($timeType == "d") {
        $timeCalc = round($timeCalc / 60 / 60 / 24) . " days ago";
    }
    return $timeCalc;
}
