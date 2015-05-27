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
 * Description of user
 *
 * @author Diego Rodríguez Suárez-Bustillo
 */
class user extends Controller {

    public function profile($args = array(), $messages = array()) {
        global $config;
        if (isset($args[0])) {
            $user = getUser($args[0], true);
            
            //getUser returns false if user doesnt exists
            if ($user) {
                global $db, $config;
                $user_vote_history_template = "templates/user/vote-history.html";
                $user_profile_template = "";
                
                $data = $user;
//                $data['user_id'] = $user['user_id'];
//                $data['user_nick'] = $user['user_nick'];
//                $data['user_avatar'] = $user['user_avatar'];
                $join = [
                    "[>]users" => ["user" => "user_id"],
                    "[>]games" => ["game" => "id"]
                ];
                $columns = ['name', 'game', 'vote', 'vote_date'];
                $where = ["user_id" => $user['user_id'], "ORDER" => "vote_date DESC",];
                $votes = $db->select($config['t_user_votes'], $join, $columns, $where);
                handleDbError();
                $history_html = '';
                foreach ($votes as $vote) {
                    $temp['game'] = $vote['name'];
                    $temp['id'] = $vote['game'];
                    $temp['date'] = xTimeAgo(strtotime($vote['vote_date']), time(), 'd');
                    $temp['vote'] = $vote['vote'] ? 'thumbs-up' : 'thumbs-down';
                    $history_html .= replace($temp, $user_vote_history_template);
                }

                $data['history'] = ($history_html=='')?'No Votes found.' : $history_html;
                
                $data['edit_display'] = 'hidden';
                if (isset($_SESSION['user_id']) && $user['user_id'] == $_SESSION['user_id']) {
                    $data['edit_display'] = '';
                }

                //DISPLAY MESSAGES
                $data['messages'] = '';
                if ($messages) {
                    foreach ($messages as $message) {
                        $data['messages'].=$message;
                    }
                }

                $data['ago'] = xTimeAgo(strtotime($data['create_time']), time(), "d");
                
                $template = "templates/user/user-profile.html";
                $this->body = replace($data, $template);

                return $this->build();
            }
        }
        //Return to root
        header("Location: {$config['server_root']}");
    }

    public function edit() {
        global $config;
        $returnURL = $config['server_root'];
        if ($this->isPost() && $this->isLogged() && isset($_POST['action']) && isset($_FILES['avatar'])) {
            $id = $_SESSION['user_id'];
            $name = $_SESSION['user_nick'];
            $returnURL .= 'user/profile/' . $name;

            //Check file dimensions
            $size = getimagesize($_FILES['avatar']['tmp_name']);
            $width = $size[0];
            $height = $size[1];
            //IF ERROR, display profile with error
            if ($width > 200 || $height > 200) {
                $message = array((new Message('danger', 'Error', 'Exceeded file dimentions (max filse dimentions: 200x200px)'))->getMessage());
                return $this->profile(array($_SESSION['user_nick']), $message);
            }
            //If size is ok, update
            $form_field_name = 'avatar';
            $path_to_save = 'avatars/';
            $avatar = proccessUploadedImage($name, $form_field_name, $path_to_save);

            //Edit user
            updateUser($id, $avatar);
            $error = [
                "level" => "success",
                "title" => "Done",
                "msg" => "Avatar updated!"
            ];
            return $this->profile(array($_SESSION['user_nick']), $error);
        }
        header("Location: " . $returnURL);
    }

}
