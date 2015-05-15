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
 * Replace the data array on the {tags} on the template
 * @param type $data
 * @param type $template
 * @return type
 */
function replace($data, $template) {
    $file = $template;
    $html = file_get_contents($file);
    foreach ($data as $key1 => $value1) {
        if (count($value1) > 1) {
            foreach ($value1 as $key2 => $value2) {
                $replaceString = "{" . $key1 . " " . $key2 . "}";
                $html = str_replace($replaceString, $value2, $html);
            }
        } else {
            $replaceString = '{' . $key1 . '}';
            $html = str_replace($replaceString, $value1, $html);
        }
    }
    return $html;
}

/**
 * 
 * @global type $config
 * @param type $gameArray
 * @return type
 */
function replaceGame($gameArray, $is_main=false) {
    global $config;
    $html = '';
    foreach ($gameArray as $game) {
        /* @var $game Game */

        //Get the html string for the platforms data
        $platforms = $game->platforms;
        $allPlatforms = '';
        foreach ($platforms as $platform) {
            /* @var $platform Platform */
            $platformData = array(
                "id" => $platform->id,
                "name" => $platform->name,
                "short_name" => $platform->short_name,
                "icon" => $platform->icon
            );
            $template = "templates/common/platform.html";
            $allPlatforms .= replace($platformData, $template);
        }

        //Get the html string for the saga
        if ($game->saga != null) {
            /* @var $saga Saga */
            $saga = $game->saga;
//            $sagaVoteBalanceData = $saga->vote_balance;
            $sagaVoteBalance = replace((array) $saga->vote_balance, "templates/common/vote-balance.html");
            $sagaData = array(
                "id" => $saga->id,
                "name" => $saga->name,
                "description" => $saga->description,
                "logo" => $saga->logo,
                "vote_balance" => $sagaVoteBalance
            );
            $template = "templates/common/saga.html";
            $sagaHtml = replace($sagaData, $template);
        } else {
            $sagaHtml = '';
        }

        /* @var $saga  */
//        $gameVoteBalanceData = $game->vote_balance;
        if ($game->vote_balance != null) {
            $gameVoteBalance = replace((array) $game->vote_balance, "templates/common/vote-balance.html");
        } else {
            $gameVoteBalance = '';
        }
        $gameData = array(
            "id" => $game->id,
            "name" => $game->name,
            "description" => $game->description,
            "cover" => $game->cover,
            "platforms" => $allPlatforms,
            "saga" => $sagaHtml,
            "vote_balance" => $gameVoteBalance,
            "my_vote" => $game->my_vote,
            "user_vote" => $game->user_vote
        );
        if ($is_main){
            $template = "templates/main/game.html";
        } else {
            $template = "templates/common/game.html";
        }
        
        $html .= replace($gameData, $template);
    }
    return $html;
}


/**
 * 
 * @global type $config
 * @param type $gameArray
 * @return type
 */
function replaceSaga($gameArray) {
    global $config;
    $html = '';
    foreach ($gameArray as $game) {
        /* @var $game Game */

        //Get the html string for the platforms data
        $platforms = $game->platforms;
        $allPlatforms = '';
        foreach ($platforms as $platform) {
            /* @var $platform Platform */
            $platformData = array(
                "id" => $platform->id,
                "name" => $platform->name,
                "short_name" => $platform->short_name,
                "icon" => $platform->icon
            );
            $template = "templates/platform.html";
            $allPlatforms .= replace($platformData, $template);
        }

        //Get the html string for the saga
        if ($game->saga != null) {
            /* @var $saga Saga */
            $saga = $game->saga;
//            $sagaVoteBalanceData = $saga->vote_balance;
            $sagaVoteBalance = replace((array) $saga->vote_balance, "templates/vote-balance.html");
            $sagaData = array(
                "id" => $saga->id,
                "name" => $saga->name,
                "description" => $saga->description,
                "logo" => $saga->logo,
                "vote_balance" => $sagaVoteBalance
            );
            $template = "templates/saga.html";
            $sagaHtml = replace($sagaData, $template);
        } else {
            $sagaHtml = '';
        }

        /* @var $saga  */
//        $gameVoteBalanceData = $game->vote_balance;
        if ($game->vote_balance != null) {
            $gameVoteBalance = replace((array) $game->vote_balance, "templates/vote-balance.html");
        } else {
            $gameVoteBalance = '';
        }
        $gameData = array(
            "id" => $game->id,
            "name" => $game->name,
            "description" => $game->description,
            "cover" => $game->cover,
            "platforms" => $allPlatforms,
            "saga" => $sagaHtml,
            "vote_balance" => $gameVoteBalance,
            "my_vote" => $game->my_vote,
            "user_vote" => $game->user_vote
        );
        $template = "templates/game.html";
        $html .= replace($gameData, $template);
    }
    return $html;
}

function validateField($campo, $camposPendientes, $camposErroneos) {
    if (in_array($campo, $camposPendientes)) {
        return ' class="error_pendiente"';
    } elseif (in_array($campo, $camposErroneos)) {
        return ' class="error_error"';
    }
    return '';
}

function setValue($nombreCampo) {
    if (isset($_POST[$nombreCampo])) {
        return $_POST[$nombreCampo];
    }
}

function isAdmin() {
    if (isset($_SESSION['user_level']) && $_SESSION['user_level'] == 'admin') {
        return true;
    }
    return false;
}
