<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


/**
 * Description of cookies
 *
 * @author drodriguezsuarezbust
 */
class cookies extends Controller {

    public function index($args = array()) {
        $this->body = '';
        return $this->build();
    }
    
    public function policy($args = array()) {
        $template = 'templates/cookies/policy.html';
        $this->body = file_get_contents($template);
        return $this->build();
    }
}
