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
