<div style="width: 700px;padding:5px;margin-left: 20px;">
    <style>
        .plt{
            display: inline-block;
            margin-right: 10px;
            width: 100px;
        }
        *{margin-top: 10px;}
    </style>
    <form>
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

        include_once './conexion.php';
        include_once './getGames.php';
        $conexion = conexion();

//Crear select de juegos
        $games = getGamesAlphabetical('\w*', 1000);
        $select = "<select id='games'>";
        foreach ($games as $game) {
            $select .= "<option value='{$game['id']}'>{$game['name']}</option>";
        }
        $select .= '</select><br>';
        echo $select;


//Crear select plataformas
        $platforms = getPlatforms();
        $checkboxes = '';
        foreach ($platforms as $platform) {
            $checkboxes .= "<div class='plt'><input class='chk' type='checkbox' id='{$platform['short_name']}' value='{$platform['id']}' name='platforms[]'/><label for='{$platform['short_name']}'>{$platform['short_name']}</label></div>";
        }
        echo $checkboxes;
        ?>
        <br>
        <input type="button" value="Generar" onclick="add()">
        <input type="reset" value="Reset" onclick="document.getElementById('querys').innerHTML =''">
        <br><textarea id="querys" cols="95" rows="40"></textarea>
    </form>
    <script>
        function add() {
            var game = document.getElementById('games');
            var allPlatforms = document.getElementsByClassName('chk');

            for (var i = 0; i < allPlatforms.length; i++) {
                if (allPlatforms[i].checked) {
                    var comment = "# " + game.options[game.selectedIndex].innerHTML + " for " + allPlatforms[i].id;
                    var query = "INSERT IGNORE INTO `isthisgamefun`.`game_platform` (`game`, `platform`) VALUES (" + game.value + ", " + allPlatforms[i].value + ");";
                    document.getElementById('querys').innerHTML += comment + "\n" + query + "\n";
                }
            }
        }
        function clear() {
            var allPlatforms = document.getElementsByClassName('chk');

            for (var i = 0; i < allPlatforms.length; i++) {
                allPlatforms[i].checked = false;
            }
        }
        document.getElementById('games').onchange = clear;
    </script>
</div>