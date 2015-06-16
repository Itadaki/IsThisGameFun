<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


/**
 * Controller for cookies section
 *
 * @author drodriguezsuarezbust
 */
class cookies extends Controller {

    public function index($args = array()) {
        return $this->policy();
    }
    
    public function policy($args = array()) {
        $template = 'templates/cookies/policy.html';
        $this->generateBreadcrumbs([
            "Home" => '{server_root}',
            "Cookies" => '{server_root}cookies',
            "Policy" => '{server_root}admin/policy'
        ]);
        $this->body = file_get_contents($template);
        return $this->build();
    }
}
