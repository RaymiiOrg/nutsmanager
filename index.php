<?php
ob_start();
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


include("config.php");
include("functions.php");
include("header.php");



## Call the form function from the functions file.
showinputform("action.php");

if(is_array($json_a)) {   

  # start overlay npp dpp
  echo "<div id='npp-dpp-overlay'>";
  echo "<h2><span class=\"glyphicon glyphicon-certificate\"></span> <span class=\"glyphicon glyphicon-bed\"></span> ".$LANG["nppdppoverlay"]."</h2>";

  foreach ((array) $json_a as $item => $value) {
    if ($value['type'] == "DPP") {  
      $havedpp += 1;
    }
    if ($value['type'] == "NPP") {  
      $havenpp += 1;
    }
  }

  if ($havedpp == 0 || $havenpp == 0) {
    echo $LANG["nodatatograph"];
  } else {
    if ($havedpp >= 3 && $havenpp >= 3) {
      maketrippleoverlaygraph($json_a, ["NPP","DPP","TOT"], ["High","Low","Total"], ["yellow","green", "red"], 10);
    }
  }
  echo "</div>";
  # end overlay npp dpp

  # start npp
  echo "<div id='npp'>";
  echo "<h2><span class=\"glyphicon glyphicon-flash\"></span> ".$LANG["npp"]."</h2>";

  foreach ((array) $json_a as $item => $value) {
    if ($value['type'] == "NPP") {  
      $havenpp += 1;
    }
  }

  if ($havenpp == 0) {
    echo $LANG["nodatatograph"];
  } else {
    if ($havenpp >= 3) {
      makegraph($json_a,"NPP","yellow",10);
    }
    showitems($json_a,$LANG["npp"],"NPP",10,$NPPprice,"");
  }
  echo "</div>";
  # end npp

  # start dpp
  echo "<div id='dpp'>";
  echo "<h2><span class=\"glyphicon glyphicon-flash\"></span> ".$LANG["dpp"]."</h2>";

  foreach ((array) $json_a as $item => $value) {
    if ($value['type'] == "DPP") {  
      $havedpp += 1;
    }
  }

  if ($havedpp == 0) {
    echo $LANG["nodatatograph"];
  } else {
    if ($havedpp >= 3) {
      makegraph($json_a,"DPP","green",10);
    }
    showitems($json_a,$LANG["dpp"],"DPP",10,$DPPprice,"");
  }
  echo "</div>";
  # end dpp


  # start gas
  echo "<div id='gas'>";
  echo "<h2><span class=\"glyphicon glyphicon-fire\"></span> ".$LANG["gas"]."</h2>";

  foreach ((array) $json_a as $item => $value) {
    if ($value['type'] == "GAS") {  
      $havegas += 1;
    }
  }

  if ($havegas == 0) {
    echo $LANG["nodatatograph"];
  } else {
    if ($havegas >= 3) {
      makegraph($json_a,"GAS","purple",10);
    }
    showitems($json_a,$LANG["gas"],"GAS",10,$GASprice,"");
  }
  echo "</div>";
  # end gas

  # start water
  echo '<div id="water">';
  echo "<h2><span class=\"glyphicon glyphicon-tint\"></span> ".$LANG["water"]."</h2>";

  foreach ((array) $json_a as $item => $value) {
    if ($value['type'] == "H2O") {  
      $havewater += 1;
    }
  }

  if ($havewater == 0) {
    echo $LANG["nodatatograph"];
  } else {
    if ($havewater >= 3) {
      makegraph($json_a,"H2O","cyan",10);
    }
    showitems($json_a,$LANG["water"],"H2O",10,$H2Oprice,"");
  } 
  echo "</div>";
  # end water
} else {
  echo $LANG["nodata"];
}

?>
</div>

<?php 
include("footer.php");

?>
