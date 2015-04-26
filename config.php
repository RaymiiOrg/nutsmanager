<?php
/*
    Copyright (C) 2015 Remy van Elst
    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU Affero General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
    GNU Affero General Public License for more details.
    You should have received a copy of the GNU Affero General Public License
    along with this program. If not, see <http://www.gnu.org/licenses/>.
*/
$havecheappower = 0;
$havepower = 0;
$havegas = 0;
$havewater = 0;

# per kWh, high price
$NPPprice=0.2217;
# per kWh, low price
$DPPprice=0.2072;
# per m3
$GASprice=0.6129;
# per m3
$H2Oprice=0.914;
# price symbol/name
$currency="EUR";


$productname = "NutsManager";
$productversion = "v0.0.6";


// Do not edit
require('language.nl.php');
date_default_timezone_set('UTC');

error_reporting(E_ALL & ~E_NOTICE);
$jsonfile = "power.json";
$file = file_get_contents($jsonfile) or die("Cannot open JSON file. Does it exist?.");
$json_a = json_decode($file, true) or die("Cannot decode JSON file. Is it a valid JSON file?");

?>
