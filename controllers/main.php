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
 * Description of main
 *
 * @author Diego Rodríguez Suárez-Bustillo
 */
class main extends Controller {

    public function index($args = array()) {
        //Get array with games
        $latest = getLatestGames();
        //Transform it into html string
        $latesHtml = replaceGame($latest, true);

        //Get array with games
        $best = getBestGames();
        //Transform it into html string
        $bestHtml = replaceGame($best, true);

        //Add the html to an array
        $data['latest'] = $latesHtml;
        $data['best'] = $bestHtml;
        //Use the html array to dump it into the places marked by {} on the template
        $template = "templates/main/index.html";
        $body = replace($data, $template);
        
        //Add the html to the body
        $this->body = $body;
        //Build the pieces of the web and return to client
        return $this->build();

//        return $html;
    }

}
