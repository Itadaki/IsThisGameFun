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
include_once './conexion.php';
include_once './vista.php';



if (isset($_COOKIE['allow_cookies'])) {
    session_start();
    echo "<div style='border:1px solid'><h3>SESSION</h3>";
    var_dump($_SESSION);
    if (isset($_SESSION['user_id'])) {
        $config['user_id'] = $_SESSION['user_id'];
    }
    echo "</div>";
}

if (isset($_POST["login"])) {
    validarLogin();
} else {
    displayLoginForm();
}


//function login() {
//    if (isset($_POST['user']) && isset($_POST['pass'])) {
//        $user_name = $_POST['user'];
//        $user_pass = $_POST['pass'];
//        $conexion = conexion();
//        $query = "select user_id from {$config['t_users']} where user_name='$user_name' and user_pass='$user_pass';";
//        $resultado = mysqli_query($conexion, $query);
//        $errorNo = mysqli_errno($conexion);
//        $errorMsg = mysqli_error($conexion);
//        if ($user_id = mysqli_fetch_array($resultado, MYSQLI_ASSOC)['user_id']) {
//            $_SESSION['user_id'] = $user_id;
//            echo "USER_ID: $user_id<br>";
//        } else {
//            echo "Usuario o contraseña incorrectos";
//        }
//        if ($errorNo != 0)
//            echo "<br>$errorNo: $errorMsg";
//    }
//}

function validarLogin() {
    $camposObligatorios = array("user", "password");
    $camposPendientes = array();
    $camposErroneos = array();
    foreach ($camposObligatorios as $campoObligatorio) {
        if (!isset($_POST[$campoObligatorio]) or ! $_POST[$campoObligatorio]) {
            $camposPendientes[] = $campoObligatorio;
        }
    }

    if (isset($_POST["user"]) && !empty($_POST["user"]) && !preg_match("/^[a-zA-ZÑñ0-9_-]{3,}$/", $_POST["user"])) {
        $camposErroneos[] = "user";
    }
    if (isset($_POST["password"]) && !empty($_POST["password"]) && !preg_match("/^[a-zA-ZÑñ0-9_-]{3,}$/", $_POST["user"])) {
        $camposErroneos[] = "password";
    }
    if ($camposPendientes or $camposErroneos) {
//        echo "ERROR";
        displayLoginForm($camposErroneos, $camposPendientes);
    } else {

        //Ver si el usuario existe en la BD
        $conexion = conexion();
        $query = "select user_id, user_nick from isthisgamefun.users where user_name='{$_POST['user']}' and user_pass='{$_POST['password']}'";
        $resultado = mysqli_query($conexion, $query);
        $errorNo = mysqli_errno($conexion);
        $errorMsg = mysqli_error($conexion);
        echo "$errorMsg";
        if ($user = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
            //Si existe, añadir a la variable de sesion la id y el nick
            $_SESSION['user_id'] = $user["user_id"];
            $_SESSION['user_nick'] = $user['user_nick'];

            var_dump($_SESSION);
        } else {
            displayLoginForm($camposErroneos, $camposPendientes, true);
        }
        



//        visualizarDatos($socios);
    }
}
