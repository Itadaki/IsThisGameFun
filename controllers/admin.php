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

    public function index($args = array()) {
        $data['body'] = file_get_contents('templates/admin/index.html');

        $template = "templates/generic.html";
        $this->body = replace($data, $template);

        return $this->build();
    }

    public function users($args = array()) {
        $users = getUsers();
        $template = "templates/admin/users/user-list.html";
        $userHtml = '';
        foreach ($users as $user) {
            $user['server_root'] = '/isthisgamefun/';
            $userHtml .= replace($user, $template);
        }
        $data['list'] = $userHtml;

        $template = "templates/admin/users/users.html";
        $this->body = replace($data, $template);
        return $this->build();
        ;
    }

    public function games($args = array()) {
        $games = getAllGames(10000);
        $template = "templates/admin/games/game-list.html";
        $gameHtml = '';
        foreach ($games as $game) {
            $repl['id'] = $game->id;
            $repl['name'] = $game->name;
            $repl['server_root'] = '/isthisgamefun/';
            $gameHtml .= replace($repl, $template);
        }
        $data['list'] = $gameHtml;

        $template = "templates/admin/games/games.html";
        $this->body = replace($data, $template);
        return $this->build();
    }

    public function platforms($args = array()) {
        $platforms = getPlatforms();
        $template = "templates/admin/platforms/platform-list.html";
        $platformsHtml = '';
        foreach ($platforms as $platform) {
            $repl['id'] = $platform->id;
            $repl['name'] = $platform->name;
            $repl['short_name'] = $platform->short_name;
            $repl['server_root'] = '/isthisgamefun/';
            $platformsHtml .= replace($repl, $template);
        }
        $data['list'] = $platformsHtml;

        $template = "templates/admin/platforms/platforms.html";
        $this->body = replace($data, $template);
        return $this->build();
    }

    public function sagas($args = array()) {
        $sagas = getSaga();
        $template = "templates/admin/sagas/saga-list.html";
        $sagasHtml = '';
        foreach ($sagas as $saga) {
            $repl = $saga->getDataArray();
            //No quiero lios con el objeto VB
            unset($repl['vote_balance']);
            $repl['server_root'] = '/isthisgamefun/';
            $sagasHtml .= replace($repl, $template);
        }
        $data['list'] = $sagasHtml;

        $template = "templates/admin/sagas/sagas.html";
        $this->body = replace($data, $template);
        return $this->build();
    }

}
