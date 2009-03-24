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
include("inc_sem_courant.php");
$reg=0;
echo $_POST['action'];
if($_POST['action']=="valider les modifications"){
	//vérifier si toutes les données sont bien rentrées (cohérence des mots de passe et unicité du log);
	if(strlen($_POST['new_pass1'])>1){
		if($_POST['new_pass1']==$_POST['new_pass2']){
			$passw=$_POST['new_pass1'];
		}
		
	}else{
		$passw=$_POST['passw'];
	}
	if($_POST['id']<0){
		$req = "SELECT * FROM etudiants WHERE log='".$_POST['log']."';";
		$res = mysql_query($req);
		if(mysql_num_rows($res)>0){
			header("Location: edition_etudiant.php?id=".$_POST["id"]."&erreur=logdeja");
		}else{
			$req= "INSERT INTO etudiants (id, nom, prenom, log, passw, mail)";
			$req .= "VALUES ('',";
			$req .= "'".$_POST['nom']."'";
			$req .= ",'".$_POST['prenom']."'";
			$req .=",'".$_POST['log']."'";
			$req .=",'".$passw."'";
			$req .= ",'".$_POST['mail']."'";
			$req .= ");";
			
		}
	}else {
		$req= "UPDATE etudiants SET ";
		$req .= "prenom='".$_POST['prenom']."', ";
		$req .= "nom='".$_POST['nom']."', ";
		
		$req .= "log='".$_POST['log']."', ";
		$req .="passw='".$passw."', ";
		$req .="mail='".$_POST['mail']."'";
		$req .= " WHERE id='".$_POST['id']."';";
	}

	echo $req;
	$res = mysql_query($req);
	$iid  = mysql_insert_id();
	echo mysql_error($res);
	if ($_POST['id']<0){
	$reqsup = "INSERT INTO niveaux (id, niveau, etudiant, periode, cycle) VALUES ";
			$reqsup .="('',";
			$reqsup .= "'".$_POST['niveau']."'";
			$reqsup .= ",'".$iid."'";
			$reqsup .=",'".$semestre_courant."'";
			$reqsup .=",'".$_POST['cycle']."');";
			$res = mysql_query($reqsup);}
			
	header("Location: edition_etudiant.php?id=".$iid);
}
?>
