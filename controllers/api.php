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
 * Description of api
 *
 * @author Diego Rodríguez Suárez-Bustillo
 */
class api extends Controller {

    public function index() {
        header('HTTP/1.0 403 Forbidden');
    }

    public function vote() {
        if ($this->is_ajax() && $this->is_logged()) {
            $json = json_decode($_POST['json']);
            $game_id = $json->game_id;
            $vote = $json->vote;
            $user_id = $_SESSION['user_id'];
            //INSERTAR VOTO
            
            //MANEJAR ERRORES
            
            //EJEMPLO OK
//            $respuesta['error']=false;
//            $respuesta['message']="Game $game_id voted with $vote from {$_SESSION['user_nick']}!";
//            return json_encode($respuesta);
            
            //EJEMPLO ERROR
            $respuesta['error']=True;
            $respuesta['message']="Error voting game $game_id voted with $vote from {$_SESSION['user_nick']}!";
            return json_encode($respuesta);
        } else {
            header('HTTP/1.0 403 Forbidden');
        }
    }

    private function is_logged() {
        return isset($_SESSION['user_nick']);
    }

    private function is_ajax() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }

}
