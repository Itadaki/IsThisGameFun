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
//$config[''] = '';
$config['db_name'] = 'isthisgamefun';
$config['t_users'] = 'users';
$config['t_games'] = 'games';
$config['t_user_votes'] = 'user_votes';
$config['t_platforms'] = 'platforms';
$config['t_game_platform'] = 'game_platform';
$config['t_sagas'] = 'sagas';
$config['t_game_saga'] = 'game_saga';
$config['t_saga_votes'] = 'saga_votes';

$config['v_game_positive_percentage'] = 'game_positive_percentage';
$config['v_game_vote_balance'] = 'game_vote_balance';
$config['v_most_negative_voted'] = 'most_negative_voted';
$config['v_most_positive_voted'] = 'most_positive_voted';
$config['v_most_voted'] = 'most_voted';
$config['v_saga_vote_balance'] = 'game_saga_balance';

$config['t_full_users'] = $config['db_name'] . '.' . $config['t_users'];
$config['t_full_games'] = $config['db_name'] . '.' . $config['t_games'];
$config['t_full_user_votes'] = $config['db_name'] . '.' . $config['t_user_votes'];
$config['t_full_platforms'] = $config['db_name'] . '.' . $config['t_platforms'];
$config['t_full_game_platform'] = $config['db_name'] . '.' . $config['t_game_platform'];

$config['server_root'] = '/IsThisGameFun/';

$config['allow_cookies'] = 'cookie_compliance';