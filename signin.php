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
include_once './getGames.php';



//if (isset($_COOKIE['allow_cookies'])) {
//    session_start();
//    echo "<div style='border:1px solid'><h3>SESSION</h3>";
//    var_dump($_SESSION);
//    if (isset($_SESSION['user_id'])) {
//        $config['user_id'] = $_SESSION['user_id'];
//    }
//    echo "</div>";
//}
//if (!isset($_SESSION['user_id'])) {
//    $conexion = conexion();
//    if (isset($_POST["signin"])) {
//        validarRegistro();
//    } else {
//        displaySigninForm();
//    }
//} else {
//    header("Location: index");
//}

function validarRegistro() {
    global $conexion;
    $camposObligatorios = array("user", "password", "nick", "email");
    $camposPendientes = array();
    $camposErroneos = array();
    $existe = false;
    //Comprobar si ya existe el usuario
    if (isset($_POST["user"]) && !empty($_POST["user"])) {
        $existe = getUser($_POST["user"], 'name');
    }
    if (isset($_POST["nick"]) && !empty($_POST["nick"])) {
        $existe = getUser($_POST["nick"], 'nick');
    }

    //Validar campos
    foreach ($camposObligatorios as $campoObligatorio) {
        if (!isset($_POST[$campoObligatorio]) || ! $_POST[$campoObligatorio]) {
            $camposPendientes[] = $campoObligatorio;
        }
    }

    if (isset($_POST["user"]) && !empty($_POST["user"]) && !preg_match("/^[a-zA-ZÑñ0-9_-]{3,}$/", $_POST["user"])) {
        $camposErroneos[] = "user";
    }
    if (isset($_POST["password"]) && !empty($_POST["password"]) && !preg_match("/^[a-zA-ZÑñ0-9_-]{3,}$/", $_POST["password"])) {
        $camposErroneos[] = "password";
    }
    if (isset($_POST["nick"]) && !empty($_POST["nick"]) && !preg_match("/^[a-zA-ZÑñ0-9_-]{3,}$/", $_POST["nick"])) {
        $camposErroneos[] = "nick";
    }
    if (isset($_POST["email"]) && $_POST["email"]) {
        if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
            $camposErroneos[] = "email";
        }
    }

    //ALGUN PROBLEMA
    if ($camposPendientes || $camposErroneos || $existe) {
        displaySigninForm($camposErroneos, $camposPendientes, $existe);
    } else {
        //SI TODO ESTA CORRECTO INSERTAMOS USUARIO
        $query = "INSERT INTO isthisgamefun.users VALUES (NULL, '{$_POST["user"]}', '{$_POST["password"]}', 'normal', '{$_POST["nick"]}', '{$_POST["email"]}', DEFAULT, DEFAULT, DEFAULT)";
        $resultado = mysqli_query($conexion, $query);
        $errorNo = mysqli_errno($conexion);
        //LA INSERCION FUE BIEN O MAL
        if ($errorNo != 0) {
            var_dump(array("error" => "$errorNo: " . mysqli_error($conexion)));
        } else {
            $_SESSION['user_id'] = mysqli_insert_id($conexion);
            $_SESSION['user_nick'] = $_POST['nick'];
            $id = mysqli_insert_id($conexion);
            $nick = $_POST['nick'];
            echo "'{$_SESSION['user_id']} {$_SESSION['user_nick']} 'registrado!";
        }
    }
}

//$conexion = conexion();
//var_dump(getUser("admin", 'search'));

//$resultado = mysqli_query($conexion, "insert into isthisgamefun.dummy values (null)");
//$errorNo = mysqli_errno($conexion);
//if ($errorNo != 0) {
//    var_dump(array("error" => "$errorNo: " . mysqli_error($conexion)));
//}

//echo mysqli_insert_id($conexion);
