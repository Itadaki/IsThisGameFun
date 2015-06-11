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
 * Description of Error
 *
 * @author Diego Rodríguez Suárez-Bustillo
 */
class Error2 extends Model {

    public $errorCode;
    public $message;

    public function __construct($errorCode = 0, $message = "An error occurred") {
        $this->message = $message;
        $this->errorCode = $errorCode;
    }

    public function displayError() {
        $errorTemplate = "templates/error/index.html";
        $data = $this->getDataArray($this);
        echo replace($data, $errorTemplate);
        die;
    }

}
