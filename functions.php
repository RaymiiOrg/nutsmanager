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

function arrayFilter($arrHaystack, $arrFilter, $boolStrict = false) {
  $old_error_reporting = error_reporting(0); 
  error_reporting(E_ALL & ~E_NOTICE);
  if (!is_array($arrFilter)) {
    $arrFilter = array($arrFilter);
  }
  if (!is_array($arrHaystack)) {
    $arrHaystack = array($arrHaystack);
  }

  foreach ($arrHaystack as $strHKey => $objHValue) {
    if (is_array($objHValue)) {
      $boolFound = arrayFilter($objHValue, $arrFilter, $boolStrict);
    } else {
      $strHKey = strtolower($strHKey);
      $objHValue = strtolower($objHValue);
      foreach ($arrFilter as $strFKey => $objFValue) {
        $strFKey = strtolower($strFKey);
        $objFValue = strtolower($objFValue);
        $boolMatch = (($strFKey == $strHKey) AND ($objFValue == $objHValue));
        if ($boolMatch == 1) {
          if ($boolStrict) {
            unset($arrFilter[$strFKey]);
          } else {
            $arrFilter = array();
          }
        }     
        if (count($arrFilter) == 0) {
          return true;
        }
      }
    }
        
    if ($boolFound){
      $arrResult[$strHKey] = $objHValue;
    }
  }
  error_reporting($old_error_reporting);
  return $arrResult;    
}

function makegraph($array,$shortcode,$color,$maxitems) {
  global $LANG;
  $arrFilter = array("type" => $shortcode);
  $json_a = arrayFilter($array, $arrFilter, true);
  uasort($json_a, function ($i, $j) {
    $a = $i['date'];
    $b = $j['date'];
    if ($a == $b) return 0;
    elseif ($a > $b) return 1;
    else return -1;
  });
  echo "<div id=\"" . $shortcode . "-chart\">\n";
  echo "<div id=\"" . $shortcode . "graph\" style='width:600px;height:200px;'></div>\n";
  echo "<script type='text/javascript'>\n";
  echo "$(function () {\n";
  $codeitems=1;
  $lastcode=null;
  $totalitems=count($json_a);
  $start_loop=0;
  if(floatval($totalitems) > floatval($maxitems)) {
    $array_diff = floatval($totalitems) - floatval($maxitems);
    $start_loop = $array_diff;
  }
  echo "var d2 = [";
  foreach (array_slice($json_a, $start_loop) as $item => $value) {
    if($codeitems < $maxitems+1) {
      $date = $value['date'];
      $dt = new DateTime("@$date");
      if($codeitems > 1) {
        $codemin =  floatval($value['content']) - floatval($lastcode);
      } else {
        $codemin = 0;
      }
      if ($codeitems >= 2) {
        echo "[" . $dt->format('z') . ", " . $codemin . "], \n";
      }
      $codeitems+=1;
      $lastcode=$value['content'];
    }
  }
  echo "];\n";
  echo "$.plot(\n";
  echo "$(\"#" . $shortcode ."graph\"), \n";
  echo " [ d2 ], {\n";
  echo "colors: ['". $color ."'], \n";
  echo "lines: {show: true},\n";
  echo "points: {show: true}\n";
  echo "});\n";
  echo "});\n";
  echo "</script>\n";
  echo "</div>";
  unset($json_a);
  unset($totalitems);
}
# end makegraph function


function makeoverlaygraph($array,$shortcodes,$legends,$colors,$maxitems) {
  echo "<div id=\"" . implode("-", $shortcodes) . "-chart\">\n";
  echo "<div id=\"" . implode("-", $shortcodes) . "graph\" style='width:600px;height:200px;'></div>\n";
  echo "<script type='text/javascript'>\n";
  echo "$(function () {\n";

  foreach ($shortcodes as $key => $shortcode) {
      $arrFilter = array("type" => $shortcode);
      $json_a = arrayFilter($array, $arrFilter, true);
      uasort($json_a, function ($i, $j) {
        $a = $i['date'];
        $b = $j['date'];
        if ($a == $b) return 0;
        elseif ($a > $b) return 1;
        else return -1;
      });
      $codeitems=1;
      $lastcode=null;
      $totalitems=count($json_a);
      $start_loop=0;
      if(floatval($totalitems) > floatval($maxitems)) {
        $array_diff = floatval($totalitems) - floatval($maxitems);
        $start_loop = $array_diff;
      }
      echo "var d" . $key . " = [\n";
      foreach (array_slice($json_a, $start_loop) as $item => $value) {
        if($codeitems < $maxitems+1) {
          $date = $value['date'];
          $dt = new DateTime("@$date");
          if($codeitems > 1) {
            $codemin =  floatval($value['content']) - floatval($lastcode);
          } else {
            $codemin = 0;
          }
          if ($codeitems >= 2) {
            echo "[\"" . $dt->format('z') . "\", " . floatval($codemin) . "]";
            if ($codeitems == $maxitems) {
              echo "\n";
            } else {
              echo ",\n";
            }
          }
          $codeitems+=1;
          $lastcode=$value['content'];
        }
      }
      echo "];\n";
    } 
      echo "\n$.plot(\n";
      echo "  $(\"#" . implode("-", $shortcodes) . "graph\"),\n"; 
      echo "  [\n";
        foreach ($shortcodes as $key => $shortcode) {
          echo "{\n";
          echo "  label: \" " . $legends[$key] . "\",\n";
          echo "  data: d" . $key . ", \n";
          echo "  color: ['" . $colors[$key] . "'],\n";
          echo "  lines: {show: true},\n";
          echo "  points: {show: true}\n";
          echo "}";
          if ($key != count($shortcodes) - 1) {
            echo ",";
          }
        }
    echo "]);\n";
    echo "});\n";
  echo "</script>";
  echo "</div>";
  unset($json_a);
  unset($totalitems);
}
# end makeoverlaygraph function

function showitems($array,$name,$shortcode,$maxitems,$price,$outputformat) {
    global $LANG;
    #Get the currency value from outside the function
    global $currency;
    #Define the filter for the array filter
    $arrFilter = array("type" => $shortcode);
    #Filter the json file array with the above filter (filter on type $shortcode)
    $json_a=arrayFilter($array, $arrFilter, true);
    uasort($json_a, function ($i, $j) {
      $a = $i['date'];
      $b = $j['date'];
      if ($a == $b) return 0;
      elseif ($a > $b) return 1;
      else return -1;
    });
    
    #Define 2 empty arrays for average usage and average price
    $averageusage = array('');
    $averageprice = array('');

    $codeitems=1;
    $lastcode=null;
    $totalitems=count($json_a);
    
    #Set the normal start of the loop on the first item in the array
    $start_loop=0;
    #If we define a maximum number of items, then make sure the array loop and such start at that position
    #The newer items are later in the array.
    if(intval($totalitems) > intval($maxitems)) {
        $array_diff = floatval($totalitems) - floatval($maxitems);
        $start_loop = $array_diff;
    }

    if (!isset($outputformat)) {
        $outputformat=NULL;
    }
    # first do some formatting if needed
    switch ($outputformat) {
        case 'list':
            # do nothing
        echo "";
        break;
        
        default:
            # Tables, yay!
        echo "<table class=\"table table-striped\">";
        echo "<tr>";
        echo "<th>#</th>";
        echo "<th>".$LANG["date"]."</th>";
        echo "<th>".$LANG["usage"]."</th>";
        echo "<th>".$LANG["difference"]."</th>";
        echo "<th>".$LANG["price"]."</th>";
        echo "<th>".$LANG["edit"]."</th>";
        echo "</tr>";
        break;
    }
    
    foreach (array_slice($json_a, $start_loop) as $item => $value) {
        if ($value['type'] == $shortcode) { 
            if($codeitems < $maxitems+1) {
                if($codeitems > 1) {
                    $codemin =  floatval($value['content']) - floatval($lastcode);
                    $itemprice = $codemin * $price;
                    #Add the usage and price to the arrays for the averages
                    $averageusage[] = $codemin;
                    $averageprice[] = $itemprice;
                } else {
                    $codemin = 0;
                    $itemprice = 0;
                }

                switch ($outputformat) {
                    default:
                    echo "<tr>";
                    $date = $value['date'];
                    $dt = new DateTime("@$date");
                    echo "<td>" . $dt->format('z') . "</td>";
                    echo "<td>" . $dt->format('D d M Y ') . "</td>";
                    echo "<td>" . $value['content'] . "</td>";
                    if ($itemprice != 0) {
                                    #Difference
                        echo "<td>" . round($codemin,3) . '</td>';
                                    #Price                                        
                        echo "<td>". $currency.": ".round($itemprice,2)."</td>";
                    } else {
                                    #Difference
                        echo "<td> - </td>";
                                    #Price
                        echo "<td> - </td>";
                    }
                    echo "<td>";
                                    #Edit
                    echo "<a href=\"action.php?id=" .$item. "&action=edit\"><span class='glyphicon glyphicon-edit' aria-hidden='true'></span></a>";
                    echo " - ";            
                        #Delete
                    echo "<a href=\"action.php?id=" .$item. "&action=delete\" onclick=\"if (!confirm('Are you sure you want to delete this item?')) return false;\"><span class='glyphicon glyphicon-remove' aria-hidden='true'></span></a>";
                    echo "</tr>";
                    break;

                    case 'list':
                    $date = $value['date'];
                    $dt = new DateTime("@$date");
                    echo "<b>" . $codeitems . ":</b>"  . $dt->format('D d M Y ') . ' - ' . $name . ' usage: ' . $value['content'];              
                    if ($itemprice != 0) {
                        echo ' - '.$LANG["difference"] . round($codemin,3) . '.';
                        echo " [". $currency.": ".round($itemprice,2)."]";
                    }
                    echo " [ <a href=\"action.php?id=" .$item. "&action=edit\"><span class='glyphicon glyphicon-edit' aria-hidden='true'></span></a>";
                    echo " <a href=\"action.php?id=" .$item. "&action=delete\" onclick=\"if (!confirm('Are you sure you want to delete this item?')) return false;\"><span class='glyphicon glyphicon-remove' aria-hidden='true'></span></a>";
                    echo "<br />";
                    break;

                }

                $codeitems+=1;
                $lastcode=$value['content'];
            }
        }   
    }
    switch ($outputformat) {
        case 'list':
        echo "".$LANG["average2"]." ".strtolower($LANG["difference"]).": ". round(calculate_average($averageusage),2) . ".";
        echo "".$LANG["average"]." ".strtolower($LANG["price"]).": ". $currency . round(calculate_average($averageprice),2) . ".";
        break;
        
        default:
        echo "<tr>";
        echo "<td colspan=\"3\">";
        echo "".$LANG["average2"]." ".strtolower($LANG["difference"]).": ". round(calculate_average($averageusage),2);
        echo "</td>";
        echo "<td colspan=\"3\">";
        echo "".$LANG["average"]." ".strtolower($LANG["price"]).": " . $currency . " " . round(calculate_average($averageprice),2);
        echo "</td>";
        echo "</tr>";

        echo "</table>";
        break;
    }
    

}
#end showitems function

#via http://www.mdj.us/web-development/php-programming/calculating-the-median-average-values-of-an-array-with-php/
# start median calculate function
function calculate_median($arr) {
    global $LANG;
    sort($arr);
    $count = count($arr); //total numbers in array
    $middleval = floor(($count-1)/2); // find the middle value, or the lowest middle value
    if($count % 2) { // odd number, middle is the median
        $median = $arr[$middleval];
    } else { // even number, calculate avg of 2 medians
        $low = $arr[$middleval];
        $high = $arr[$middleval+1];
        $median = (($low+$high)/2);
    }
    return $median;
}
# end media calculate function

#via http://www.mdj.us/web-development/php-programming/calculating-the-median-average-values-of-an-array-with-php/
# start average calculate
function calculate_average($arr) {
    global $LANG;
    $count = count($arr); //total numbers in array
    $total=NULL;
    foreach ($arr as $value) {
        $total = $total + $value; // total value of array numbers
    }
    $average = ($total/$count); // get average value
    return $average;
}
#end average calculate

# start date function
function createdatearray($json_a,$itemtype,$maand) {
    global $LANG;
    global $currency;
    ${$itemtype . "month"} = array();
    ${$itemtype . "items"} = array();
    
    uasort($json_a, function ($i, $j) {
      $a = $i['date'];
      $b = $j['date'];
      if ($a == $b) return 0;
      elseif ($a > $b) return 1;
      else return -1;
    });
    foreach ($json_a as $item => $itemarray) {
        if ($itemarray["type"] == strtoupper($itemtype)) {
            ${$itemtype . "items"}[] = array("content" => $itemarray["content"], "date" => $itemarray["date"], "ID" => $item);
        } 
    }

    for ($i=0; $i < count(${$itemtype . "items"}); $i++) { 
        $date = ${$itemtype . "items"}[$i]["date"];
        $dt = new DateTime("@$date");
        $idate = $dt->format('n');
        ${$itemtype . "month"}[$idate][${$itemtype . "items"}["$i"]["ID"]] = array("date" => ${$itemtype ."items"}[$i]["date"], "content" => ${$itemtype ."items"}[$i]["content"]);
    }

    $maandnaam=date( 'F', mktime(0, 0, 0, $maand) );
    global ${strtoupper($itemtype) . "price"};
    $price = ${strtoupper($itemtype) . "price"};
    ksort(${$itemtype."month"});

    if (count(${$itemtype."month"}[$maand]) != 0) {
        echo "<h3>" . $maandnaam . "</h3>\n";
        echo "<table class=\"table table-striped\">\n";
        echo "<tr>\n";
        echo "<th>".$LANG["day"]."</th>\n";
        echo "<th>".$LANG["usage"]."</th>\n";
        echo "<th>".$LANG["difference"]."</th>\n";
        echo "<th>".$LANG["price"]."</th>\n";
        echo "<th>".$LANG["editdelete"]."</th>\n";
        echo "</tr>\n";
        $items=0;
        $lastcontent=0;
        $totaloftype=0;
        $totalprice=0;
        
        foreach (${$itemtype . "month"}[$maand] as $key => $value) {
            if (!empty($value["date"])) {
                if ($items >= 1) {
                    $diff = $value["content"] - $lastcontent;
                    $itemprice = floatval($diff) * floatval($price);
                } else {
                    $itemprice = 0;
                }
                
                echo "<tr>\n";
                echo "<td>\n";
                $date = $value['date'];
                $dt = new DateTime("@$date");
                echo $dt->format('D d M Y ');
                //echo date('l',strtotime(str_replace('-', '/',$value["date"]))) . " " . date('d',strtotime(str_replace('-', '/',$value["date"]))) . " " . date('M',strtotime(str_replace('-', '/',$value["date"])));
                echo "</td>\n";
                echo "<td>" . $value["content"] . "</td>\n";

                if ($items >= 1) {
                    #difference
                    echo "<td>" . round($diff,3) . "</td>\n";

                } else {
                    echo "<td> - </td>\n";
                }
                #price
                echo "<td>".$currency.": " . round($itemprice,2) . "</td>\n";
                echo "<td>";
                            #Edit
                echo "<a href=\"action.php?id=" .$key. "&action=edit\"><span class='glyphicon glyphicon-edit' aria-hidden='true'></span></a>";

                echo " - ";            
                #Delete
                echo "<a href=\"action.php?id=" .$key. "&action=delete\" onclick=\"if (!confirm('Are you sure you want to delete this item?')) return false;\"><span class='glyphicon glyphicon-remove' aria-hidden='true'></span></a>";


                $lastcontent=$value["content"];
                $totalprice += $itemprice;
                
                $items += 1;
                echo "</tr>\n";
            }
        }
        echo "<tr>\n";
        $firstelement=reset(${$itemtype . "month"}[$maand]);
        $firstelement=$firstelement["content"];        
        $lastelement=end(${$itemtype . "month"}[$maand]);
        $lastelement=$lastelement["content"];
        $totaloftype=floatval($lastelement)-floatval($firstelement);
        reset(${$itemtype . "month"}[$maand]);
        echo"<td colspan=\"2\">".$LANG["total"]." ".strtolower($LANG["usage"]).": " . round($totaloftype,3) . "</td>\n";
        echo "<td colspan=\"2\">".$LANG["total"]." ".strtolower($LANG["price"]).": " . $currency . ": ".round($totalprice,2) . "</td>\n";
        echo "<td></td>";
        echo "</tr>";
        echo "</table>";

    } else {
        echo " ";
    }
}
# end date function


# start showform function

function showinputform($actionpage) {
    global $LANG;
    $vandaag=date('m-d-Y');

    echo "<h3>".$LANG["addvalue"]."</h3>\n";
    echo "<form name=\"edit\" action=\"". $actionpage ."\" method=\"GET\">\n";
    echo "<select name=\"type\">\n";
    echo "<option value=\"NPP\">".$LANG["npp"]."</option>\n";
    echo "<option value=\"DPP\">".$LANG["dpp"]."</option>\n";
    echo "<option value=\"GAS\">".$LANG["gas"]."</option>\n";
    echo "<option value=\"H2O\">".$LANG["water"]."</option>\n";
    echo "</select>\n";
    echo "  <input name=\"content\" type=\"text\" placeholder=\"".$LANG["value"]."\" ></input>\n";
    echo "<input name=\"date\" type=\"text\" value=\"${vandaag}\"></input>\n";
    echo "<input type=\"hidden\" name=\"action\" value=\"add\"></input>\n";
    echo "&nbsp; &nbsp; ";
    echo "<input type=\"submit\" name=\"submit\" value=\"".$LANG["additem"]."\"></input>\n";
    echo "</form>\n";
}


function gen_uuid() {
  return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
    // 32 bits for "time_low"
    mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

    // 16 bits for "time_mid"
    mt_rand( 0, 0xffff ),

    // 16 bits for "time_hi_and_version",
    // four most significant bits holds version number 4
    mt_rand( 0, 0x0fff ) | 0x4000,

    // 16 bits, 8 bits for "clk_seq_hi_res",
    // 8 bits for "clk_seq_low",
    // two most significant bits holds zero and one for variant DCE1.1
    mt_rand( 0, 0x3fff ) | 0x8000,

    // 48 bits for "node"
    mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
  );
}

function download_send_headers($filename) {
    // disable caching
    $now = gmdate("D, d M Y H:i:s");
    header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
    header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
    header("Last-Modified: {$now} GMT");

    // force download  
    header("Content-Type: application/force-download");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/download");

    // disposition / encoding on response body
    header("Content-Disposition: attachment;filename={$filename}");
    header("Content-Transfer-Encoding: binary");
}

function downloadcsv($array) {
    $shortcodes = ["NPP", "DPP", "GAS", "H2O"];
    $csv_a = array();
    foreach ($shortcodes as $key => $value) {
        $arrFilter = array("type" => $value);
        $json_a = arrayFilter($array, $arrFilter, false);
        uasort($json_a, function ($i, $j) {
          $a = $i['date'];
          $b = $j['date'];
          if ($a == $b) return 0;
          elseif ($a > $b) return 1;
          else return -1;
        });
        foreach ($json_a as $key => $value) {
            $date = $value['date'];
            $dt = new DateTime("@$date");
            $line =  $value['type'] . "," . $value['content']. "," . $dt->format('d-m-Y '); 
            array_push($csv_a, $line);
        }
    }

    download_send_headers("csv_export_" . date("Y-m-d") . ".csv");
    echo "Type,Value,Date\n";
    foreach ($csv_a as $key => $value) {
        echo htmlspecialchars($value);
        echo "\n";
    }
    
    //echo array2csv($csv_a);
    die();
}

function pre_dump($var) {
  echo "<pre>";
  var_dump($var);
  echo "</pre>";
}

?>
