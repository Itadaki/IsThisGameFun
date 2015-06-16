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
 * Message class to display alerts on site
 *
 * @author Diego Rodríguez Suárez-Bustillo
 */
class Message {

    public $type;
    public $title;
    public $text;

    public function __construct($type, $title, $text) {
        $this->type = $type;
        $this->title = $title;
        $this->text = $text;
    }

    public function getMessage() {
        $message = [
            "type" => $this->type,
            "title" => $this->title,
            "text" => $this->text
        ];
        return replace($message, "templates/common/message.html");
    }

}
