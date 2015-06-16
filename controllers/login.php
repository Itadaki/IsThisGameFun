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
 * Controller for login section
 *
 * @author Diego Rodríguez Suárez-Bustillo
 */
class login extends Controller {

    public function index($args = array()) {
        global $config;
        $this->generateBreadcrumbs([
            "Home" => '{server_root}',
            "Log In" => '{server_root}login'
        ]);
        //Check if the form is posted and if user allow cookies for session (REVIEW NEEDED - COOKIE POLICY --> SEEMS TO BE UNNECESSARY FOR LOGIN COOKIES)
        if (isset($_POST['login']) /**&& isset($_COOKIE[$config['allow_cookies']])**/) {
            //Validate the data and 'return' if something's wrong
            return $this->validateLogIn();
        } else {
            //Shows the login form
            return $this->displayLogInForm();
        }
    }

    public function displayLogInForm($camposErroneos = array(), $camposPendientes = array(), $messages = array()) {
        $data['messages'] = '';
        if ($messages) {
            foreach ($messages as $message) {
                $data['messages'].=$message;
            }
        }
        
        if ($camposErroneos || $camposPendientes) {
            if ($camposErroneos) {
                $data['messages'] .= (new Message('warning', 'Warning', "Usuario o contraseña no validos."))->getMessage();
            }
            if ($camposPendientes) {
                $data['messages'] .= (new Message('danger', 'Error', "Falta un campo."))->getMessage();
            }
        }
        $data['user'] = setValue('user');


        $template = "templates/login/login.html";
        $this->body = replace($data, $template);
        return $this->build();
    }

    private function validateLogIn() {
        global $config;
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
        if (isset($_POST["password"]) && !empty($_POST["password"]) && !preg_match("/^[a-zA-ZÑñ0-9_-]{3,}$/", $_POST["password"])) {
            $camposErroneos[] = "password";
        }

        if ($camposPendientes or $camposErroneos) {
            return $this->displayLoginForm($camposErroneos, $camposPendientes);
        } else {
            //Check user exists in DB
            /* @var $db Medoo */
            global $db, $config;
            $colummns = ['user_id', 'user_nick', 'user_level'];
            $where = ["AND" => ['user_name' => $_POST['user'], 'user_pass' => crypt($_POST['password'],$config['salt'])]];
            $user = $db->select($config['t_users'], $colummns, $where);
            handleDbError();
            if (count($user) != 0) {
                if ($user[0]["user_level"] == "banned"){
                    $messages[] = (new Message('danger', 'Error', "Your account is banned."))->getMessage();
                    return $this->displayLoginForm(false, false, $messages);
                }
                $_SESSION['user_id'] = $user[0]["user_id"];
                $_SESSION['user_nick'] = $user[0]['user_nick'];
                $_SESSION['user_level'] = $user[0]['user_level'];
                if (isset($_POST['keep_logged'])) {
                    session_cache_expire(1440);
                    session_set_cookie_params(86400);
                }
                header("Location: {$config['server_root']}user/profile/".$_SESSION['user_nick']);
            } else {
                return $this->displayLoginForm(true);
            }
        }
    }

    public function logOut() {
        global $config;
        if (isset($_SESSION['user_id'])) {
            session_unset();
        }
        header("Location: {$config['server_root']}main");
    }

}
