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
        if ($noExiste){
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
