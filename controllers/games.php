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
        $most = getBestGames();
        $mostHtml = replaceGame($most);

        $this->data['most'] = $mostHtml;

        $template = "templates/games.html";
        $html = replace($this->data, $template);

        return $html;
    }

    public function top($args = array()) {
        return $this->index();
    }

    public function latest($args = array()) {
        $best = getBestGames();
        $bestHtml = replaceGame($best);

        $this->data['most'] = $bestHtml;

        $template = "templates/games.html";
        $html = replace($this->data, $template);

        return $html;
    }

    public function all($args = array()) {
        $all = getAllGames();
        $allHtml = replaceGame($all);

        $this->data['most'] = $allHtml;

        $template = "templates/games.html";
        $html = replace($this->data, $template);

        return $html;
    }

    public function details($args = array()) {
        if (count($args) > 0 && is_numeric($args[0])) {
            $game = getGame($args[0]);
            if ($game != null) {
                $data[] = $game;
                $gameHtml = replaceGame($data);
                
                $this->data['most'] = $gameHtml;
                $template = "templates/games.html";
                $html = replace($this->data, $template);

                return $html;
            } else {
                header('Location: ../../main');
            }
        } else {
            header('Location: main');
        }
    }

}
