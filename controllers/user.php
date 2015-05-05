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

    public function profile($args = array()) {
        $user = getUser($args[0]);
        //getUser returns false if user doesnt exists
        if ($user) {
            $data['user_id'] = $user['user_id'];
            $data['user_nick'] = $user['user_nick'];
            $data['user_avatar'] = $user['user_avatar'];
            $data['games_voted'] = replaceGame($user['games_voted']);

            $template = "templates/user/user-profile.html";
            $this->body = replace($data, $template);

            return $this->build();
        } else {
            //Return to root
            header("Location: ../../");
        }
    }

}
