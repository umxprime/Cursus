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
include("regles_utilisateurs.php");

if(isset($_GET["id"])){
	$session = $_GET["id"];
} else {
	die();
}

$req = "SELECT modules.obligatoire, modules.ecole FROM session,modules WHERE session.id='$session' AND session.module=modules.id;";
//echo $req;
$res = mysql_query($req);
$niveau = intval(mysql_result($res,0,"obligatoire"));
$ecole = mysql_result($res,0,"ecole");

$req = "SELECT etudiants.id, etudiants.nom FROM niveaux,etudiants,cycles WHERE niveaux.niveau='$niveau' AND etudiants.id=niveaux.etudiant AND periode='$semestre_courant' AND niveaux.cycle=cycles.id AND cycles.ecole='$ecole';";
$etudiants = mysql_query($req);
while ($etudiant = mysql_fetch_array($etudiants))
{
	$etudiant_id = $etudiant["id"];
	//echo $etudiant["nom"];
	$req = "SELECT id FROM evaluations WHERE evaluations.etudiant='$etudiant_id' AND evaluations.session='$session'";
	$res = mysql_query($req);
	if(mysql_num_rows($res)>0)
	{
		//echo "<b>inscrit</b><br/>";
	}
	else
	{
		$req = "INSERT INTO evaluations (session,etudiant) VALUES ('$session','$etudiant_id');";
		$res = mysql_query($req);
		//echo "<b>non inscrit</b><br/>";
	}
	header("location:gestion_modules.php?session=$session&nPeriode=$semestre_courant");
}
?>