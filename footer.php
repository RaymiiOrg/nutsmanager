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
?>
<div class="col-md-2">
	<h2>Menu</h2>
	<p>
		<a href="index.php"><?php echo $LANG["mainpage"]; ?></a><br />

		<a href="full.php"><?php echo $LANG["monthlyoverview"]; ?></a><br />

	</p>
	<br />
	<h2><?php echo $LANG["prices"]; ?></h2>
	<p>
		<?php echo $LANG["pricestext"]; ?>
	</p>
	<h2><?php echo $LANG["help"]; ?></h2>
	<p>
		<?php echo $LANG["helptext"]; ?>
	</p>
	<h2><?php echo $LANG["info"]; ?></h2>
	<p>
		<?php echo $LANG["infotext"]; ?>
	</p>
</div>

<div class="footer col-md-10 col-md-offset-1">
<hr />
<?php echo $productname . " " . $productversion; ?> by Remy van Elst - <a href="https://raymii.org">Raymii.org</a>.
</div>

</div>

</div>
</body></html>
