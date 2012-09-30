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

$vandaag=date('m-d-Y');
	echo "<h3>Add Value</h3>\n";
	echo "<form name=\"edit\" action=\"action.php\" method=\"GET\">\n";
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
	?>