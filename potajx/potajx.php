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
	$rootpath="../include/";
	include($rootpath."connexion.php");
	$action = $_GET["action"];
	$getparams = explode(",",$_GET["params"]);
	$params = array();
	foreach($getparams as $value){
		$param = explode(":",$value);
		$params[$param[0]] = $param[1];
	}
	function ajx_restore($str)
	{
		$str = str_replace("%#%",",",$str);
		$str = str_replace("%##%",":",$str);
		return utf8_decode($str);
	}
	include("modules/ajx_".$_GET["page"].".php");
?>