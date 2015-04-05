<?php

// Copyright (c) 2012 Remy van Elst
// Permission is hereby granted, free of charge, to any person obtaining a copy
// of this software and associated documentation files (the "Software"), to deal
// in the Software without restriction, including without limitation the rights
// to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
// copies of the Software, and to permit persons to whom the Software is
// furnished to do so, subject to the following conditions:

// The above copyright notice and this permission notice shall be included in
// all copies or substantial portions of the Software.

// THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
// IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
// FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
// AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
// LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
// OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
// THE SOFTWARE.


$file = file_get_contents("power.json") or die("Cant open JSON file. Does it exist?");
$json_a = json_decode($file, true) or die("Cant decode JSON file. Is it a valid JSON file?");

include("config.php");
include("functions.php");
include("header.php");
echo "    <div class='page-header'>";
echo '      <h1> '.$productname.' </h1>';
echo "    </div>";
echo "  </div>";
echo "</div>";
echo "<div class='row'>";
echo "<div class='col-md-8 col-md-offset-1'>";

echo '<ul class="nav nav-tabs">';
echo '<li class="active"><a href="#npp" role="tab" data-toggle="tab">'.$LANG["npp"].'</a></li>';
echo '<li><a href="#dpp" role="tab" data-toggle="tab">'.$LANG["dpp"].'</a></li>';
echo '<li><a href="#gas" role="tab" data-toggle="tab">'.$LANG["gas"].'</a></li>';
echo '<li><a href="#h2o" role="tab" data-toggle="tab">'.$LANG["water"].'</a></li>';
echo "</ul>";

if(is_array($json_a)) {		

echo '<div class="tab-content">';
	# start npp
	echo '<div id="npp" class="tab-pane fade in active">';
	echo"<h2>".$LANG["npp"]."</h2>";

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
	echo"<h2>".$LANG["dpp"]."</h2>";

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
	echo"<h2>".$LANG["gas"]."</h2>";

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
	echo"<h2>".$LANG["water"]."</h2>";

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
