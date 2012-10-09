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

# per kWh
$NPPprice=0.22716;
# per kWh
$DPPprice=0.20556;
# per m3
$GASprice=0.64354;
# per m3
$H2Oprice=0.914;
# price symbol/name
$currency="EUR";


//http://www.tek-tips.com/viewthread.cfm?qid=1568788
function arrayFilter($arrHaystack, $arrFilter, $boolStrict = false)
{
    if (!is_array($arrFilter)) $arrFilter = array($arrFilter);
    if (!is_array($arrHaystack)) $arrHaystack = array($arrHaystack);

    foreach ($arrHaystack as $strHKey => $objHValue)
    {
        if (is_array($objHValue))
            $boolFound = arrayFilter($objHValue, $arrFilter, $boolStrict);
        else
        {
            $strHKey = strtolower($strHKey);
            $objHValue = strtolower($objHValue);
            foreach ($arrFilter as $strFKey => $objFValue)
            {
                $strFKey = strtolower($strFKey);
                $objFValue = strtolower($objFValue);

                $boolMatch = (($strFKey == $strHKey) AND ($objFValue == $objHValue));

                if ($boolMatch == 1)
                    if ($boolStrict)
                        unset($arrFilter[$strFKey]);
                    else
                        $arrFilter = array();

                    if (count($arrFilter) == 0)
                        return true;
                }
            }

            if ($boolFound)
                $arrResult[$strHKey] = $objHValue;
        }

        return $arrResult;    
    }


    function makegraph($array,$shortcode,$color,$maxitems) {


        $arrFilter = array("type" => $shortcode);
        $json_a=arrayFilter($array, $arrFilter, true);

        ?>
        <div id="<?php echo $shortcode; ?>-chart">
            <div id="<?php echo $shortcode; ?>graph" style="width:500px;height:150px;"></div>
            <script type="text/javascript">
            $(function () {

                <?php
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
                        $day=date("D",strtotime($value['date'])); 
                        if($codeitems > 1) {
                            $codemin =  floatval($value['content']) - floatval($lastcode);
                        } else {
                            $codemin = 0;
                        }
                        if ($codeitems >= 2) {
                            echo "[" . $codeitems. ", " . $codemin . "], ";
                        }

                        $codeitems+=1;
                        $lastcode=$value['content'];

                    }
                }
                echo "];";
                ?>  
                $.plot($("#<?php echo $shortcode; ?>graph"), [ d2 ], {colors: ['<?php echo $color; ?>']});
            });
</script>
<?php

echo "</div>";
unset($json_a);
unset($totalitems);
}
# end makegraph function

function showitems($array,$name,$shortcode,$maxitems,$price,$outputformat) {

    #Get the currency value from outside the function
    global $currency;
    #Define the filter for the array filter
    $arrFilter = array("type" => $shortcode);
    #Filter the json file array with the above filter (filter on type $shortcode)
    $json_a=arrayFilter($array, $arrFilter, true);

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

    # first do some formatting if needed
    switch ($outputformat) {
        case 'list':
            # do nothing
        echo "";
        break;
        
        default:
            # Tables, yay!
        echo "<table class=\"striped\">";
        echo "<tr>";
        echo "<th>#</th>";
        echo "<th>Date</th>";
        echo "<th>Usage</th>";
        echo "<th>Difference</th>";
        echo "<th>Price</th>";
        echo "<th>Edit</th>";
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
                    echo "<td>".$codeitems."</td>";
                    echo "<td>".date('D d M Y',strtotime(str_replace('-', '/',$value["date"])))."</td>";
                    echo "<td>".$value['content']."</td>";
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
                    echo "<a href=\"action.php?id=" .$item. "&action=edit\"><span class=\"icon small darkgray\" data-icon=\"7\"></span></a>";
                    echo " - ";            
                        #Delete
                    echo "<a href=\"action.php?id=" .$item. "&action=delete\"><span class=\"icon small darkgray\" data-icon=\"T\"></span></a>";
                    echo "</tr>";
                    break;

                    case 'list':
                    echo "<b>" . $codeitems . ":</b>"  . $value['date'] . ' - ' . $name . ' usage: ' . $value['content'];              
                    if ($itemprice != 0) {
                        echo ' - Difference with day before: ' . round($codemin,3) . '.';
                        echo " [". $currency.": ".round($itemprice,2)."]";
                    }
                    echo " [ <a href=\"action.php?id=" .$item. "&action=edit\"><span class=\"icon small darkgray\" data-icon=\"7\"></span></a></td>";
                    echo " <a href=\"action.php?id=" .$item. "&action=delete\"><span class=\"icon small darkgray\" data-icon=\"T\"></span></a> ] \n\r";
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
        echo "Average difference: ". round(calculate_average($averageusage),2) . ".";
        echo "Average price: ". $currency . round(calculate_average($averageprice),2) . ".";
        break;
        
        default:
        echo "<tr>";
        echo "<td colspan=\"3\">";
        echo "Average difference: ". round(calculate_average($averageusage),2);
        echo "</td>";
        echo "<td colspan=\"3\">";
        echo "Average price: " . $currency . " " . round(calculate_average($averageprice),2);
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
    $count = count($arr); //total numbers in array
    foreach ($arr as $value) {
        $total = $total + $value; // total value of array numbers
    }
    $average = ($total/$count); // get average value
    return $average;
}
#end average calculate

# start date function
function createdatearray($json_a,$itemtype,$maand) {
    global $currency;
    ${$itemtype . "month"} = array();
    ${$itemtype . "items"} = array();
    foreach ($json_a as $item => $itemarray) {
        if ($itemarray["type"] == strtoupper($itemtype)) {
            ${$itemtype . "items"}[] = array("content" => $itemarray["content"], "date" => $itemarray["date"], "ID" => $item);
        } 
    }

    for ($i=0; $i < count(${$itemtype . "items"}); $i++) { 
        $idate=date('n',strtotime(str_replace('-', '/',${$itemtype . "items"}[$i]["date"])));
        ${$itemtype . "month"}[$idate][${$itemtype . "items"}["$i"]["ID"]] = array("date" => ${$itemtype ."items"}[$i]["date"], "content" => ${$itemtype ."items"}[$i]["content"]);
    }

    $maandnaam=date( 'F', mktime(0, 0, 0, $maand) );
    global ${strtoupper($itemtype) . "price"};
    $price = ${strtoupper($itemtype) . "price"};
    ksort(${$itemtype."month"});

    if (count(${$itemtype."month"}[$maand]) != 0) {

        echo "<h3>" . $maandnaam . "</h3>\n";
        echo "<table class=\"striped\">\n";
        echo "<tr>\n";
        echo "<th>Day</th>\n";
        echo "<th>Usage</th>\n";
        echo "<th>Difference</th>\n";
        echo "<th>Price</th>\n";
        echo "<th>Edit/Delete</th>\n";
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
                echo date('l',strtotime(str_replace('-', '/',$value["date"]))) . " " . date('d',strtotime(str_replace('-', '/',$value["date"]))) . " " . date('M',strtotime(str_replace('-', '/',$value["date"])));
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
                echo "<a href=\"action.php?id=" .$key. "&action=edit\"><span class=\"icon small darkgray\" data-icon=\"7\"></span></a>";
                echo " - ";            
                #Delete
                echo "<a href=\"action.php?id=" .$key. "&action=delete\"><span class=\"icon small darkgray\" data-icon=\"T\"></span></a>";

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
        echo"<td colspan=\"2\">Total usage: " . round($totaloftype,3) . "</td>\n";
        echo "<td colspan=\"2\">Total price: " . $currency . ": ".round($totalprice,2) . "</td>\n";
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

    $vandaag=date('m-d-Y');

    echo "<h3>Add Value</h3>\n";
    echo "<form name=\"edit\" action=\"". $actionpage ."\" method=\"GET\">\n";
    echo "<select name=\"type\">\n";
    echo "<option value=\"NPP\">Normal Price power</option>\n";
    echo "<option value=\"DPP\">Discount Price power</option>\n";
    echo "<option value=\"GAS\">Gas</option>\n";
    echo "<option value=\"H2O\">Water</option>\n";
    echo "</select>\n";
    echo "  <input name=\"content\" type=\"text\" placeholder=\"Value\" ></input>\n";
    echo "<input name=\"date\" type=\"text\" value=\"${vandaag}\"></input>\n";
    echo "<input type=\"hidden\" name=\"action\" value=\"add\"></input>\n";
    echo "&nbsp; &nbsp; ";
    echo "<input type=\"submit\" name=\"submit\" value=\"Add Item\"></input>\n";
    echo "</form>\n";
}



?>