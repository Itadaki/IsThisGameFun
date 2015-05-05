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

/**
 * Description of signin
 *
 * @author Diego Rodríguez Suárez-Bustillo
 */
class signin extends Controller {

    public function index($args = array()) {
        global $config;
        //Check if the form is posted and if user allow cookies for session (REVIEW NEEDED - COOKIE POLICY)
        if (isset($_POST['signin']) && $_COOKIE[$config['allow_cookies']]) {
            //Validate the data and 'return' if something's wrong
            return $this->validateSignIn();
        } else {
            //Shows the login form
            return $this->displaySignInForm();
        }
    }

    /**
     * 
     * @param type $camposErroneos
     * @param type $camposPendientes
     * @return type
     */
    private function displaySignInForm($camposErroneos = array(), $camposPendientes = array()) {
        $data['error'] = '';
        if ($camposErroneos || $camposPendientes) {
            if ($camposErroneos) {
                $data['error'] .= '<p class="error_error">Hay campos no validos.</p>';
            }
            if ($camposPendientes) {
                $data['error'] .= '<p class="error_pendiente">Falta algun campo.</p>';
            }
        }
        $data['user'] = setValue('user');
        $data['validateUser'] = validateField('user', $camposPendientes, $camposErroneos);
        $data['nick'] = setValue('nick');
        $data['validateNick'] = validateField('nick', $camposPendientes, $camposErroneos);
        $data['email'] = setValue('email');
        $data['validateEmail'] = validateField('email', $camposPendientes, $camposErroneos);
        $data['validatePassword'] = validateField('password', $camposPendientes, $camposErroneos);

        $template = "templates/signin/signin.html";
        $this->body = replace($data, $template);
        return $this->build();
    }

    /**
     * 
     * @global Medoo $db
     * @global type $config
     * @return type
     */
    private function validateSignIn() {
        $camposObligatorios = array("user", "password", "nick", "email");
        $camposPendientes = array();
        $camposErroneos = array();
        foreach ($camposObligatorios as $campoObligatorio) {
            if (!isset($_POST[$campoObligatorio]) || !$_POST[$campoObligatorio]) {
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

        if ($camposPendientes || $camposErroneos) {
            return $this->displaySignInForm($camposErroneos, $camposPendientes);
        } else {
            //Ver si el usuario existe en la BD
            /* @var $db Medoo */
            global $db, $config;
            $data = [
                "user_name" => $_POST['user'],
                "user_pass" => $_POST['password'],
                "user_nick" => $_POST['nick'],
                "user_email" => $_POST['email']
            ];
            $result = $db->insert($config['t_users'], $data);
            $error = $db->error();
            //If pos 1 in error array is 'true' (not null or 0)
            //That means user created
            if ($error[1]) {
                return $this->displaySignInForm(true);
            } else {
                header("Location: login");
            }
        }
    }

}
