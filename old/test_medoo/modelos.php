<?php

class Game {

    public $id;
    public $name;
    public $cover;
    public $vote_balance;
    public $platforms;
    public $saga;
    public $my_vote;
    public $user_vote;

    public function __construct($id, $name, $cover = "default.png", VoteBalance $vote_balance = NULL, array $platforms = NULL, Saga $saga = NULL, $my_vote = NULL, $user_vote = NULL) {
        $this->id = (int) $id;
        $this->name = $name;
        $this->cover = $cover;
        $this->vote_balance = $vote_balance;
        $this->platforms = $platforms;
        $this->saga = $saga;
        $this->my_vote = $my_vote;
        $this->user_vote = $user_vote;
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getCover() {
        return $this->cover;
    }

    public function getVote_balance() {
        return $this->vote_balance;
    }

    public function getPlatforms() {
        return $this->platforms;
    }
    
    public function getSaga() {
        return $this->saga;
    }

    public function getMy_vote() {
        return $this->my_vote;
    }

    public function getUser_vote() {
        return $this->user_vote;
    }

    public function setId($id) {
        $this->id = (int) $id;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setCover($cover) {
        $this->cover = $cover;
    }

    public function setVote_balance($vote_balance) {
        $this->vote_balance = $vote_balance;
    }

    public function setPlatforms($platforms) {
        $this->platforms = $platforms;
    }

    public function setSaga($saga) {
        $this->saga = $saga;
    }

    public function setMy_vote($my_vote) {
        $this->my_vote = $my_vote;
    }

    public function setUser_vote($user_vote) {
        $this->user_vote = $user_vote;
    }

}

class Platform {

    public $id;
    public $name;
    public $short_name;
    public $icon;

    public function __construct($id, $name, $short_name, $icon = "default.png") {
        $this->id = (int) $id;
        $this->name = $name;
        $this->short_name = $short_name;
        $this->icon = $icon;
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getShort_name() {
        return $this->short_name;
    }

    public function getIcon() {
        return $this->icon;
    }

    public function setId($id) {
        $this->id = (int) $id;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setShort_name($short_name) {
        $this->short_name = $short_name;
    }

    public function setIcon($icon) {
        $this->icon = $icon;
    }

}

class Saga {

    public $id;
    public $name;
    public $logo;
    public $vote_balance;

    public function __construct($id, $name, $logo = "default.png", VoteBalance $vote_balance = NULL) {
        $this->id = (int) $id;
        $this->name = $name;
        $this->logo = $logo;
        $this->vote_balance = $vote_balance;
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getLogo() {
        return $this->logo;
    }

    public function getVote_balance() {
        return $this->vote_balance;
    }

    public function setId($id) {
        $this->id = (int) $id;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setLogo($logo) {
        $this->logo = $logo;
    }

    public function setVote_balance($vote_balance) {
        $this->vote_balance = $vote_balance;
    }

}

class VoteBalance {

    public $positive_votes;
    public $negative_votes;
    public $total_votes;
    public $positive_percentage;

    public function __construct($positive_votes, $negative_votes) {
        $this->positive_votes = (int) $positive_votes;
        $this->negative_votes = (int) $negative_votes;
        $total = $positive_votes + $negative_votes;
        $this->total_votes = $total;
        $this->positive_percentage = (float) number_format($positive_votes * 100 / $total, 2);
    }

}