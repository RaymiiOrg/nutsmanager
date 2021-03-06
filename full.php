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
echo '<li class="active"><a href="#nppdppoverlay" data-toggle="tab">'.$LANG["nppdppoverlay"].'</a></li>';
echo '<li><a href="#npp" data-toggle="tab">'.$LANG["npp"].'</a></li>';
echo '<li><a href="#dpp" data-toggle="tab">'.$LANG["dpp"].'</a></li>';
echo '<li><a href="#gas" data-toggle="tab">'.$LANG["gas"].'</a></li>';
echo '<li><a href="#h2o" data-toggle="tab">'.$LANG["water"].'</a></li>';
echo "</ul>";

if(is_array($json_a)) { 


  foreach ((array) $json_a as $item => $value) {
    if ($value['type'] == "DPP") {  
      $havedpp += 1;
    }
    if ($value['type'] == "NPP") {  
      $havenpp += 1;
    }
  }


  echo '<div class="tab-content">';
  # start total
  echo '<div id="nppdppoverlay" class="tab-pane fade in active">';
  echo"<h2><span class=\"glyphicon glyphicon-flash\"></span> ".$LANG["nppdppoverlay"]."</h2>";
  if ($havedpp == 0 || $havenpp == 0) {
    echo $LANG["nodatatograph"];
  } else {
    if ($havedpp >= 3 && $havenpp >= 3) {
      maketrippleoverlaygraph($json_a, ["TOT","NPP","DPP"], ["Total","High","Low"], ["red","yellow", "green"], $havenpp);
    }
  }
  for ($i=1; $i < 13; $i++) { 
      createdoubledatearray($json_a,"npp","dpp",$i);
  } 
  echo '</div>';
  # start npp
  echo '<div id="npp" class="tab-pane fade in">';
  echo"<h2><span class=\"glyphicon glyphicon-flash\"></span> ".$LANG["npp"]."</h2>";


  if ($havenpp == 0) {
    echo "".$LANG["nodate"]."";
  } else {
    if ($havenpp >= 3) {
      makegraph($json_a,"NPP","yellow",$havenpp);
    }
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


  if ($havedpp == 0) {
    echo "".$LANG["nodata"]."";
  } else {
    if ($havedpp >= 3) {
      makegraph($json_a,"DPP","green",$havedpp);
    }
    for ($i=1; $i < 13; $i++) { 
      createdatearray($json_a,"dpp",$i);
    } 
  }
  echo "</div>";
  # end dpp

  # start gas
  echo "<div id=\"gas\" class=\"tab-pane fade\">";
  echo"<h2><span class=\"glyphicon glyphicon-fire\"></span> ".$LANG["gas"]."</h2>";
  $havegas = 0;
  foreach ($json_a as $item => $value) {
    if ($value['type'] == "GAS") {  
      $havegas += 1;
    }
  }

  if ($havegas == 0) {
    echo "".$LANG["nodata"]."";
  } else {
    if ($havegas >= 3) {
      makegraph($json_a,"GAS","purple",$havegas);
    }
    for ($i=1; $i < 13; $i++) { 
      createdatearray($json_a,"gas",$i);
    }
  }
  echo "</div>";
  # end gas

  # start water
  echo "<div id=\"h2o\" class=\"tab-pane fade\">";
  echo"<h2><span class=\"glyphicon glyphicon-tint\"></span> ".$LANG["water"]."</h2>";
  $haveh20 = 0;
  foreach ($json_a as $item => $value) {
    if ($value['type'] == "H2O") {  
      $haveh2o += 1;
    }
  }

  if ($haveh2o == 0) {
    echo "".$LANG["nodata"]."";
  } else {
    if ($haveh2o >= 3) {
      makegraph($json_a,"H2O","blue",$haveh2o);
    }
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
