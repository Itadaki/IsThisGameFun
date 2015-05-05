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

//Include config file
include_once './config.php';

//Include the composer dependencies
require_once './vendor/autoload.php';

//Include all the functions
foreach (glob("./functions/*.php") as $filename)
{
    include_once $filename;
}

//Include all the model classes
foreach (glob("./models/*.php") as $filename)
{
    include_once $filename;
}

//Include API
foreach (glob("./api/*.php") as $filename)
{
    include_once $filename;
}

//Include all the controllers
foreach (glob("./controllers/*.php") as $filename)
{
    include_once $filename;
}