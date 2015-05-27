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
 * Replace the {tags} on the template with the values of the data array where the tags matches the indexes
 * @param array $data An associative array
 * @param string $template Path to the template that contains the {tags}
 * @return string The template with the {tags} replaced
 */
function replace($data, $template, $providing_plain_text = false) {
    $file = $template;
    if (!$providing_plain_text) {
        $html = file_get_contents($file);
    } else {
        $html = $template;
    }
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
 * @param Game[] $gameArray Array fo Game object
 * @param boolean $is_main Set if the games are goin to be displayed on the main page.<br>The template changes if so.
 * @return string The template with the {tags} replaced
 */
function replaceGame($gameArray, $is_main = false) {
    $html = '';
    foreach ($gameArray as $game) {
        /* @var $game Game */

        //Get the html string for the platforms data
        $platforms = $game->platforms;
        $platformsHtml = '';
        foreach ($platforms as $platform) {
            /* @var $platform Platform */
            $platformData = $platform->getDataArray();
            $template = "templates/common/platform.html";
            $platformsHtml .= replace($platformData, $template);
        }

        //Get the html string for the saga
        if ($game->saga != null) {
            /* @var $saga Saga */
//            $saga = $game->saga;
//            $sagaVoteBalanceData = $saga->vote_balance;
//            $sagaVoteBalance = replace((array) $saga->vote_balance, "templates/common/vote-balance.html");
            $sagaData = $game->saga->getDataArray();
            $sagaData["description"] = nl2br($sagaData["description"]); //Change \n to <br>

            $template = "templates/common/saga.html";
            $sagaHtml = replace($sagaData, $template);
            $sagaCaretHtml = 'Saga <span class="caret">';
        } else {
            $sagaHtml = '';
            $sagaCaretHtml = '';
        }

        /* @var $saga  */
//        $gameVoteBalanceData = $game->vote_balance;
        if ($game->vote_balance != null) {
            if ($game->my_vote === null) {
                $game->vote_balance->positive_vote_class = "";
                $game->vote_balance->negative_vote_class = "";
            } else {
                $game->vote_balance->positive_vote_class = ($game->my_vote == 1) ? "chosen" : "bg-gray2";
                $game->vote_balance->negative_vote_class = ($game->my_vote == 0) ? "chosen" : "bg-gray2";
            }

            $gameVoteBalanceHtml = replace($game->vote_balance->getDataArray(), "templates/common/vote-balance.html");
        } else {
            $gameVoteBalanceHtml = '';
        }

//        $positive_vote = $game->my_vote ? "choosed" : "";
//        $negative_vote = $game->my_vote ? "" : "choosed";
        $gameData = $game->getFullDataArray();
        $gameData['platforms'] =$platformsHtml;
        $gameData['saga'] =$sagaHtml;
        $gameData['vote_balance'] =$gameVoteBalanceHtml;
        $gameData['saga_caret'] =$sagaCaretHtml;
//        $gameData = array(
//            "id" => $game->id,
//            "name" => $game->name,
//            "description" => $game->description,
//            "cover" => $game->cover,
//            "platforms" => $allPlatforms,
//            "saga" => $sagaHtml,
//            "vote_balance" => $gameVoteBalance,
//            "my_vote" => $game->my_vote,
//            "user_vote" => $game->user_vote,
//            "saga_caret" => $saga_caret
//        );
        if ($is_main) {
            $template = "templates/main/game.html";
        } else {
            $template = "templates/common/game.html";
        }

        $html .= replace($gameData, $template);
    }
    return $html;
}
