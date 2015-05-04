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

//Incluir todos los ficheros necesarios
include_once './autoinclude.php';


//Establecer la clase
if (isset($_GET['section']) && !empty($_GET['section'])) {
    $section = $_GET['section'];
} else {
    $section = "main";
}
$section .= "controller";

//Establecer el metodo
if (isset($_GET['action']) && !empty($_GET['action'])) {
    $action = $_GET['action'];
} else {
    $action = "index";
}

//Establecer los parametros
if (isset($_GET['args']) && !empty($_GET['args'])) {
    $args = explode("/", $_GET['args']);
} else {
    $args = array();
}


//Invocar a la clase enrutador

$r = new router($section, $action, $args);
echo $r->start();



