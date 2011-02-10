<?php
	/**
	 * 
	 * Copyright © 2010,2011 Maxime CHAPELET (umxprime@umxprime.com)
	 *
	 * This file is a part of the Limelight Framework
	 *
	 * The Limelight Framework is free software: you can redistribute it and/or modify
	 * it under the terms of the GNU General Public License as published by
	 * the Free Software Foundation, either version 3 of the License, or
	 * (at your option) any later version.
	 *
	 * The Limelight Framework is distributed in the hope that it will be useful,
	 * but WITHOUT ANY WARRANTY; without even the implied warranty of
	 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	 * GNU General Public License for more details.
	 *
	 * You should have received a copy of the GNU General Public License
	 * along with the Limelight Framework.  If not, see <http://www.gnu.org/licenses/>.
	 * 
	 **/
	session_start();
	$_ROOT_PATH="../../../../";
	$_LIMELIGHT_PATH = "com/umxprime/limelight/";
	include($_ROOT_PATH."connect_info.php");
	include($_ROOT_PATH."connexion.php");
	$action = $_GET["action"];
	$getparams = explode(",",$_GET["params"]);
	foreach($getparams as $value){
		$param = explode(":",$value);
		try {
			${$param[0]} = $param[1];
		}catch(Exception $e) {
			
		}
	}
	function ajx_restore($str)
	{
		$str = str_replace("%#%",",",$str);
		$str = str_replace("%##%",":",$str);
		return utf8_decode($str);
	}
	include($_ROOT_PATH.$_LIMELIGHT_PATH."modules/ajx_".$_GET["module"].".php");
?>