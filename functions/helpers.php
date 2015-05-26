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
 * Handles the error on DB object <b>in admin sections</b><br>
 * Returns an string with the error or false
 * @return string|false
 */
function handleError() {
    global $db;
    //Handle error
    $error = $db->error();
    if ($error[0] != 0000) {
        return "An $error[1] error ocurred!";
    }
    return false;
}

/**
 * Handles the error on DB object <b>in public sections</b><br>
 * @return string|false
 */
function handleDbError(){
    global $db;
    //Handle error
    $error = $db->error();
    if ($error[0] != 0000) {
        (new Error($error[1],"An error ocurred! - $error[2]"))->displayError();
    }
}

/**
 * 
 * @param string $field The name of the field to be checked
 * @param array $pendingFields The array containing the pending fields
 * @param array $wrongFields The array containing the wrong fields
 * @return string Classes that match with error or empty
 */
function validateField($field, $pendingFields, $wrongFields) {
    if (in_array($field, $pendingFields)) {
        return 'has-warning has-feedback';
    } elseif (in_array($field, $wrongFields)) {
        return 'has-error has-feedback';
    }
    return '';
}

/**
 * 
 * @param string $fieldName The name of the field
 * @return string The value of the field if it's in $_POST
 */
function setValue($fieldName) {
    if (isset($_POST[$fieldName])) {
        return $_POST[$fieldName];
    }
}

/**
 * Check if the current session is from an admin level user
 * @return boolean Is or not admin
 */
function isAdmin() {
    if (isset($_SESSION['user_level']) && $_SESSION['user_level'] == 'admin') {
        return true;
    }
    return false;
}
