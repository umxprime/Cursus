<?php
	/**
	 * Copyright © 2009 Maxime CHAPELET (umxprime@umxprime.com)
	 * 
	 * This file is a part of Potajx
	 * 
	 * Potajx is free software: you can redistribute it and/or modify
	 * it under the terms of the GNU General Public License as published by
	 * the Free Software Foundation, either version 3 of the License, or
	 * (at your option) any later version.
	 *
	 * Potajx is distributed in the hope that it will be useful,
	 * but WITHOUT ANY WARRANTY; without even the implied warranty of
	 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	 * GNU General Public License for more details.
	 *
	 * You should have received a copy of the GNU General Public License
	 * along with Potajx.  If not, see <http://www.gnu.org/licenses/>.
	 * 
	 */
?>
	<script type="text/javascript" src="potajx/potajx.js"></script>
<?php
	$file = $_SERVER['PHP_SELF'];
	$path = explode("/",$file);
	$page = $path[count($path)-1];
	$page = explode(".php",$page);
	$page = $page[0];
	echo "<script type=\"text/javascript\" src=\"potajx/modules/ajx_".$page.".js\"></script>";
	echo "<script type=\"text/javascript\" src=\"potajx/potajuste.js\"></script>";
?>