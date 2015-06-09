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
 * Description of router
 *
 * @author Diego Rodríguez Suárez-Bustillo
 */
class Router {

    private $controller;
    private $action;
    private $args;

    public function __construct($controller = "main", $action = "index", $args = array()) {
        $this->controller = $controller;
        $this->action = $action;
        $this->args = $args;
    }

    public function start() {
        //If the class exist
        if (class_exists($this->controller)) {
            //Invoke the new class
            $c = new $this->controller();
            //If the class has the action method
            if (method_exists($c, $this->action)) {
                //Invoke the method
                return $c->{$this->action}($this->args);
            } else {
            (new NotFound())->send();
            }
        } else {
            (new NotFound())->send();
        }
    }

}

