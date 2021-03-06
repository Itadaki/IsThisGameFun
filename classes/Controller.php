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
 * Base controller class for each controller
 *
 * @author Diego Rodríguez Suárez-Bustillo
 */
class Controller {

    public $top;
    public $menu;
    public $body;
    public $bottom;
    public $data = array();
    private $section = array();
    private $breadcrumbs = array();

    public function __construct($top = "templates/frame/top.html", $menu = "templates/frame/menu.html", $bottom = "templates/frame/bottom.html") {
        $this->top = file_get_contents($top);
        $this->menu = getScriptOutput('templates/frame/create-menu.php');
        $this->bottom = file_get_contents($bottom);

        //ADD a key for each section in the menu
        //ControllerName-active
        $this->section['main-active'] = '';
        $this->section['games-active'] = '';
        $this->section['user-active'] = '';
        $this->section['admin-active'] = '';
        $this->section['about-active'] = '';
        $this->section['contact-active'] = '';

        $this->breadcrumbs = ['breadcrumbs' => ''];

        $className = get_class($this) . '-active';
        $this->section[$className] = 'active';


//        $this->addTemplatesToData();
    }

    private function addTemplatesToData() {
        $this->data['top'] = $this->top;
        $this->data['body'] = $this->body;
        $this->data['menu'] = $this->menu;
        $this->data['bottom'] = $this->bottom;
    }

    private function buildMenu() {
        $this->menu = replace($this->section, $this->menu, true);
        $this->menu = replace($this->breadcrumbs, $this->menu, true);
    }

    public function build() {
        global $config;
        $this->buildMenu();
        $html = $this->top . $this->menu . $this->body . $this->bottom;
        $data['server_root'] = $config['server_root'];
        $html = replace($data, $html, true);
        return $html;
    }

    protected function generatePaginator($totalRows, $itemsPerPage, $pageNumber) {
        $totalPages = ceil($totalRows / $itemsPerPage);
        $position = (($pageNumber - 1) * $itemsPerPage);
        //LIMIT $position, $item_per_page
    }

    protected function isLogged() {
        return isset($_SESSION['user_nick']);
    }

    protected function isAdmin() {
        if (isset($_SESSION['user_level']) && $_SESSION['user_level'] == 'admin') {
            return true;
        }
        return false;
    }

    protected function isAjax() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }

    protected function isPost() {
        return $_SERVER['REQUEST_METHOD'] == 'POST';
    }

    protected function isGet() {
        return $_SERVER['REQUEST_METHOD'] == 'GET';
    }

    protected function emptyTemplates() {
        $this->top = '';
        $this->menu = '';
        $this->body = '';
        $this->bottom = '';
    }

    protected function generateBreadcrumbs($tree = array()) {

        $bc = '<ol class="breadcrumb voffset3 bg-gray2">';
        $c = 1;
        foreach ($tree as $name => $link) {
            if (!empty($link)){
                $link ="<a href='$link'>$name</a>";
            } else {
                $link = $name;
            }
            if ($c++ != count($tree)) {
                $bc .= "<li>$link</li>";
            } else {
                $bc .= "<li class='active'>$link</li>";
            }
        }
        $bc .= '</ol>';
        $this->breadcrumbs = ['breadcrumbs' => $bc];
    }

}
