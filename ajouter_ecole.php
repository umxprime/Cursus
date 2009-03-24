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

	if ($_POST['action']=="ajouter"){
	$req= "INSERT INTO ecoles (id,nom,rue, cp, ville, tel) VALUES ('',";
	$req .="'".$_POST['nom']."'";
	$req .=",'".$_POST['rue']."'";
	$req .=",'".$_POST['cp']."'";
	$req .=",'".$_POST['ville']."'";
	$req .=",'".$_POST['tel']."'";
	$req .= ");";
	}else if ($_POST['action']=="supprimer"){
		$req = "DELETE FROM ecoles WHERE id='".$_POST['id']."';";
	}else if ($_POST['action']=='modifier'){
		$req= "UPDATE ecoles SET nom='";
	
	$req .=$_POST['nom']."'";
	$req .=",rue='".$_POST['rue']."'";
	$req .=",cp='".$_POST['cp']."'";
	$req .=",ville='".$_POST['ville']."'";
	$req .=",tel='".$_POST['tel']."'";
	$req .= " WHERE id='".$_POST['id']."';";
	
	}
	//echo $req;
	$res = mysql_query($req);
	//echo mysql_error($res);
	header("Location: edition_ecoles.php?session=".$_POST["session"]);

?>
