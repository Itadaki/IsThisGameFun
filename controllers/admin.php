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
 * Controller for admin section
 *
 * @author Diego Rodríguez Suárez-Bustillo
 */
class admin extends Controller {

    /**
     * Using the constructor to prevent no-admins to get this menu
     */
    public function __construct() {
        global $config;
        parent::__construct();
        if (!$this->isAdmin()) {
//            header("Location: {$config['server_root']}main");
//            header('HTTP/1.0 403 Forbidden');
//            die;
            (new Forbidden())->send();
        }
    }

    /**
     * 
     * 
     * 
     */
    public function index($args = array()) {
        global $config;
        header("Location: ".$config['server_root']."admin/games");
    }

    /**
     * 
     * 
     * 
     */
    public function users($args = array(), $messages = array()) {
        if (isset($args[0])) {
            if ($args[0] == 'edit' && isset($args[1]) && is_string($args[1])) {
                $this->emptyTemplates();
                $this->body = $this->generateUserForm($args[1]);
            } else if ($args[0] == 'save' && isset($_POST['action'])) {
                return $this->saveUser();
//                header('Location: ../users');
            } else if ($args[0] == 'delete' && isset($_POST['action'])) {
                return $this->deleteUser();
//                header('Location: ../users');
            }
        } else {
            $users = getUsers();
            $userListTemplate = "templates/admin/users/user-list.html";
            $userHtml = '';
            foreach ($users as $user) {
                $userHtml .= replace($user, $userListTemplate);
            }
            $data['list'] = $userHtml;
            //Adding sidebar menu
            $data['sidebar'] = file_get_contents("templates/admin/sidebar-menu.html");

            //DISPLAY MESSAGES
            $data['messages'] = '';
            if ($messages) {
                foreach ($messages as $message) {
                    $data['messages'].=$message;
                }
            }

            $usersTemplate = "templates/admin/users/users.html";
            $this->body = replace($data, $usersTemplate);
        }
        
        $this->generateBreadcrumbs([
            "Home" => '{server_root}',
            "C-Panel" => '{server_root}admin',
            "Users" => '{server_root}admin/users'
        ]);
        
        return $this->build();
    }

    /**
     * 
     * 
     * 
     */
    public function games($args = array(), $messages = array()) {
        if (isset($args[0])) {
            if ($args[0] == 'add') {
                $this->emptyTemplates();
                $this->body = $this->generateGameForm();
            } else if ($args[0] == 'edit' && isset($args[1]) && is_numeric($args[1])) {
                $this->emptyTemplates();
                $this->body = $this->generateGameForm($args[1]);
            } else if ($args[0] == 'save' && isset($_POST['action'])) {
                return $this->saveGame();
//                header('Location: ../games');
            } else if ($args[0] == 'delete' && isset($_POST['action'])) {
                return $this->deleteGame();
//                header('Location: ../games');
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
                    $plat = $platform->getDataArray();
//                    $plat['id'] = $platform->id;
//                    $plat['name'] = $platform->name;
//                    $plat['short_name'] = $platform->short_name;
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

            //DISPLAY MESSAGES
            $data['messages'] = '';
            if ($messages) {
                foreach ($messages as $message) {
                    $data['messages'].=$message;
                }
            }

            $template = "templates/admin/games/games.html";
            $this->body = replace($data, $template);
        }
        
        $this->generateBreadcrumbs([
            "Home" => '{server_root}',
            "C-Panel" => '{server_root}admin',
            "Games" => '{server_root}admin/games'
        ]);
        
        return $this->build();
    }

    /**
     * 
     * 
     * 
     */
    public function platforms($args = array(), $messages = array()) {
        if (isset($args[0])) {
            if ($args[0] == 'add') {
                $this->emptyTemplates();
                $this->body = $this->generatePlatformForm();
            } else if ($args[0] == 'edit' && isset($args[1]) && is_numeric($args[1])) {
                $this->emptyTemplates();
                $this->body = $this->generatePlatformForm($args[1]);
            } else if ($args[0] == 'save' && isset($_POST['action'])) {
                return $this->savePlatform();
//                header('Location: ../platforms');
            } else if ($args[0] == 'delete' && isset($_POST['action'])) {
                return $this->deletePlatform();
//                header('Location: ../platforms');
            }
        } else {
            $platforms = getPlatforms();
            $template = "templates/admin/platforms/platform-list.html";
            $platformsHtml = '';
            foreach ($platforms as $platform) {
                $repl = $platform->getDataArray();
//                $repl['id'] = $platform->id;
//                $repl['name'] = $platform->name;
//                $repl['short_name'] = $platform->short_name;
                $platformsHtml .= replace($repl, $template);
            }
            $data['list'] = $platformsHtml;
//Adding sidebar menu
            $data['sidebar'] = file_get_contents("templates/admin/sidebar-menu.html");

            //DISPLAY MESSAGES
            $data['messages'] = '';
            if ($messages) {
                foreach ($messages as $message) {
                    $data['messages'].=$message;
                }
            }

            $template = "templates/admin/platforms/platforms.html";
            $this->body = replace($data, $template);
        }
        
        $this->generateBreadcrumbs([
            "Home" => '{server_root}',
            "C-Panel" => '{server_root}admin',
            "Platforms" => '{server_root}admin/platforms'
        ]);
        
        return $this->build();
    }

    /**
     * 
     * 
     * 
     */
    public function sagas($args = array(), $messages = array()) {
        if (isset($args[0])) {
            if ($args[0] == 'add') {
                $this->emptyTemplates();
                $this->body = $this->generateSagaForm();
            } else if ($args[0] == 'edit' && isset($args[1]) && is_numeric($args[1])) {
                $this->emptyTemplates();
                $this->body = $this->generateSagaForm($args[1]);
            } else if ($args[0] == 'save' && isset($_POST['action'])) {
                $this->saveSaga();
//                header('Location: ../sagas');
            } else if ($args[0] == 'delete' && isset($_POST['action'])) {
                $this->deleteSaga();
//                header('Location: ../sagas');
            }
        } else {
            $sagas = getSaga();
            $template = "templates/admin/sagas/saga-list.html";
            $sagasHtml = '';
            foreach ($sagas as $saga) {
                $repl = $saga->getDataArray();
                //I don't want a mess with VB, so unset
                unset($repl['vote_balance']);
                $sagasHtml .= replace($repl, $template);
            }
            $data['list'] = $sagasHtml;
//Adding sidebar menu
            $data['sidebar'] = file_get_contents("templates/admin/sidebar-menu.html");

            //DISPLAY MESSAGES
            $data['messages'] = '';
            if ($messages) {
                foreach ($messages as $message) {
                    $data['messages'].=$message;
                }
            }

            $template = "templates/admin/sagas/sagas.html";
            $this->body = replace($data, $template);
        }
        
        $this->generateBreadcrumbs([
            "Home" => '{server_root}',
            "C-Panel" => '{server_root}admin',
            "Sagas" => '{server_root}admin/sagas'
        ]);
        
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
        $saga_option_html = '';
        $check_saga = $is_edit && $game->saga != null;
        foreach ($all_sagas as $saga) {
            $temp_data = $saga->getDataArray();
//            $temp_data['id'] = $saga->id;
//            $temp_data['name'] = $saga->name;
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
            $temp_data = $platform->getDataArray();
//            $temp_data['id'] = $platform->id;
//            $temp_data['name'] = $platform->name;
//            $temp_data['short_name'] = $platform->short_name;
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
        $name = cleanString($_POST['name']);
        $description = cleanString($_POST['description']);
        if (isset($_POST['platforms'])) {
            $platforms = $_POST['platforms']; //array
        } else {
            $platforms = [];
        }
        $saga = $_POST['saga']; //id

        $cover = proccessUploadedImage($name); //name or false
        
        if ($cover == false && $cover != null) {
            $messages[] = (new Message('danger', 'Error', "Unexpected error."))->getMessage();
            unset($_POST['action']);
            return $this->games([], $messages);
        }

        if ($action == "Create") {
            $error = insertGame($name, $description, $platforms, $saga, $cover);
        } else if ($action == "Edit") {
            $id = $_POST['id'];
            $error = updateGame($id, $name, $description, $platforms, $saga, $cover);
        }
        if ($error) {
            $messages[] = (new Message('danger', 'Error', $error))->getMessage();
            unset($_POST['action']);
            return $this->games([], $messages);
        }
        $messages[] = (new Message('success', 'Success', " on '$action game $name'."))->getMessage();
        return $this->games(array(), $messages);
    }

    private function deleteGame() {
        global $db, $config;

        $delete = [
            "id" => $_POST['id']
        ];
        //Delete game
        $db->delete($config['t_games'], $delete);
        $error = handleError();
        if ($error) {
            $messages[] = (new Message('danger', 'Error', $error))->getMessage();
            return $this->games([], $messages);
        }
        $messages[] = (new Message('success', 'Success', " on 'Delete game {$_POST['id']}'."))->getMessage();
        return $this->games(array(), $messages);
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
            "user_nick" => cleanString($_POST['nick']),
            "user_email" => cleanString($_POST['email']),
            "user_level" => $_POST['level']
        ];
        //Update user games table
        $db->update($config['t_users'], $insert, ["user_id" => $_POST['id']]);
        $error = handleError();
        if ($error) {
            $messages[] = (new Message('danger', 'Error', $error))->getMessage();
            return $this->users([], $messages);
        }
        $messages[] = (new Message('success', 'Success', " on 'Edit user ".cleanString($_POST['nick'])."'."))->getMessage();
        return $this->users(array(), $messages);
    }

    private function deleteUser() {
        global $db, $config;

        $delete = [
            "user_id" => $_POST['id']
        ];
        //Update user games table
        $db->delete($config['t_users'], $delete);
        $error = handleError();
        if ($error) {
            $messages = (new Message('danger', 'Error', $error))->getMessage();
            return $this->users([], $messages);
        }
        $messages[] = (new Message('success', 'Success', " on 'Delete user {$_POST['id']}'."))->getMessage();
        return $this->users(array(), $messages);
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
            $data = $platform->getDataArray();
//            dd($data);
//            $data['id'] = $platform['id'];
//            $data['name'] = $platform['name'];
//            $data['shortname'] = $platform['short_name'];
        } else {
            $data['id'] = '';
            $data['name'] = '';
            $data['short_name'] = '';
        }

        $data['action'] = $action;
        $html = replace($data, $base_template);

        return $html;
    }

    private function savePlatform() {
        global $db, $config;
        $action = $_POST['action'];

        $name = cleanString($_POST['name']);
        $shortName = cleanString($_POST['shortname']);

        if ($action == "Create") {
            $error = insertPlatform($name, $shortName);
        } else if ($action == "Edit") {
            $id = $_POST['id'];
            $error = updatePlatform($id, $name, $shortName);
        }

        if ($error) {
            $messages[] = (new Message('danger', 'Error', $error))->getMessage();
            return $this->platforms([], $messages);
        }
        $messages[] = (new Message('success', 'Success', " on '$action platform $name'."))->getMessage();
        return $this->platforms(array(), $messages);
    }

    private function deletePlatform() {
        global $db, $config;

        $delete = [
            "id" => $_POST['id']
        ];

        $db->delete($config['t_platforms'], $delete);
        $error = handleError();
        if ($error) {
            $messages[] = (new Message('danger', 'Error', $error))->getMessage();
            return $this->platforms([], $messages);
        }
        $messages[] = (new Message('success', 'Success', " on 'Delete platform {$_POST['id']}'."))->getMessage();
        return $this->platforms(array(), $messages);
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

        $name = cleanString($_POST['name']);
        $description = cleanString($_POST['description']);

        $logo = proccessUploadedImage($name, 'logo', 'logos/'); //name or false
        
        if ($logo == false && $logo != null) {
            $messages[] = (new Message('danger', 'Error', "Unexpected error."))->getMessage();
            unset($_POST['action']);
            return $this->sagas([], $messages);
        }

        if ($action == "Create") {
            $error = insertSaga($name, $description, $logo);
        } else if ($action == "Edit") {
            $id = $_POST['id'];
            $error = updateSaga($id, $name, $description, $logo);
        }
        if ($error) {
            $messages[] = (new Message('danger', 'Error', $error))->getMessage();
            return $this->sagas([], $messages);
        }
        $messages[] = (new Message('success', 'Success', " on '$action saga $name'."))->getMessage();
        return $this->sagas(array(), $messages);
    }

    private function deleteSaga() {
        global $db, $config;

        $delete = [
            "id" => $_POST['id']
        ];

        $db->delete($config['t_sagas'], $delete);
        $error = handleError();
        if ($error) {
            $messages[] = (new Message('danger', 'Error', $error))->getMessage();
            return $this->sagas([], $messages);
        }
        $messages[] = (new Message('success', 'Success', " on 'delete saga {$_POST['id']}'."))->getMessage();
        return $this->sagas(array(), $messages);
    }

}
