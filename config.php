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

$config = array();

$config['db_server'] = 'localhost';
$config['db_user'] = 'root';
$config['db_pass'] = '';
$config['db_charset'] = 'utf8';
$config['db_name'] = 'isthisgamefun';

$config['db_config'] = array(
    'database_type' => 'mysql',
    'database_name' => $config['db_name'],
    'server' => 'localhost',
    'username' => $config['db_user'],
    'password' => $config['db_pass'],
    'charset' => $config['db_charset']
);

$config['t_users'] = 'users';
$config['t_games'] = 'games';
$config['t_user_votes'] = 'user_votes';
$config['t_platforms'] = 'platforms';
$config['t_game_platform'] = 'game_platform';
$config['t_sagas'] = 'sagas';
$config['t_game_saga'] = 'game_saga';
$config['t_saga_votes'] = 'saga_votes';

$config['t_full_users'] = $config['db_name'] . '.' . $config['t_users'];
$config['t_full_games'] = $config['db_name'] . '.' . $config['t_games'];
$config['t_full_user_votes'] = $config['db_name'] . '.' . $config['t_user_votes'];
$config['t_full_platforms'] = $config['db_name'] . '.' . $config['t_platforms'];
$config['t_full_game_platform'] = $config['db_name'] . '.' . $config['t_game_platform'];

$config['server_root'] = '/IsThisGameFun/';

$config['allow_cookies'] = 'cookie_compliance';

$config['salt'] = "curlybrace";

//Turn it false in release mode
error_reporting(E_ALL);

//Alternative mode
//ini_set( "display_errors", 0); 

register_shutdown_function('CatchFatalError');
function CatchFatalError() {
    global $config;
    $error = error_get_last();
    
    if ($error['type']) {
        // handle the error - but DO NOT THROW ANY EXCEPTION HERE.
//        d($error);
        $log = "Type: {$error['type']}; Message: {$error['message']}; File: {$error['file']}; Line: {$error['line']}";
        header("Location: {$config['server_root']}error");
        file_put_contents('logs/log_'.date("j.n.Y").'.txt', $log, FILE_APPEND);
    }
    die;
}

