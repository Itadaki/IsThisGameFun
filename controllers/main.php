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
class main extends Controller{

    public function index($args = array()) {
        $latest = getLatestGames();
        $latesHtml = replaceGame($latest);
        
        $best = getBestGames();
        $bestHtml = replaceGame($best);
        
        $this->data['latest'] = $latesHtml;
        $this->data['best'] = $bestHtml;

        $template = "templates/index.html";
        $html = replace($this->data, $template);
        
        return $html;
    }

}

