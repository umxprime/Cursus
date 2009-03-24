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
$reg=0;
//echo $_POST['action'];
if($_POST['action']=="valider les modifications"){
	//vérifier si toutes les données sont bien rentrées (cohérence des mots de passe et unicité du log);
	if(strlen($_POST['new_pass1'])>1){
		if($_POST['new_pass1']==$_POST['new_pass2']){
		}
		$passw=$_POST['new_pass1'];
	}else{
		$passw=$_POST['passw'];
	}
	if($_POST['id']<0){
		$req = "SELECT * FROM professeurs WHERE log='".$_POST['log']."';";
		$res = mysql_query($req);
		if(mysql_num_rows($res)>0){
			header("Location: edition_prof.php?id=".$_POST["id"]."&erreur=logdeja");
		}else{
			$req= "INSERT INTO professeurs (id, nom, prenom,ecole, log, passw, autos, nom_complet)";
			$req .= "VALUES ('',";
			$req .= "'".$_POST['nom']."'";
			
			$req .= ",'".$_POST['prenom']."'";
			$req .= "'".$_POST['ecole']."'";
			$req .=",'".$_POST['log']."'";
			$req .=",'".$passw."'";
			$req .= ",'p',";
			$req .= "'".$_POST['prenom']." ".$_POST['nom']."'";
			$req .= ");";
		}
	}else {
		$req= "UPDATE professeurs SET ";
		$req .= "prenom='".$_POST['prenom']."', ";
		$req .= "nom='".$_POST['nom']."', ";
		$req .= "ecole='".$_POST['ecole']."', ";
		$req .= "log='".$_POST['log']."', ";
		$req .="passw='".$passw."', ";
		$req .="autos='p', ";
		$req .= "nom_complet='".$_POST['prenom']." ".$_POST['nom']."'";
		$req .= " WHERE id='".$_POST['id']."';";
	}

	//echo $req;
	$res = mysql_query($req);
	echo mysql_error($res);
	header("Location: edition_prof.php?id=".$_POST["id"]);
}
?>
