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
?>
<?php
include("lesotho.php");
require("connect_info.php");
require("connexion.php");
if($_POST["dateDebut"] && $_POST["dateFin"]){
	$debut=$_POST["dateDebut"];
	$fin=$_POST["dateFin"];
	if ($_POST['action']=="ajouter"){
	$req= "INSERT INTO periodes (id,debut,fin, nom, activite) VALUES ('',";
	$req .= "'".$debut['annee']."-".$debut['mois']."-".$debut['jour']."'";
	$req .= ",'".$fin['annee']."-".$fin['mois']."-".$fin['jour']."'";
	$req .=",'".$_POST['nom']."'";
	$req .=",'".$_POST['activite']."'";
	$req .= ");";
	}else if ($_POST['action']=="supprimer"){
		$req = "DELETE FROM periodes WHERE id='".$_POST['id']."';";
	}else if ($_POST['action']=='modifier'){
		$req= "UPDATE periodes SET debut='";
	$req .= $debut['annee']."-".$debut['mois']."-".$debut['jour']."'";
	$req .= ",fin='";
	$req .= $fin['annee']."-".$fin['mois']."-".$fin['jour']."'";
	$req .=",nom='".$_POST['nom']."'";
	$req .=",activite='".$_POST['activite']."'";
	$req .= " WHERE id='".$_POST['id']."';";
	
	}
	//echo $req;
	$res = mysql_query($req);
	//echo mysql_error($res);
	header("Location: periodes.php?session=".$_POST["session"]);
}
?>
