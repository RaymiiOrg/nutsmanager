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

include("config.php");
include("functions.php");

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
			echo $LANG[strtolower($value["type"])] . " for ";
			echo htmlspecialchars($value["date"]) . " <br>(current value: <i>";
			echo htmlspecialchars($value["content"]) . "</i>): <br><br>";
			echo "<form name=\"edit\" action=\"action.php\" method=\"GET\">";
			echo "<input name=\"content\" type=\"text\" value=\"";
			echo $value['content'];
			echo "\"></input>";
			echo "<input type=\"hidden\" name=\"id\" value=\"".  $id ."\"></input>";		
			echo "<input type=\"hidden\" name=\"action\" value=\"update\"></input> ";
			echo "<input type=\"submit\" name=\"submit\" value=\"Submit\"></input> ";
			echo "<input type=\"button\" value=\"Back\" onClick=\"history.go(-1);return true;\">";
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
	$id = gen_uuid();
	$current = file_get_contents($jsonfile);
	$current = json_decode($current, TRUE);
	$json_a = array($id => array("content" => $value, "date" => $dateepoch, "type" => $type));
	
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
	if(file_put_contents($jsonfile, $current, LOCK_EX)) {
		header("Location: index.php");
		echo $LANG["itemadded"]."<br /> \n";
		echo"<a href=\"index.php\">".$LANG["noredirect"]."</a>";
	} else {
		echo $LANG["efailjsonwrite"];
	}
} elseif (isset($_GET['submit']) && $_GET['action'] == 'update' && !empty($_GET['id']) && !empty($_GET['content'])) {
#update value
	$id=htmlspecialchars($_GET['id']);
	$replacedvalue=htmlspecialchars($_GET['content']);
	#Time for some validation..
	#We only want numeric shizzle, we cannot graph text	
	if(!is_numeric($replacedvalue)) {
		die($LANG["enumeric"]);
	}

	foreach ($json_a as $item => $value) {
		if ($item == $id) {
			$found = 1;

			$current = file_get_contents($jsonfile);
			$current = json_decode($current, TRUE);
			$json_a = array($id => array("content" => $replacedvalue));

			$replaced = array_replace_recursive($current, $json_a);
			$replaced = json_encode($replaced);
			
			if(file_put_contents($jsonfile, $replaced, LOCK_EX)) {
				header("Location: index.php");
				echo $LANG["actionsuccess"]."<br /> \n";
				echo"<a href=\"index.php\">".$LANG["noredirect"]."</a>";
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
			$current = file_get_contents($jsonfile);
			$current = json_decode($current, TRUE);
			unset($current[$id]);
			$deleted = json_encode($current);
			ksort($deleted);		
			if(file_put_contents($jsonfile, $deleted, LOCK_EX)) {
				header("Location: index.php");
				echo $LANG["actionsuccess"]."<br /> \n";
				echo"<a href=\"index.php\">".$LANG["noredirect"]."</a>";
			} else {
				echo $LANG["efailjsonwrite"];
				}
			}
		}
		if ($found==0) {
			echo "<a href=\"index.php\">".$LANG["eitemnotfound"]."</a>";
		}
	} elseif (isset($_GET['action']) && $_GET['action'] == 'csv' ) {
		downloadcsv($json_a);
	} else {
		echo $LANG["enovalidaction"]."\n<br />Code x03. ";
	}	
	echo "</div>";

	include("footer.php");

	?>
