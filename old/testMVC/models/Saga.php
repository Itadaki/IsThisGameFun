<?php



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
