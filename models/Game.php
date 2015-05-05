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
 * Description of Game
 *
 * @author Diego Rodríguez Suárez-Bustillo
 */
class Game extends Model {

    public $id;
    public $name;
    public $description;
    public $cover;
    public $vote_balance;
    public $platforms;
    public $saga;
    public $my_vote;
    public $user_vote;

    public function __construct($id, $name, $description = "", $cover = "default.png", VoteBalance $vote_balance = NULL, array $platforms = NULL, Saga $saga = NULL, $my_vote = NULL, $user_vote = NULL) {
//        if ($vote_balance == NULL){
//            $vote_balance = new VoteBalance();
//        }
        $this->id = (int) $id;
        $this->name = $name;
        $this->description = $description;
        $this->cover = $cover;
        $this->vote_balance = $vote_balance;
        $this->platforms = $platforms;
        $this->saga = $saga;
        $this->my_vote = $my_vote;
        $this->user_vote = $user_vote;
    }

}
