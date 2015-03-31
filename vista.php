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

function validateField($campo, $camposPendientes, $camposErroneos) {
    if (in_array($campo, $camposPendientes)) {
        return ' class="error_pendiente"';
    } elseif (in_array($campo, $camposErroneos)) {
        return ' class="error_error"';
    }
}

function setValue($nombreCampo) {
    if (isset($_POST[$nombreCampo])) {
        return $_POST[$nombreCampo];
    }
}

function displayLoginForm($camposErroneos = array(), $camposPendientes = array(), $noExiste = false) {
    $mensaje = "";
    $error = "";
    if ($camposErroneos || $camposPendientes || $noExiste) {
        if ($camposErroneos) {
            $error .= '<p class="error_error">Usuario o contraseña no validos.</p>';
        }
        if ($camposPendientes) {
            $error .= '<p class="error_pendiente">Falta un campo.</p>';
        }
        if ($noExiste) {
            $error .= '<p class="info">Usuario o contraseña incorrectos.</p>';
        }
    } else {
        $mensaje = '<p>Introduce user y pass</p>';
    }
    $datos = array(
        "error" => $error,
        "mensaje" => $mensaje,
        "validacionUsuario" => validateField("user", $camposPendientes, $camposErroneos),
        "valorUsuario" => setValue("user"),
        "validacionPassword" => validateField("password", $camposPendientes, $camposErroneos)
    );
    $plantilla = "plantillas/login_form.html";
    $formulario = respuesta($datos, $plantilla);
    print ($formulario);
//    $datos = array(
//        "titulo" => TITULO,
//        "formulario" => $formulario
//    );
//    $plantilla = "plantillas/plantilla.html";
//    $html = respuesta($datos, $plantilla);
//    print ($html);
}

function displaySigninForm($camposErroneos = array(), $camposPendientes = array(), $existe = false) {
    $mensaje = "";
    $error = "";
    if ($camposErroneos || $camposPendientes || $existe) {
        if ($camposErroneos) {
            $error .= '<p class="error_error">Hay campos no validos.</p>';
        }
        if ($camposPendientes) {
            $error .= '<p class="error_pendiente">Falta un campo.</p>';
        }
        if ($existe) {
            $error .= '<p class="info">Usuario ya existe.</p>';
        }
    } else {
        $mensaje = '<p>Introduce user y pass</p>';
    }
    $datos = array(
        "error" => $error,
        "mensaje" => $mensaje,
        "validacionUsuario" => validateField("user", $camposPendientes, $camposErroneos),
        "valorUsuario" => setValue("user"),
        "validacionPassword" => validateField("password", $camposPendientes, $camposErroneos),
        "validacionNick" => validateField("nick", $camposPendientes, $camposErroneos),
        "valorNick" => setValue("nick"),
        "validacionEmail" => validateField("email", $camposPendientes, $camposErroneos),
        "valorEmail" => setValue("email")
    );
    $plantilla = "plantillas/signin_form.html";
    $formulario = respuesta($datos, $plantilla);
    print ($formulario);
//    $datos = array(
//        "titulo" => TITULO,
//        "formulario" => $formulario
//    );
//    $plantilla = "plantillas/plantilla.html";
//    $html = respuesta($datos, $plantilla);
//    print ($html);
}

function displayMainPage() {
    global $content, $config;
    $user = getUserMenu();
    $datos = array(
        "data" => "MAIN PAGE",
        "user" => $user,
        "content" => $content,
        "admin" => "Nop"
    );
    var_dump($config);
    if (isset($config['user_level']) && $config['user_level'] == 'admin') {
        $datos['admin'] = 'Eres administrador!!';
    }
    $plantilla = "plantillas/base.html";
    $formulario = respuesta($datos, $plantilla);
    print ($formulario);
}

function getUserMenu() {
    global $config;
    if (isset($config['user_id']) && isset($config['user_nick'])) {
        $datos = array(
            "user_nick" => $config['user_nick'],
            "enlace" => "./user/" . $config['user_nick']
        );
    } else {
        $datos = array(
            "user_nick" => "Login",
            "enlace" => "./login"
        );
    }
    $plantilla = "plantillas/user_menu.html";
    return respuesta($datos, $plantilla);
}

function displayUser($user_nick) {
    global $config;
    if ($user = getUser($user_nick, 'nick')) {
//        var_dump($user);
        $user = $user[0];
        $games_voted = getGamesVoted($user['user_id'], 20);

        $content = '';
        foreach ($games_voted as $game) {
            $votes = getVoteBalance($game['id']);
            $datos = array(
                "id" => $game['id'],
                "name" => $game['name'],
                "cover" => $game['cover'],
                "totalVotos" => $votes['total'],
                "totalPositivos" => $votes['positives'],
                "vote" => $game['vote']
            );
            $plantilla = "plantillas/pastilla.html";
            $content .= respuesta($datos, $plantilla);
        }
        $datos = array(
            "user_nick" => $user['user_nick'],
            "user_avatar" => $config['server_root'] . "/avatars/" . $user['user_avatar'],
            "content" => $content
        );
        $plantilla = "plantillas/user_profile.html";
        print respuesta($datos, $plantilla);
    } else {
        echo "No existe el usuario";
    }
//    var_dump($games_voted);
}

function displayGame($game_id) {
    $game = getGame($game_id);
    $datos = array(
        "id" => $game['id'],
        "name" => $game['name'],
        "cover" => $game['cover'],
        "totalVotos" => $game['totalVotes'],
        "totalPositivos" => $game['totalPositiveVotes'],
        "percent"=>(int)($game['totalPositiveVotes']/$game['totalVotes']*100),
        "vote"=>null
    );
    if (isset($game['userVote'])){
        $datos["vote"] = $game['userVote'];
    }
    $p='<ul>';
    foreach ($game['platforms'] as $platform) {
        $p .='<li>'.$platform['short_name'].'</li>';
    }
    $p.='</ul>';
    $datos['extra'] = $p;
    $plantilla = "plantillas/pastilla.html";
    print respuesta($datos, $plantilla);
    var_dump($datos);
}

function respuesta($resultados, $plantilla) {
    $file = $plantilla;
    $html = file_get_contents($file);
    foreach ($resultados as $key1 => $valor1)
        if (count($valor1) > 1) {
            foreach ($valor1 as $key2 => $valor2) {
                $cadena = "{" . $key1 . " " . $key2 . "}";
                $html = str_replace($cadena, $valor2, $html);
            }
        } else {
            $cadena = '{' . $key1 . '}';
            $html = str_replace($cadena, $valor1, $html);
        }
    return $html;
}
