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
$LANG = array(
    "addvalue" => "Add value",
    "npp" => "Normal price power",
    "dpp" => "Discount price power",
    "gas" => "Gas",
    "water" => "Water",
    "edit" => "Edit",
    "date" => "Date",
    "usage" => "Usage",
    "price" => "Price",
    "difference" => "Difference",
    "average" => "Average",
    "average2" => "Average",
    "day" => "Day",
    "editdelete" => "Edit/Delete",
    "help" => "Help",
    "menu" => "Menu",
    "info" => "Info",
    "helptext" => "Input format for dates is: MM-DD-YYYY. The graph needs at least 3 items to show the difference. With one item it will be empty.",
    "infotext" => 'This is a power/gas/water usage tracker written in PHP. It uses a JSON text file for the values (<a href="power.json">click here to see it</a>) and the visual side is created with the <a href="http://getbootstrap.com/">Bootstrap framework.</a></p><hr /><p>The script is available for download under an permissive open source license on <a href="http://raymii.org">Raymii.org</a> and was written by Remy van Elst.',
    "mainpage" => "Main page",
    "monthlyoverview" => "Monthly overview",
    "additem" => "Add item",
    "value" => "Value",
    "total" => "Total",
    "nodata" => "Error: No data to display. Please add it.",
    "emptyjsonarray" => "Error: JSON array does not contain items I can work with.",
    "noaction" => "Error: no action given.",
    "goback" => "Go back and try again please.",
    "itemnotfound" => "Error: item not found.",
    "enumeric" => "Error: you have to give me a numeric value. Error code x89",
    "edateformat" => "Error: the date you have me is not valid. The format is: MM-DD-YYYY. Error code x72.",
    "edateexist" => "Error: this date already exists. Please edit or delete the corresponding item first. Error code x293",
    "etypenotvalid" => "Error: The type you gave is not valid. Error code x62.",
    "actionsuccess" => "The action completed without errors.",
    "noredirect" => "You will now be redirected to the homepage. If that does not happen, please click here.",
    "efailjsonwrite" => "Failure. The item could not be added. Please check if the JSON database file exists and is writable.",
    "eitemnotfound" => "Error: The item was not found. Please go back and try again.",
    "enovalidaction" => "Error: the action you want me to to is not valid. ",
    "nodatatograph" => "No data to graph or display yet. Please add it.",
    "prices" => "Prices",
    "pricestext" => "Current prices:<br><ul><li>Power <sup>high kWh</sup>: " . $currency . " " . $NPPprice . "</li><li>Power <sup>low kWh</sup>: " . $currency . " " . $DPPprice . "</li><li>Gas <sup>m3</sup>: " . $currency . " " . $GASprice . "</li><li>Water <sup>m3</sup>: " . $currency . " " . $H2Oprice . "</li></ul>",
    "downloadcsv" => "CSV Export",
    "nppdppoverlay" => "High/Low overlay",
    "" => ""
    );

?>
