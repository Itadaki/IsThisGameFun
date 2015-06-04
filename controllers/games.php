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
 * Description of games
 *
 * @author Diego Rodríguez Suárez-Bustillo
 */
class games extends Controller {

    public function index($args = array()) {
        return $this->top();
    }

    public function top($args = array()) {
        $most = getBestGames();
        $mostHtml = replaceGame($most);

        $data['games'] = $mostHtml;
        $data['title'] = "Top Voted Games";

        $template = "templates/games/index.html";
        $this->body = replace($data, $template);

        return $this->build();
    }

    public function newest($args = array()) {
        $best = getLatestGames();
        $bestHtml = replaceGame($best);

        $data['games'] = $bestHtml;
        $data['title'] = "New Games";

        $template = "templates/games/index.html";
        $this->body = replace($data, $template);

        return $this->build();
    }

    public function all($args = array()) {
        $all = getAllGames(10000);
        $allHtml = replaceGame($all);

        $data['games'] = $allHtml;
        $data['title'] = "List of Games";

        $template = "templates/games/index.html";
        $this->body = replace($data, $template);

        return $this->build();
    }

    public function details($args = array()) {
        global $config;
        if (count($args) > 0 && is_numeric($args[0])) {
            $game = getGame($args[0]);
            if ($game != null) {
                $details_template = "templates/games/details.html";
                $platform_template = "templates/games/platform.html";

                $data = $game->getDataArray();
                $data = array_merge($data, $data['vote_balance']->getDataArray());
                if ($data['saga'] != null) {
                    $data['saga_id'] = $data['saga']->id;
                    $data['saga_name'] = $data['saga']->name;
                } else {
                    $data['saga_id'] = '';
                    $data['saga_name'] = '';
                }

                $data['platforms_list'] = '';
                foreach ($data['platforms'] as $platform) {
                    $p = $platform->getDataArray();
                    $data['platforms_list'].=replace($p, $platform_template);
                }
                unset($data['vote_balance']);
                unset($data['saga']);
                unset($data['platforms']);
                //Change the breaklines \n and so to <br>
                $data['description'] = nl2br($data['description']);

                if ($game->my_vote === null) {
                    $data['positive_vote_class'] = "";
                    $data['negative_vote_class'] = "";
                } else {
                    $data['positive_vote_class'] = ($game->my_vote == 1) ? "disabled" : "";
                    $data['negative_vote_class'] = ($game->my_vote == 0) ? "disabled" : "";
                }

//                dd($data);
//                $gameHtml = replaceGame([$game]);
//                $data['games'] = $gameHtml;
//                $template = "templates/games/index.html";
                $this->body = replace($data, $details_template);

                return $this->build();
            } else {
                header("Location: {$config['server_root']}main");
            }
        } else {
            header("Location: {$config['server_root']}main");
        }
    }

}
