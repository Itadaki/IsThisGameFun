<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Main {

    public function Index($id) {
        return "INDEX from MAIN" . ($id ? " Y tenemos ID! $id" : "");
    }

}

class Games {

    public function Index($id) {
        return "INDEX from GAMES" . ($id ? " Y tenemos ID! $id" : "");
    }

    public function Game($id) {
        return "GAME from GAMES" . ($id ? " Y tenemos ID! $id" : "");
    }

    public function Best($id) {
        return "BEST from GAMES" . ($id ? " Y tenemos ID! $id" : "");
    }

    public function Most($id) {
        return "MOST from GAMES" . ($id ? " Y tenemos ID! $id" : "");
    }

}

class Controller {

    private $controller;
    private $action;
    private $id;

    public function __construct() {
        $this->controller = isset($_GET['section']) ? $_GET['section'] : "Main";
        $this->action = isset($_GET['action']) ? $_GET['action'] : "Index";
        $this->id = isset($_GET['id']) ? $_GET['id'] : "";
    }

    public function Launch() {
        //Existe el controlador?
        if (class_exists($this->controller)) {
            $control = new $this->controller();
            //Existe el metodo?
            if (method_exists($control, $this->action)) {
                return $control->{$this->action}($this->id);
                //No existe el metodo
                //Llama al metodo INDEX
            } else {
                return $control->{"Index"}($this->id);
            }
            //No existe el controlador
            //Llama al controlador MAIN metodo INDEX
        } else {
            return (new Main())->Index($this->id);
        }
    }

}


$c = new Controller();
echo $c->Launch();
echo "<br><a href='?'>Index from Main</a> ";
echo "<br><a href='?section=games'>Index from Games</a> ";
echo "<br><a href='?section=games&action=best'>Best from Games</a> ";
echo "<br><a href='?section=games&action=best&id=66'>Best from Games with ID</a> ";