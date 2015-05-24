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


//Include all needed files
include_once './autoinclude.php';

//Set the DB Object
$db = new medoo($config['db_config']);

//Get the URL parts for the router
//Get the section
if (isset($_GET['section']) && !empty($_GET['section'])) {
    $section = $_GET['section'];
} else {
    $section = "main";
}
//$section .= "controller";
//Get the action
if (isset($_GET['action']) && !empty($_GET['action'])) {
    $action = $_GET['action'];
} else {
    $action = "index";
}

//Get the args
if (isset($_GET['args']) && !empty($_GET['args'])) {
    $args = explode("/", $_GET['args']);
} else {
    $args = array();
}


//Controll the cookies and session
//setcookie($config['allow_cookies'], true, time()+60*60*24*30);
if (isset($_COOKIE[$config['allow_cookies']])) {
    session_cache_expire(0);
    session_start();
//    echo '<h6>Session is running</h6>';
    if (isset($_SESSION['user_id'])) {
        $config['user_id'] = $_SESSION['user_id'];
        $config['user_nick'] = $_SESSION['user_nick'];
        $config['user_level'] = $_SESSION['user_level'];
    }
}


//Set the router

$router = new Router($section, $action, $args);

echo $router->start();
