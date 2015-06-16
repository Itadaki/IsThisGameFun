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
 * Controller for api section
 *
 * @author Diego Rodríguez Suárez-Bustillo
 */
class api extends Controller {

    public function index() {
//        header('HTTP/1.0 403 Forbidden');
        (new Forbidden())->send();
    }

    public function vote() {
        sleep(2);
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
                return $this->encodeResponse($error, $message);
            } else {
            //THERE IS AN ERROR
                $error = true;
                $message = "Error while voting!";
                return $this->encodeResponse($error, $message);
            }
        } else if ($this->isAjax()) {
            //USER IS NOT LOGGED IN
            $error = true;
            $message = "User not logged in!";
            return $this->encodeResponse($error, $message);
        } else {
            (new Forbidden())->send();
//            header('HTTP/1.0 403 Forbidden');
//            die;
        }
    }

    public function getMoreGames($args = array()) {
        // api/getMoreGames/type/offset/limit
        //1 - Best games
        //2 - New games
        //3 - All Games
        if ($this->isAjax() || $this->isGet()) {
            $offset = 0;
            $limit = 20;
            $type = 1;
            if (isset($args[0])) {
                $type = $args[0];
            }
            if (isset($args[1])) {
                $offset = $args[1];
            }
            if (isset($args[2])) {
                $limit = $args[2];
            }
            switch ($type) {
                case 1:
                    $games = getBestGames($limit, $offset);
                    break;
                case 2:
                    $games = getLatestGames($limit, $offset);
                    break;
                case 3:
                    $games = getAllGames($limit, $offset);
                    break;

                default:
                    die;
            }
            if (count($games)== $limit){
                $quota= true;
            } else {
                $quota= false;
            }
            return $this->encodeResponse(false, "Request OK", ["full_quota"=>$quota, "games"=>$games]);
        }
        return $this->encodeResponse(true, "Not AJAX or not GET");
    }

    private function encodeResponse($error, $message, $additionalData = array()) {
        $response['error'] = $error;
        $response['message'] = $message;
        foreach ($additionalData as $index => $value) {
            $response[$index] = $value;
        }
        header('Content-Type: application/json');
        return json_encode($response);
    }

    public function checkUserNick($args = array()) {
        sleep(2);
        if ($this->isAjax() || $this->isGet() && isset($args[0])) {
            $nickExists = nickExists($args[0]);
            $message = "This nick is " . ($nickExists?"not ":"") . "available";
            return $this->encodeResponse(false, $message, array('exists' => $nickExists));
        }else if ($this->isAjax()) {
            //USER IS NOT LOGGED IN
            $error = true;
            $message = "No nick especified!";
            return $this->encodeResponse($error, $message);
        }
        return $this->encodeResponse(true, 'Not AJAX or not GET!');
    }

}
