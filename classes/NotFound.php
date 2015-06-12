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
 * Description of NotFound
 *
 * @author Diego Rodríguez Suárez-Bustillo
 */
class NotFound {
    public $message;
    
    public function __construct($message ="Resource not found on this server.") {
        $this->message = $message;
    }
    
    public function send(){
        header("HTTP/1.0 404 Not Found");
        echo "<h1>Error 404</h1><h2>{$this->message}</h2>";
        die;
    }

    
}
