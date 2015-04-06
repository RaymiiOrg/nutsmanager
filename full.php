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

echo '<ul class="nav nav-tabs">';
echo '<li class="active"><a href="#npp" data-toggle="tab">'.$LANG["npp"].'</a></li>';
echo '<li><a href="#dpp" data-toggle="tab">'.$LANG["dpp"].'</a></li>';
echo '<li><a href="#gas" data-toggle="tab">'.$LANG["gas"].'</a></li>';
echo '<li><a href="#h2o" data-toggle="tab">'.$LANG["water"].'</a></li>';
echo "</ul>";

if(is_array($json_a)) {   
  echo '<div class="tab-content">';
  # start npp
  echo '<div id="npp" class="tab-pane fade in active">';
  echo"<h2><span class=\"glyphicon glyphicon-flash\"></span> ".$LANG["npp"]."</h2>";

  foreach ($json_a as $item => $value) {
    if ($value['type'] == "NPP") {  
      $havenpp=1;
    }
  }

  if ($havenpp == 0) {
    echo "".$LANG["nodate"]."";
  } else {
    for ($i=1; $i < 13; $i++) { 
        #Months
      createdatearray($json_a,"npp",$i);
    }
  }
  echo "</div>";
  # end npp

  # start dpp
  echo "<div id=\"dpp\" class=\"tab-pane fade\">";
  echo"<h2><span class=\"glyphicon glyphicon-flash\"></span> ".$LANG["dpp"]."</h2>";

  foreach ($json_a as $item => $value) {
    if ($value['type'] == "DPP") {  
      $havedpp=1;
    }
  }

  if ($havedpp == 0) {
    echo "".$LANG["nodata"]."";
  } else {
    for ($i=1; $i < 13; $i++) { 
      createdatearray($json_a,"dpp",$i);
    } 
  }
  echo "</div>";
  # end dpp

  # start gas
  echo "<div id=\"gas\" class=\"tab-pane fade\">";
  echo"<h2><span class=\"glyphicon glyphicon-fire\"></span> ".$LANG["gas"]."</h2>";

  foreach ($json_a as $item => $value) {
    if ($value['type'] == "GAS") {  
      $havegas=1;
    }
  }

  if ($havegas == 0) {
    echo "".$LANG["nodata"]."";
  } else {
    for ($i=1; $i < 13; $i++) { 
      createdatearray($json_a,"gas",$i);
    }
  }
  echo "</div>";
  # end gas

  # start water
  echo "<div id=\"h2o\" class=\"tab-pane fade\">";
  echo"<h2><span class=\"glyphicon glyphicon-tint\"></span> ".$LANG["water"]."</h2>";
  foreach ($json_a as $item => $value) {
    if ($value['type'] == "H2O") {  
      $haveh2o=1;
    }
  }

  if ($haveh2o == 0) {
    echo "".$LANG["nodata"]."";
  } else {
    for ($i=1; $i < 13; $i++) { 
      createdatearray($json_a,"h2o",$i);
    }
  }
  echo "</div>";
  # END WATER

} else {
    # no json array
  echo "".$LANG["emptyjsonarray"]."";
}

?>
</div>
</div>

<?php
include("footer.php");
?>
