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
 * Description of Model
 *
 * @author Diego Rodríguez Suárez-Bustillo
 */
class Model {

    /**
     * Transform the current object parameters into associative array<br>
     * Object data of the parameter <b>WILL NOT</b> be transformed
     * @return array
     */
    public function getDataArray() {
        return get_object_vars($this);
    }

    /**
     * Transform the current object parameters into associative array<br>
     * Object data of the parameter <b>WILL</b> be transformed
     * @return array
     */
    public function getFullDataArray() {
        return $this->object_to_array($this);
    }

    /**
     * Transforms recursively an object into an associative array
     * @param object $obj
     * @return array
     */
    private function object_to_array($obj) {
        if (is_object($obj))
            $obj = (array) $obj;
        if (is_array($obj)) {
            $new = array();
            foreach ($obj as $key => $val) {
                $new[$key] = $this->object_to_array($val);
            }
        } else
            $new = $obj;
        return $new;
    }

}
