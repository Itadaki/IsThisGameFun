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

function conexion() {
    global $mensajeAbrirConexion;
    global $config;
    $conexion = mysqli_connect($config['db_server'], $config['db_user'], $config['db_pass']);
    mysqli_select_db($conexion, $config['db_name']);
    $errorNo = mysqli_connect_errno();
    $errorMsg = mysqli_connect_error();
    if ($errorNo == 0) {
//        echo "<h2>Conexión establecida con el servidor</h2>";
    } else {
//        echo "<h2>No se ha podido establecer la conexión con el servidor. Se ha producido un error nº $errorNo que corresponde a: $errorMsg</h2>";
    }
    return $conexion;
}

function cerrarConexion($conexion) {
    $operacion = true;
    if (mysqli_close($conexion)) {
//        echo "<h2> Conexión cerrada con exito</h2>";
    } else {
//        echo "<h2> No se ha podido cerrar la conexión</h2>";
    }
}
