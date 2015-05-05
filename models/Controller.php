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
 * Description of Controller
 *
 * @author Diego Rodríguez Suárez-Bustillo
 */
class Controller {

    public $top;
    public $menu;
    public $body;
    public $bottom;
    public $data = array();

    public function __construct($top = "templates/frame/top.html", $menu = "templates/frame/menu.html", $bottom = "templates/frame/bottom.html") {
        $this->top = file_get_contents($top);
        $this->menu = getScriptOutput('templates/frame/create-menu.php');
        $this->bottom = file_get_contents($bottom);
        $this->addTemplatesToData();
    }
    
    private function addTemplatesToData(){
        $this->data['top'] = $this->top;
        $this->data['body'] = $this->body;
        $this->data['menu'] = $this->menu;
        $this->data['bottom'] = $this->bottom;
    }
    
    public function build(){
        $this->addTemplatesToData();
        return $this->top.$this->menu.$this->body.$this->bottom;
    }

}
