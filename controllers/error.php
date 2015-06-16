<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Controller for error
 *
 * @author drodriguezsuarezbust
 */
class error extends Controller {

    public function index($args = array()) {

        $template = "templates/error/index.html";
        $body = replace([], $template);
        
        //Add the html to the body
        $this->body = $body;
        
        $this->generateBreadcrumbs([
            "" => ''
        ]);
        
        //Build the pieces of the web and return to client
        return $this->build();
    }

}
