<?php
/*
#Copyright (c) 2012 Remy van Elst
#Permission is hereby granted, free of charge, to any person obtaining a copy
#of this software and associated documentation files (the "Software"), to deal
#in the Software without restriction, including without limitation the rights
#to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
#copies of the Software, and to permit persons to whom the Software is
#furnished to do so, subject to the following conditions:
#
#The above copyright notice and this permission notice shall be included in
#all copies or substantial portions of the Software.
#
#THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
#IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
#FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
#AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
#LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
#OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
#THE SOFTWARE.
*/

#JSON File database.
$file = "power.json";
#Check if PHP can open it.
$jsonfile = file_get_contents($file) or die("Cannot open JSON file. Does it exist?");
# Check if it is valid JSON.
$json_a = json_decode($jsonfile, true) or die("Cannot decode JSON file. Is it valid?");

#Include the header
include("header.php");
include("functions.php");


#Check if we get an action
if (empty($_GET['action'])) {
	echo "".$LANG["noaction"]." \n<br /><a href=\"index.php\">".$LANG["goback"].".</a>";
} elseif (isset($_GET['action']) && $_GET['action'] == 'edit' ) {
#User wants to edit a value.
	#sanitize the ID.
	$id=htmlspecialchars($_GET['id']);
	#We need to match the ID to the item.
	$found=0;
	echo "<h2>".$LANG["edit"]."</h2>";
	#Loop through the entire JSON file.
	foreach ($json_a as $item => $value) {
		#Is it a match, then show the edit form.
		if ($item == $id) {
			$found = 1;
			echo "<form name=\"edit\" action=\"action.php\" method=\"GET\">";
			echo "<input name=\"content\" type=\"text\" value=\"";
			echo $value['content'];
			echo "\"></input>";
			echo "<input type=\"hidden\" name=\"id\" value=\"".  $id ."\"></input>";		
			echo "<input type=\"hidden\" name=\"action\" value=\"update\"></input>";
			echo "<input type=\"submit\" name=\"submit\" value=\"Submit\"></input>";
			echo "</form>";
			echo "<p />";
		}
	}		

	if ($found == 0) {
		#We don't have a match.
		echo $LANG["itemnotfound"];
	} 
	
}  elseif (isset($_GET['submit']) && $_GET['action'] == 'add' && !empty($_GET['content']) && !empty($_GET['type']) && !empty($_GET['date'])) {
	#User wants to add a item.
	#I use a sub-random ID for the items. It consists out of the EPOCH for the item-date plus some randomness. This is the randomness.
	$id5=substr(md5(rand()), 0, 20);
	$value=htmlspecialchars($_GET['content']);
	$itemcontent=htmlspecialchars($_GET['content']);
	$type=htmlspecialchars($_GET['type']);

	#Time for some falidation..
	#We only want numeric shizzle, we cannot graph text	
	if(!is_numeric($value)) {
		die($LANG["enumeric"]);
	}
	$date=htmlspecialchars($_GET['date']);	

	#is it a date I want?
	if(!preg_match('/([0-9]{2}-[0-9]{2}-[0-9]{4})/', $date)){
		die($LANG["edateformat"]);
	}

	#does the date already exists
	$addfilter = array("type" => $type);
	$filt_a=arrayFilter($json_a, $addfilter, true);
	foreach ((array) $filt_a as $item => $loopvalue) {

		if($loopvalue['date'] == $date) {
				die($LANG["edateexist"]);
			}
		}

	$type=htmlspecialchars($_GET['type']);
	#Do I accept this type?
	$typevalid=0;
	if($type == "NPP" or $type == "DPP" or $type == "GAS" or $type == "H2O") {
		$typevalid=1;
	}
	if($typevalid == 0) {
		die($LANG["etypenotvalid"]);
	}
	# because stackoverflow told me to.
	$datecomma=str_replace('-', '/', $date);
	#epoch of item date
	$dateepoch=strtotime($datecomma);
	#real ID
	$id=$dateepoch . "x000" . $id5;
	$current = file_get_contents($file);
	$current = json_decode($current, TRUE);
	$json_a = array($id => array("content" => $value, "date" => $date, "type" => $type));
	
	if(is_array($current)) {
		# why array merge?
		$current = array_merge_recursive($json_a, $current);
	} else {
		$current = $json_a;
	}
	#sort the array by date (EPOCH FTW)
	#If the sorting goes wrong, the graphs are wrong.
	ksort($current);
	$current=json_encode($current);	
	if(file_put_contents($file, $current, LOCK_EX)) {
		echo $LANG["itemadded"]."<br /> \n";
		echo"<a href=\"index.php\">".$LANG["noredirect"]."</a>";
		?>
		<script type="text/javascript">
		window.location = "index.php"
		</script>
		<?php
	} else {
		echo $LANG["efailjsonwrite"];
	}
} elseif (isset($_GET['submit']) && $_GET['action'] == 'update' && !empty($_GET['id']) && !empty($_GET['content'])) {
#update value
	$id=htmlspecialchars($_GET['id']);
	$replacedvalue=htmlspecialchars($_GET['content']);
	#Time for some falidation..
	#We only want numeric shizzle, we cannot graph text	
	if(!is_numeric($replacedvalue)) {
		die($LANG["enumeric"]);
	}

	foreach ($json_a as $item => $value) {
		if ($item == $id) {
			$found = 1;

			$current = file_get_contents($file);
			$current = json_decode($current, TRUE);
			$json_a = array($id => array("content" => $replacedvalue));

			$replaced = array_replace_recursive($current, $json_a);
			$replaced = json_encode($replaced);
			
			if(file_put_contents($file, $replaced, LOCK_EX)) {
				echo $LANG["actionsuccess"]."<br /> \n";
				echo"<a href=\"index.php\">".$LANG["noredirect"]."</a>";
				?>
				<script type="text/javascript">
				window.location = "index.php"
				</script>
				<?php
			} else {
				echo $LANG["efailjsonwrite"];
			}
		}
	}
	if ($found==0) {
		echo "<a href=\"index.php\">".$LANG["eitemnotfound"]."</a>";
	}
	
} elseif (isset($_GET['action']) && $_GET['action'] == 'delete' && !empty($_GET['id'])) {
#delete task
	$id=htmlspecialchars($_GET['id']);
	foreach ($json_a as $item => $value) {
		if ($item == $id) {
			$found = 1;
			$current = file_get_contents($file);
			$current = json_decode($current, TRUE);
			unset($current[$id]);
			$deleted = json_encode($current);
			ksort($deleted);		
			if(file_put_contents($file, $deleted, LOCK_EX)) {
				echo $LANG["actionsuccess"]."<br /> \n";
				echo"<a href=\"index.php\">".$LANG["noredirect"]."</a>";
				?>
				<script type="text/javascript">
				window.location = "index.php"
				</script>
				<?php
			} else {
				echo $LANG["efailjsonwrite"];
				}
			}
		}
		if ($found==0) {
			echo "<a href=\"index.php\">".$LANG["eitemnotfound"]."</a>";
		}
	} else {
		echo $LANG["enovalidaction"]."\n<br />Code x03. ";
	}	
	echo "</div>";

	include("footer.php");

	?>
