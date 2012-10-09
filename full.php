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


# First open the JSON file
$file = file_get_contents("power.json") or die("Cant open JSON file. Does it exist? Error code x51.");
#now check if it is a valid file
$json_a = json_decode($file, true) or die("Cant decode JSON file. Is it a valid JSON file? Error code x61.");
$havecheappower = 0;
$havepower = 0;
$havegas = 0;
$havewater = 0;

include("functions.php");
include("header.php");

echo '<h1> NutsManager </h1>';
?>
<ul class="tabs center">
	<li><a href="#npp">Normal Power</a></li>
	<li><a href="#dpp">Discount Power</a></li>
	<li><a href="#gas">Gas</a></li>
	<li><a href="#h2o">Water</a></li>
</ul>




<?


if(is_array($json_a)) {		

	
	# start npp
	echo "<div id=\"npp\" class=\"tab-content\">";
	echo"<h2>Normal Price Power</h2>";

	foreach ($json_a as $item => $value) {
		if ($value['type'] == "NPP") {	
			$havenpp=1;
		}
	}

	if ($havenpp == 0) {
		echo "No data to display yet. Please add it.";

	} else {

		
		for ($i=1; $i < 13; $i++) { 
    		#Months
			createdatearray($json_a,"npp",$i);

		}
		
	}
	echo "</div>";
	# end npp

	# start dpp
	echo "<div id=\"dpp\" class=\"tab-content\">";
	echo"<h2>Discount Price Power</h2>";

	foreach ($json_a as $item => $value) {
		if ($value['type'] == "DPP") {	
			$havedpp=1;
		}
	}

	if ($havedpp == 0) {
		echo "No data to display yet. Please add it.";

	} else {

		
		for ($i=1; $i < 13; $i++) { 
			createdatearray($json_a,"dpp",$i);
		}	
	}
	echo "</div>";
	# end dpp


	# start gas
	echo "<div id=\"gas\" class=\"tab-content\">";
	echo"<h2>Gas</h2>";

	foreach ($json_a as $item => $value) {
		if ($value['type'] == "GAS") {	
			$havegas=1;
		}
	}

	if ($havegas == 0) {
		echo "No data to display yet. Please add it.";

	} else {

		
		for ($i=1; $i < 13; $i++) { 
			createdatearray($json_a,"gas",$i);
		}
		
	}
	echo "</div>";
	# end gas

	# start water
	echo "<div id=\"h2o\" class=\"tab-content\">";
	echo"<h2>Water</h2>";

	foreach ($json_a as $item => $value) {
		if ($value['type'] == "H2O") {	
			$haveh2o=1;
		}
	}

	if ($haveh2o == 0) {
		echo "No data to display yet. Please add it.";

	} else {

		
		for ($i=1; $i < 13; $i++) { 
			createdatearray($json_a,"h2o",$i);
		}
		
	}
	echo "</div>";
	# END WATER



} else {
		# no json array
	echo "No items in file. Unknown error.";
}

?>
</div><!--col_9 -->

<?php
include("footer.php");
?>
