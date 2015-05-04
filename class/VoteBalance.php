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
 * Description of VoteBalance
 *
 * @author Diego Rodríguez Suárez-Bustillo
 */
class VoteBalance extends Model {

    public $positive_votes;
    public $negative_votes;
    public $total_votes;
    public $positive_percentage;

    public function __construct($positive_votes = 1, $negative_votes = 0) {
        $this->positive_votes = (int) $positive_votes;
        $this->negative_votes = (int) $negative_votes;
        $total = $positive_votes + $negative_votes;
        $this->total_votes = $total;
        $this->positive_percentage = (int) number_format($positive_votes * 100 / $total, 2);
    }

}
