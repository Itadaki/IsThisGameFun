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
//$settings[''] = '';
$config['db_name'] = 'isthisgamefun';
$config['t_users'] = $config['db_name'].'users';
$config['t_games'] = $config['db_name'].'games';
$config['t_user_votes'] = $config['db_name'].'user_votes';
$config['t_platforms'] = $config['db_name'].'platforms';
$config['t_game_platform'] = $config['db_name'].'game_platform';
