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
require '../vendor/autoload.php';

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

