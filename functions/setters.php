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
 * Insert a new game into games table on db
 * @param string $name Name of the game
 * @param string $description Description of the game. Breaklines \n allowed
 * @param array $platform_ids An array with the platform IDs
 * @param int $saga_id ID of the saga if there is any
 * @param string $cover Filename of the cover in /covers/ folder
 * @return string|false The result of the insert
 */
function insertGame($name, $description, $platform_ids, $saga_id = null, $cover = null) {
    global $db, $config;

    $insert['name'] = $name;
    $insert['description'] = $description;
    if ($cover != null) {
        $insert['cover'] = $cover;
    }
    //Insert into games table
    $game_id = $db->insert($config['t_games'], $insert);
    $error = $db->error();
    if (handleError()) {
        return handleError();
    }

    //Define platforms
    redefinePlatformsRelationships($game_id, $platform_ids);
    return handleError();

    //Define saga
    redefineSagasRelationships($game_id, $saga_id, true);

    //Handle error
    return handleError();
}

/**
 * Update a game from games table on db
 * @param string $name Name of the game
 * @param string $description Description of the game. Breaklines \n allowed
 * @param array $platform_ids An array with the platform IDs
 * @param int $saga_id ID of the saga if there is any
 * @param string $cover Filename of the cover in /covers/ folder
 * @return string|false The result of the update
 */
function updateGame($game_id, $name, $description, $platform_ids, $saga_id = null, $cover = null) {
    global $db, $config;

    $update['name'] = $name;
    $update['description'] = $description;
    if ($cover != null) {
        $update['cover'] = $cover;
    }
    //Update into games table
    $db->update($config['t_games'], $update, ["id" => $game_id]);
    if (handleError()) {
        return handleError();
    }

    //Redefine platforms
    redefinePlatformsRelationships($game_id, $platform_ids);
    if (handleError()) {
        return handleError();
    }
    //Redefine saga
    redefineSagasRelationships($game_id, $saga_id);

    //Handle error
    return handleError();
}

/**
 * Insert a game-platform relationship on db
 * @param type $game_id ID of the game
 * @param type $platform_ids An array with the platform IDs
 */
function redefinePlatformsRelationships($game_id, $platform_ids) {
    global $db, $config;
    //Delete all former game-platform relationships
        $db->delete($config['t_game_platform'], [
            "game" => $game_id
        ]);
        $debug_error = $db->error();

    if (!empty($platform_ids)) {
        //Generate data array for the insert
        $insert_platform = [];
        foreach ($platform_ids as $platform) {
            $insert_platform[] = ['game' => $game_id, 'platform' => $platform];
        }
        $db->insert($config['t_game_platform'], $insert_platform);
        $debug_error = $db->error();
    }
}

/**
 * Insert a game-saga relationship on db
 * @param type $game_id ID of the game
 * @param type $saga_id The saga ID
 */
function redefineSagasRelationships($game_id, $saga_id) {
    global $db, $config;
        //Delete all former game-saga relationships
        $db->delete($config['t_game_saga'], [
            "game" => $game_id
        ]);
        $debug_error = $db->error();
    //Insert the game-saga relationship
    if ($saga_id != null && !empty($saga_id)) {
        $db->insert($config['t_game_saga'], ["game" => $game_id, "saga" => $saga_id]);
    }
    $debug_error = $db->error();
    handleDbError();
}

/**
 * Proccess an uploaded image.
 * @param type $name Name to parse as filename
 * @param type $form_field_name The name of the field in the form that POSTed the image.<br> F.E. "cover" in <input type="file" name="cover">
 * @param type $path_to_save Path target to save the image
 * @return string The file name
 */
function proccessUploadedImage($name, $form_field_name = "cover", $path_to_save = 'covers/') {
    $file_name = null;
    $acceped_types = ["image/jpeg", "image/png", "image/gif"];
    if (isset($_FILES[$form_field_name]) && $_FILES[$form_field_name]["error"] == UPLOAD_ERR_OK && in_array($_FILES[$form_field_name]["type"], $acceped_types)) {

        $allowed_chars = "/[^\w]+/";
        $replace_char = "-";
        $name = preg_replace($allowed_chars, $replace_char, $name);
        $file_name = $name . '.' . pathinfo($_FILES[$form_field_name]["name"], PATHINFO_EXTENSION);
        $path = $path_to_save . $file_name;

        move_uploaded_file($_FILES[$form_field_name]["tmp_name"], $path);
    }

    return $file_name;
}

/**
 * Set or update user vote
 * @param int $user_id ID of the user
 * @param int $game_id ID of the game
 * @param boolean $vote The value of the vote
 * @return string|boolean If no error occurred (false) or error message
 */
function setVote($user_id, $game_id, $vote) {
    global $config, $db;
    $data['user'] = $user_id;
    $data['game'] = $game_id;
    $data['vote'] = $vote;


    $where = ['AND' => ['user' => $user_id, 'game' => $game_id]];
    $has_voted = $db->has($config['t_user_votes'], $where);
    if ($has_voted == true) {
        $db->update($config['t_user_votes'], $data, $where);
    } else {
        $db->insert($config['t_user_votes'], $data);
    }

    return handleError();
}

/**
 * Insert a new platform on the db
 * @param string $name The name of the platform
 * @param string $shortName The short name of the platform
 * @return string|boolean If no error occurred (false) or error message
 */
function insertPlatform($name, $shortName) {
    global $db, $config;

    $insert['name'] = $name;
    $insert['short_name'] = $shortName;

    //Insert into games table
    $db->insert($config['t_platforms'], $insert);

    //Handle error
    return handleError();
}

/**
 * Update a platform on the db
 * @param int $id The ID of the platform
 * @param string $name The name of the platform
 * @param string $shortName The short name of the platform
 * @return string|boolean If no error occurred (false) or error message
 */
function updatePlatform($id, $name, $shortName) {
    global $db, $config;

    $update['name'] = $name;
    $update['short_name'] = $shortName;

    //Update into platforms table
    $db->update($config['t_platforms'], $update, ["id" => $id]);

    //Handle error
    return handleError();
}

/**
 * Insert a new saga on the db
 * @param string $name The name of the saga
 * @param string $description The description of the saga
 * @param string $logo Filename of the logo in /logos/ folder
 * @return string|boolean If no error occurred (false) or error message
 */
function insertSaga($name, $description, $logo = null) {
    global $db, $config;

    $insert['name'] = $name;
    $insert['description'] = $description;
    if ($logo != null) {
        $insert['logo'] = $logo;
    }

    //Insert into games table
    $db->insert($config['t_sagas'], $insert);

    //Handle error
    return handleError();
}

/**
 * Update a saga on the db
 * @param string $name The name of the saga
 * @param string $description The description of the saga
 * @param string $logo Filename of the logo in /logos/ folder
 * @return string|boolean If no error occurred (false) or error message
 */
function updateSaga($id, $name, $description, $logo = null) {
    global $db, $config;

    $update['name'] = $name;
    $update['description'] = $description;
    if ($logo != null) {
        $update['logo'] = $logo;
    }

    //Update into platforms table
    $db->update($config['t_sagas'], $update, ["id" => $id]);

    //Handle error
    return handleError();
}

/**
 * Insert a user on the db
 * @param int $id The id of the user
 * @param string $avatar Filename of the avatar in /avatars/ folder
 * @return string|boolean If no error occurred (false) or error message
 */
function updateUser($id, $avatar) {
    global $db, $config;

    $update['user_avatar'] = $avatar;

    //Update into users table
    $db->update($config['t_users'], $update, ["user_id" => $id]);

    //Handle error
    return handleError();
}
