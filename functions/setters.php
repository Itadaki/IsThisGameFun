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

function insertGame($name, $description, $platform_ids, $saga_id = null, $cover = null) {
    global $db, $config;

    $insert['name'] = $name;
    $insert['description'] = $description;
    if ($cover != null) {
        $insert['cover'] = $cover;
    }
    //Insert into games table
    $db->update($config['t_games'], $insert, ["id" => $game_id]);

    //Define platforms
    redefinePlatforms($game_id, $platform_ids, true);

    //Define saga
    redefineSagasRelationships($game_id, $saga_id, true);
}

function updateGame($game_id, $name, $description, $platform_ids, $saga_id = null, $cover = null) {
    global $db, $config;

    $insert['name'] = $name;
    $insert['description'] = $description;
    if ($cover != null) {
        $insert['cover'] = $cover;
    }
    //Update into games table
    $db->update($config['t_games'], $insert, ["id" => $game_id]);
    $debug_error = $db->error();

    //Redefine platforms
    redefinePlatformsRelationships($game_id, $platform_ids);

    //Redefine saga
    redefineSagasRelationships($game_id, $saga_id);
}

function redefinePlatformsRelationships($game_id, $platform_ids, $is_new = false) {
    global $db, $config;
    //Delete all former game-platform relationships
    if ($is_new) {
        $db->delete($config['t_game_platform'], [
            "game" => $game_id
        ]);
        $debug_error = $db->error();
    }

    //Generate data array for the insert
    $insert_platform = [];
    foreach ($platform_ids as $platform) {
        $insert_platform[] = ['game' => $game_id, 'platform' => $platform];
    }
    $db->insert($config['t_game_platform'], $insert_platform);
    $debug_error = $db->error();
}

function redefineSagasRelationships($game_id, $saga_id, $is_new = false) {
    global $db, $config;
    if ($is_new) {
        //Delete all former game-saga relationships
        $db->delete($config['t_game_saga'], [
            "game" => $game_id
        ]);
        $debug_error = $db->error();
    }
    //Insert the game-saga relationship
    if ($saga_id != null) {
        $db->insert($config['t_game_saga'], ["game" => $game_id, "saga" => $saga_id]);
    }
    $debug_error = $db->error();
}

function proccessCover($name) {
    $file_name = null;
    $acceped_types = ["image/jpeg", "image/png", "image/gif"];
    if (isset($_FILES['cover']) && $_FILES['cover']["error"] == UPLOAD_ERR_OK && in_array($_FILES['cover']["type"], $acceped_types)) {

        $allowed_chars = "/[^\w]+/";
        $replace_char = "-";
        $name = preg_replace($allowed_chars, $replace_char, $name);
        $file_name = $name . '.' . pathinfo($_FILES['cover']["name"], PATHINFO_EXTENSION);
        $path = 'covers/' . $file_name;

        move_uploaded_file($_FILES['cover']["tmp_name"], $path);
    }

    return $file_name;
}



/**
 * Set or update user vote
 * @global type $config
 * @global type $db
 * @param type $user_id
 * @param type $game_id
 * @param type $vote
 * @return boolean
 */
function setVote($user_id, $game_id, $vote) {
    global $config, $db;
    $data['user'] = $user_id;
    $data['game'] = $game_id;
    $data['vote'] = $vote;


    $where = ['AND' => ['user' => $user_id, 'game' => $game_id]];
    $has_voted = $db->has($config['t_user_votes'], $where);
    if ($has_voted == true) {
        $db->debug()->update($config['t_user_votes'], $data, $where);
    } else {
        $db->debug()->insert($config['t_user_votes'], $data);
    }

    if ($db->error()[0] !== 0) {
        return false;
    }
    return true;
}
