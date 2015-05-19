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
 * Description of sagas
 *
 * @author Diego Rodríguez Suárez-Bustillo
 */
class sagas extends Controller {

    public function index($args = array()) {
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
        return $this->build();
    }

    public function details($args = array()) {
        if (count($args) > 0 && is_numeric($args[0])) {
            $saga = getSagaById($args[0]);
            if ($saga != null) {
                $subData = $saga->getDataArray();
                $subData['vote_balance'] = replace((array) $subData['vote_balance'], "templates/common/vote-balance.html");


                $sagaHtml = replace($subData, 'templates/admin/sagas/saga-details.html');

                $data['sagas'] = $sagaHtml;
                $template = "templates/sagas/index.html";
                $this->body = replace($data, $template);

                return $this->build();
            } else {
                header('Location: ../../main');
            }
        } else {
            header('Location: main');
        }
    }

}
