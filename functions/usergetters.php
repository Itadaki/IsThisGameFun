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
    
    if ($withGames) {
        foreach ($users as &$user) {
            $user['games_voted'] = getGamesVoted($user['user_id']);
        }
    }

    return $users;
}
