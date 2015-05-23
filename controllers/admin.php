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
 * Description of admin
 *
 * @author Diego Rodríguez Suárez-Bustillo
 */
class admin extends Controller {

    /**
     * Using the constructor to prevent no-admins to get this menu
     */
    public function __construct() {
        parent::__construct();
        if (!isAdmin()) {
            header('Location: ./main');
        }
    }

    /**
     * 
     * 
     * 
     */
    public function index($args = array()) {
        $data['body'] = file_get_contents('templates/admin/index.html');

        $template = "templates/generic.html";
        $this->body = replace($data, $template);

        return $this->build();
    }

    /**
     * 
     * 
     * 
     */
    public function users($args = array()) {
        if (isset($args[0])) {
            //Empty the templates to prevent using them in this partial views
            $this->top = '';
            $this->menu = '';
            $this->body = '';
            if ($args[0] == 'edit' && isset($args[1]) && is_string($args[1])) {
                $this->body = $this->generateUserForm($args[1]);
            } else if ($args[0] == 'save' && isset($_POST['action'])) {
                $this->saveUser();
                header('Location: ../users');
            } else if ($args[0] == 'delete' && isset($_POST['action'])) {
                $this->deleteUser();
                header('Location: ../users');
            }
        } else {
            $users = getUsers();
            $template = "templates/admin/users/user-list.html";
            $userHtml = '';
            foreach ($users as $user) {
                $userHtml .= replace($user, $template);
            }
            $data['list'] = $userHtml;
            //Adding sidebar menu
            $data['sidebar'] = file_get_contents("templates/admin/sidebar-menu.html");

            $template = "templates/admin/users/users.html";
            $this->body = replace($data, $template);
        }
        return $this->build();
    }

    /**
     * 
     * 
     * 
     */
    public function games($args = array()) {
        if (isset($args[0])) {
            //Empty the templates to prevent using them in this partial views
            $this->top = '';
            $this->menu = '';
            $this->body = '';
            if ($args[0] == 'add') {
                $this->body = $this->generateGameForm();
            } else if ($args[0] == 'edit' && isset($args[1]) && is_numeric($args[1])) {
                $this->body = $this->generateGameForm($args[1]);
            } else if ($args[0] == 'save' && isset($_POST['action'])) {
                $this->saveGame();
                header('Location: ../games');
            } else if ($args[0] == 'delete' && isset($_POST['action'])) {
                $this->deleteGame();
                header('Location: ../games');
            }
        } else {
            $games = getGamesAlphabetically(10000);
            $template = "templates/admin/games/game-list.html";
            $gameHtml = '';
            foreach ($games as $game) {
                $repl['id'] = $game->id;
                $repl['name'] = $game->name;

                $repl['platforms'] = '';
                foreach ($game->platforms as $platform) {
                    $plat['id'] = $platform->id;
                    $plat['name'] = $platform->name;
                    $plat['short_name'] = $platform->short_name;
                    $repl['platforms'].= replace($plat, "templates/common/platform.html");
                }

                if ($game->saga != null) {
                    $repl['saga'] = $game->saga->name;
                } else {
                    $repl['saga'] = '';
                }

                $gameHtml .= replace($repl, $template);
            }
            $data['list'] = $gameHtml;
            //Adding sidebar menu
            $data['sidebar'] = file_get_contents("templates/admin/sidebar-menu.html");

            $template = "templates/admin/games/games.html";
            $this->body = replace($data, $template);
        }
        return $this->build();
    }

    /**
     * 
     * 
     * 
     */
    public function platforms($args = array()) {
        if (isset($args[0])) {
            //Empty the templates to prevent using them in this partial views
            $this->top = '';
            $this->menu = '';
            $this->body = '';
            if ($args[0] == 'add') {
                $this->body = $this->generatePlatformForm();
            } else if ($args[0] == 'edit' && isset($args[1]) && is_numeric($args[1])) {
                $this->body = $this->generatePlatformForm($args[1]);
            } else if ($args[0] == 'save' && isset($_POST['action'])) {
                $this->savePlatform();
                header('Location: ../platforms');
            } else if ($args[0] == 'delete' && isset($_POST['action'])) {
                $this->deletePlatform();
                header('Location: ../platforms');
            }
        } else {
            $platforms = getPlatforms();
            $template = "templates/admin/platforms/platform-list.html";
            $platformsHtml = '';
            foreach ($platforms as $platform) {
                $repl['id'] = $platform->id;
                $repl['name'] = $platform->name;
                $repl['short_name'] = $platform->short_name;
                $platformsHtml .= replace($repl, $template);
            }
            $data['list'] = $platformsHtml;
//Adding sidebar menu
            $data['sidebar'] = file_get_contents("templates/admin/sidebar-menu.html");

            $template = "templates/admin/platforms/platforms.html";
            $this->body = replace($data, $template);
        }
        return $this->build();
    }

    /**
     * 
     * 
     * 
     */
    public function sagas($args = array()) {
        if (isset($args[0])) {
            //Empty the templates to prevent using them in this partial views
            $this->top = '';
            $this->menu = '';
            $this->body = '';
            if ($args[0] == 'add') {
                $this->body = $this->generateSagaForm();
            } else if ($args[0] == 'edit' && isset($args[1]) && is_numeric($args[1])) {
                $this->body = $this->generateSagaForm($args[1]);
            } else if ($args[0] == 'save' && isset($_POST['action'])) {
                $this->saveSaga();
                header('Location: ../sagas');
            } else if ($args[0] == 'delete' && isset($_POST['action'])) {
                $this->deleteSaga();
                header('Location: ../sagas');
            }
        } else {
            $sagas = getSaga();
            $template = "templates/admin/sagas/saga-list.html";
            $sagasHtml = '';
            foreach ($sagas as $saga) {
                $repl = $saga->getDataArray();
                //No quiero lios con el objeto VB
                unset($repl['vote_balance']);
                $sagasHtml .= replace($repl, $template);
            }
            $data['list'] = $sagasHtml;
//Adding sidebar menu
            $data['sidebar'] = file_get_contents("templates/admin/sidebar-menu.html");

            $template = "templates/admin/sagas/sagas.html";
            $this->body = replace($data, $template);
        }
        return $this->build();
    }

    /**
     * 
     * 
     * 
     */
    private function generateGameForm($id = null) {
        //Check if is new game or edit
        $is_edit = $id != null;
        $action = $is_edit ? 'Edit' : 'Create';
        //Set the templates
        $base_template = "templates/admin/games/new-edit-game.html";
        $cover_template = "templates/admin/games/new-edit-game-cover.html";
        $saga_dropdown_template = "templates/admin/games/new-edit-game-saga-dropdown.html";
        $saga_option_template = "templates/admin/games/new-edit-game-saga-option.html";
        $platform_checkbox_template = "templates/admin/games/new-edit-game-checkbox.html";

        if ($is_edit) {
            $game = getGame($id);

            //Raw data from game
            $data['id'] = $game->id;
            $data['name'] = $game->name;
            $data['description'] = $game->description;
//            $data['cover'] = $game->cover;
        } else {
            $data['id'] = '';
            $data['name'] = '';
            $data['description'] = '';
//            $data['cover'] = '';
        }
        $data['action'] = $action;

        //Data for the saga
        $all_sagas = getSaga();
        //Generate all HTML for each option
        $saga_option_html = '<option>--Select a Saga--</option>';
        $check_saga = $is_edit && $game->saga != null;
        foreach ($all_sagas as $saga) {
            $temp_data['id'] = $saga->id;
            $temp_data['name'] = $saga->name;
            $temp_data['selected'] = '';
            //Set selected for edit
            if ($check_saga && $game->saga->id == $temp_data['id']) {
                $temp_data['selected'] = 'selected';
            }
            $saga_option_html .= replace($temp_data, $saga_option_template);
        }

        //Insert the options into the select
        //And save on data array for posterior replacement
        $saga_data['options'] = $saga_option_html;
        $data['sagas'] = replace($saga_data, $saga_dropdown_template);



        //Platforms available
        if ($is_edit) {
            $platforms_of_the_game = [];
            foreach ($game->platforms as $plaform) {
                $platforms_of_the_game[] = $plaform->id;
            }
        }
        $all_platforms = getPlatforms();
        $platform_checkboxes_html = '';
        foreach ($all_platforms as $platform) {
            $temp_data['id'] = $platform->id;
            $temp_data['name'] = $platform->name;
            $temp_data['short_name'] = $platform->short_name;
            $temp_data['checked'] = '';
            //Set checked for edit
            if ($is_edit && in_array($temp_data['id'], $platforms_of_the_game)) {
                $temp_data['checked'] = 'checked';
            }
            $platform_checkboxes_html .= replace($temp_data, $platform_checkbox_template);
        }

        $data['platforms'] = $platform_checkboxes_html;


        //Show the image or not
        $data['cover'] = '';
        if ($is_edit) {
            $data['cover'] = replace(array("cover" => $game->cover), $cover_template);
        }
        $html = replace($data, $base_template);

        return $html;
    }

    private function saveGame() {
        $action = $_POST['action'];
        $name = $_POST['name'];
        $description = $_POST['description'];
        $platforms = $_POST['platforms']; //array
        $saga = $_POST['saga']; //id

        $cover = proccessUploadedImage($name); //name or null

        if ($action == "Create") {
            insertGame($name, $description, $platforms, $saga, $cover);
        } else if ($action == "Edit") {
            $id = $_POST['id'];
            updateGame($id, $name, $description, $platforms, $saga, $cover);
        }
    }
    
    private function deleteGame() {
        global $db, $config;

        $delete = [
            "id" => $_POST['id']
        ];
        //Delete game
        $db->delete($config['t_games'], $delete);
        $debug_error = $db->error();
    }

    /**
     * 
     * 
     * 
     */
    private function generateUserForm($nick) {
        //Set the templates
        $base_template = "templates/admin/users/edit-user.html";
        $level_dropdown_template = "templates/admin/users/edit-user-dropdown.html";
        $level_option_template = "templates/admin/users/edit-user-option.html";
        $levels = getEnumUserLevel();

        $user = getUser($nick, true);

        $user_level_option_html = '';

        foreach ($levels as $level) {
            //Set selected
            if ($user['user_level'] == $level) {
                $temp_data['selected'] = 'selected';
            } else {
                $temp_data['selected'] = '';
            }
            $temp_data['name'] = $level;
            $user_level_option_html .= replace($temp_data, $level_option_template);
        }

        //Insert the options into the select
        //And save on data array for posterior replacement
        $level_data['options'] = $user_level_option_html;
        $user['levels'] = replace($level_data, $level_dropdown_template);
        $user['action'] = 'Edit';
        $html = replace($user, $base_template);

        return $html;
    }

    private function saveUser() {
        global $db, $config;

        $insert = [
            "user_nick" => $_POST['nick'],
            "user_email" => $_POST['email'],
            "user_level" => $_POST['level']
        ];
        //Update user games table
        $db->update($config['t_users'], $insert, ["user_id" => $_POST['id']]);
        $debug_error = $db->error();
    }

    private function deleteUser() {
        global $db, $config;

        $delete = [
            "user_id" => $_POST['id']
        ];
        //Update user games table
        $db->delete($config['t_users'], $delete);
        $debug_error = $db->error();
    }

    /**
     * 
     * 
     * 
     */
    private function generatePlatformForm($id = null) {
        //Check if is new platform or edit
        $is_edit = $id != null;
        $action = $is_edit ? 'Edit' : 'Create';
        //Set the templates
        $base_template = "templates/admin/platforms/new-edit-platform.html";

        if ($is_edit) {
            $platform = getPlatformById($id);
            $data['id'] = $platform['id'];
            $data['name'] = $platform['name'];
            $data['shortname'] = $platform['short_name'];
        } else {
            $data['id'] = '';
            $data['name'] = '';
            $data['shortname'] = '';
        }

        $data['action'] = $action;
        $html = replace($data, $base_template);

        return $html;
    }

    private function savePlatform() {
        global $db, $config;
        $action = $_POST['action'];

        $name = $_POST['name'];
        $shortName = $_POST['shortname'];

        if ($action == "Create") {
            insertPlatform($name, $shortName);
        } else if ($action == "Edit") {
            $id = $_POST['id'];
            updatePlatform($id, $name, $shortName);
        }
    }

    private function deletePlatform() {
        global $db, $config;

        $delete = [
            "id" => $_POST['id']
        ];

        $db->delete($config['t_platforms'], $delete);
        $debug_error = $db->error();
    }

    /**
     * 
     * 
     * 
     */
    private function generateSagaForm($id = null) {
        //Check if is new platform or edit
        $is_edit = $id != null;
        $action = $is_edit ? 'Edit' : 'Create';
        //Set the templates
        $base_template = "templates/admin/sagas/new-edit-saga.html";
        $logo_template = "templates/admin/sagas/new-edit-saga-logo.html";

        if ($is_edit) {
            $saga = getSagaById($id);
            $data = $saga->getDataArray();
//            unset($data['vote_balance']);
//            $data['id'] = $saga['id'];
//            $data['name'] = $saga['name'];
//            $data['description'] = $saga['description'];
            //Show the image or not
            if ($is_edit) {
                $data['logo'] = replace(array("logo" => $data['logo']), $logo_template);
            }
        } else {
            $data['id'] = '';
            $data['name'] = '';
            $data['description'] = '';
            $data['logo'] = '';
        }

        $data['action'] = $action;
        $html = replace($data, $base_template);

        return $html;
    }

    private function saveSaga() {
        global $db, $config;
        $action = $_POST['action'];

        $name = $_POST['name'];
        $description = $_POST['description'];
        
        $logo = proccessUploadedImage($name, 'logo', 'logos/'); //name or null

        if ($action == "Create") {
            insertSaga($name, $description,$logo);
        } else if ($action == "Edit") {
            $id = $_POST['id'];
            updateSaga($id, $name, $description,$logo);
        }
    }

    private function deleteSaga() {
        global $db, $config;

        $delete = [
            "id" => $_POST['id']
        ];

        $db->delete($config['t_sagas'], $delete);
        $debug_error = $db->error();
    }

}
