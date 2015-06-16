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
 * Controller for singin section
 *
 * @author Diego Rodríguez Suárez-Bustillo
 */
class signin extends Controller {

    public function index($args = array()) {
        global $config;
        $this->generateBreadcrumbs([
            "Home" => '{server_root}',
            "Sign In" => '{server_root}signin'
        ]);
        //Check if the form is posted and if user allow cookies for session (REVIEW NEEDED - COOKIE POLICY)
        if (isset($_POST['signup']) /* && $_COOKIE[$config['allow_cookies']] */) {
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
    private function displaySignInForm($camposErroneos = array(), $camposPendientes = array(), $messages = array()) {
        $data['messages'] = '';
        if ($messages) {
            foreach ($messages as $message) {
                $data['messages'].=$message;
            }
        }
        if ($camposErroneos || $camposPendientes) {
            if ($camposErroneos) {
                $data['messages'] .= (new Message('warning', 'Warning', "There are some invalid fields."))->getMessage();
            }
            if ($camposPendientes) {
                $data['messages'] .=(new Message('danger', 'Error', "Some fields are missing."))->getMessage();
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
        global $config;
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
            //Check if user already exists in DB
            if ($this->checkUserNameExists($_POST['user'])) {
                $messages[] = (new Message('danger', 'Error', " Username already in use!"))->getMessage();
                return $this->displaySignInForm(['user'], [], $messages);
            }
            if ($this->checkUserNickExists($_POST['nick'])) {
                $messages[] = (new Message('danger', 'Error', " Nick already in use!"))->getMessage();
                return $this->displaySignInForm(['nick'], [], $messages);
            }
            
            /* @var $db Medoo */
            global $db;
            $data = [
                "user_name" => $_POST['user'],
                "user_pass" => crypt($_POST['password'],$config['salt']),
                "user_nick" => $_POST['nick'],
                "user_email" => $_POST['email']
            ];
            $result = $db->insert($config['t_users'], $data);
//            $error = $db->error();
            handleDbError();
            //If pos 1 in $error array is 'true' (not null or 0)
            //That means user created
//            if ($error[1]) {
//                return $this->displaySignInForm(true);
//            } else {
            $messages[] = (new Message('success', 'Success', " New User created! Now you can log in."))->getMessage();
            return (new login())->displayLogInForm([],[],$messages);
//            }
        }
    }

    private function checkUserNameExists($user_name) {
        global $db, $config;
        $where = [
                "user_name" => $user_name
        ];
        $exist = $db->has($config['t_users'], $where);
        handleDbError();
        return $exist;
    }

    private function checkUserNickExists($user_nick) {
        global $db, $config;
        $where = [
            "user_nick" => $user_nick
        ];
        $exist = $db->has($config['t_users'], $where);
        handleDbError();
        return $exist;
    }

}
