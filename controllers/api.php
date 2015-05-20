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
        if ($this->isAjax() && $this->isPost() && $this->isLogged()) {
            $json = json_decode($_POST['json']);
            $game_id = $json->game_id;
            $vote = $json->vote;
            $user_id = $_SESSION['user_id'];

            //INSERT VOTE
            $error = setVote($user_id, $game_id, $vote);

            //VOTE WAS SUCCESFUL
            if (!$error) {
                $error = false;
                $message = "Game $game_id voted {$vote} from {$_SESSION['user_nick']}!";
                return $this->encodeResponse($error,$message);
            } else {
                //THERE IS AN ERROR
                $error = true;
                $message = "Error while voting!";
                return $this->encodeResponse($error,$message);
            }
        } else if ($this->isAjax()) {
            //USER IS NOT LOGGED IN
            $error = true;
            $message = "User not logged in!";
            return $this->encodeResponse($error,$message);
        } else {
            header('HTTP/1.0 403 Forbidden');
        }
    }

    private function encodeResponse($error, $message, $additionalData = array()) {
        $response['error'] = $error;
        $response['message'] = $message;
        foreach ($additionalData as $index => $value) {
            $response[$index] = $value;
        }
        return json_encode($response);
    }

    

    public function checkUserNick($args = array()) {
        sleep(2);
        if ($this->isAjax() && $this->isGet() && isset($args[0])) {
            $nickExists = nickExists($args[0]);
            return $this->encodeResponse(false, '', array('exists'=>$nickExists));
        }
        return $this->encodeResponse(true, 'No nick especified!');
    }

}


