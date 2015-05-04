<?php

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