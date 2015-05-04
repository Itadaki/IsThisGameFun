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
 * Description of Controller
 *
 * @author Diego Rodríguez Suárez-Bustillo
 */
interface IController {

    public function view($model = null);
}

class Controller implements IController {

    /**
     * La vista de la clase
     * @var View
     */
    private $viewName;
    private $view;

    public function __construct() {
        //Inicializar el nombre de la vista adecuadamente (sera el nombre del fichero)
        $viewName = str_replace("Controller", "", get_class($this));
        $this->viewName = $viewName . "View";
//        $this->view = new $this->viewName();
        $this->view = new View($this->viewName);
        echo "<br>Creada clase ". get_class($this);
        var_dump($this->view);
    }

    public function view($model = null) {
        //Ejecuta la vista
        //Utiliza el modelo
        return $this->view->execute();
    }

}





////////
//VIEW//
////////

interface IView {
    function execute();
}

class View implements IView {

    private $template;
    private $viewName;
    
    public function __construct($viewName) {
        $this->viewName = $viewName;
        echo "<br>Creada clase ". get_class($this);
    }

    public function execute() {
        //Cojer la vista
        //Ejecutarla usando el modelo
        //Devolver el string resultante        
        
        return "Se ha llamado a execute en " . get_class($this);
    }

}

