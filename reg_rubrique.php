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
if(isset($_POST['id'])){ 
	if ( $_POST['id']>0){
	$id=$_POST['id'];
	$req= "UPDATE rubriques ";
	$req .= "SET titre='".$_POST['titre']."', ";
	$req .= "indication='".$_POST['indication']."', ";
	$req .= "parent='".$_POST['parent']."', ";
	$req .= "visible='".((isset($_POST['visible']))?1:0)."', ";
	$req .= "ordre='".$_POST['ordre']."'";
	$req .= " WHERE id='".$id."';";
	$res = mysql_query($req);
	}else{
		$req= "INSERT INTO rubriques ";
	$req .= "(id, titre, indication, parent, visible, ordre) ";
	$req .= "VALUES ('','".$_POST['titre']."','".$_POST['indication']."', ";
	$req .= "'".$_POST['parent']."', ";
	$req .= "'".((isset($_POST['visible']))?1:0)."', ";
	$req .= "'".$_POST['ordre']."');";
	//echo mysql_error();
	$res = mysql_query($req);
	$id = mysql_insert_id();
	}
	
	echo mysql_error();
	header("Location: edition_rubriques.php?id=".$id);
}
?>
