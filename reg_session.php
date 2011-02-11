<?php
	/**
	 * 
	 * Copyright © 2007,2008,2009 Roland DECAUDIN (roland@xcvbn.net)
	 * Copyright © 2008,2009 Maxime CHAPELET (umxprime@umxprime.com)
	 *
	 * This file is a part of Cursus
	 *
	 * Cursus is free software: you can redistribute it and/or modify
	 * it under the terms of the GNU General Public License as published by
	 * the Free Software Foundation, either version 3 of the License, or
	 * (at your option) any later version.
	 *
	 * Cursus is distributed in the hope that it will be useful,
	 * but WITHOUT ANY WARRANTY; without even the implied warranty of
	 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	 * GNU General Public License for more details.
	 *
	 * You should have received a copy of the GNU General Public License
	 * along with Cursus.  If not, see <http://www.gnu.org/licenses/>.
	 *
	 * Cursus uses a modified version of TinyButStrong and TinyButStrongOOo
	 * originally released under the LGPL <http://www.gnu.org/licenses/>
	 * by Olivier LOYNET (tbsooo@free.fr)
	 *
	 * Cursus uses Potajx
	 * released under the GPL <http://www.gnu.org/licenses/>
	 * by Maxime CHAPELET (umxprime@umxprime.com)
	 * 
	 **/

include("lesotho.php");
require("connect_info.php");
require("connexion.php");
//error_reporting(E_ALL);
//echo $_POST['session'];
if($_POST["session"]>0){
	$id_session=$_POST["session"];
	$req = "SELECT * FROM evaluations WHERE session='".$id_session."';";
	$resres = mysql_query($req);
	//echo mysql_error($resres);
	while($eval = mysql_fetch_array($resres)){
		$var = "presences".$eval['id'];
		
		//echo $var;
		$var = $_POST[$var];
		if (!is_array($var)) $var=array();
		$chPresences = "pr_".implode("",$var);
		$req= "UPDATE evaluations SET presences='".$chPresences."' WHERE id='".$eval['id']."';";
		//echo $req;
		$res = mysql_query($req);
	}
	//mysql_free_result($res);
	mysql_free_result($resres);
	//echo mysql_error($res);
	$chaine_header = "Location: edition_session.php?session=".$_POST['session'];
	//echo $chaine_header;
	//header($chaine_header);
	header($chaine_header);
}
?>
