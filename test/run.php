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

//Include config file
include_once '../config.php';

//Include the composer dependencies
require_once '../vendor/autoload.php';
/* @var $db Medoo */

$data = [
    "user_name"=>"test11",
    "user_pass"=>"test1",
    "user_nick"=>"test1q",
    "user_email"=>"test1@test.test"
];
$db = new medoo($config['db_config']);
$res = $db->insert($config['t_users'], $data);
$error = $db->error();
var_dump($res);
var_dump($error);