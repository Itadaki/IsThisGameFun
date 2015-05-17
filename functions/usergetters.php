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

function getUser($match, $by_nick = false) {
    global $db, $config;

    if ($by_nick) {
        $colummns = '*';
        $where = ['user_nick' => $match];
    } else {
        $colummns = ['user_id', 'user_nick', 'user_avatar'];
        $where = ['user_id' => $match];
    }


    $user = $db->get($config['t_users'], $colummns, $where);
    $error = $db->error();
    if ($user && !$by_nick) {
        $user['games_voted'] = getGamesVoted($user['user_id']);
    }
    return $user;
}

function getUsers($withGames = false) {
    global $db, $config;

    $colummns = '*';
    $where = [];
    $users = $db->select($config['t_users'], $colummns);
    $error = $db->error();
    if ($withGames) {
        foreach ($users as &$user) {
            $user['games_voted'] = getGamesVoted($user['user_id']);
        }
    }
//    if ($user) {
//        $user['games_voted'] = getGamesVoted($user['user_id']);
//    }
    return $users;
}

//function getUserold($user, $input_type = 'id') {
//    global $conexion, $config;
//    if ($input_type == 'id') {
//        $query = "select user_id, user_nick, user_avatar from {$config['t_users']} where user_id=$user;";
//    }
//    if ($input_type == 'name') {
//        $query = "select user_id, user_nick, user_avatar from {$config['t_users']} where lower(user_name)=lower($user);";
//    }
//    if ($input_type == 'nick') {
//        $query = "select user_id, user_nick, user_avatar from {$config['t_users']} where lower(user_nick) like lower('%$user%');";
//    }
//    if ($input_type == 'search') {
//        $query = "select user_id, user_nick, user_avatar from {$config['t_users']} where lower(user_nick) like lower('%$user%') or lower(user_name) like lower('%$user%');";
//    }
//    $resultado = mysqli_query($conexion, $query);
//    $errorNo = mysqli_errno($conexion);
//    if ($errorNo != 0) {
//        return array("error" => "$errorNo: " . mysqli_error($conexion));
//    }
//    $users = array();
//    while ($user = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
//        $users[] = $user;
//    }
//    return $users;
//}
