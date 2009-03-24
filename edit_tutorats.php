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
include("fonctions.php");
//echo $_GET["eval"];
if($_GET["eval"]>0){
	$id_eval=$_GET["eval"];
	$req = "SELECT evaluations.*, etudiants.nom, etudiants.prenom,";
	$req .= " professeurs.nom_complet as prof, professeurs.id as prof_id, periodes.nom as semestre ";
	$req .="FROM evaluations, etudiants, professeurs, tutorats, periodes ";
	$req .= "WHERE evaluations.id='".$id_eval."' and tutorats.id=evaluations.tutorat ";
	$req .= "and etudiants.id = tutorats.etudiant and periodes.id=tutorats.semestre";
	$req .= " and professeurs.id=tutorats.professeur;";
	$res = mysql_query($req);
	//echo $req;
	$eval = mysql_fetch_array($res);
}
if($_SESSION['auto']=='a'or $_SESSION['userid']==$eval['prof_id']){
			?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<head>
<link rel="stylesheet" href="cursus.css" type="text/css">
<title>Tutorat de <?php echo $eval['prenom']." ".$eval['nom'];?></title>
</head>
<body>
<p>
<h2><?php echo $eval['semestre']; ?></h2>
</p>
<p>
<h2><a href="tutorats.php">Tutorat de <?php echo $eval['prof'];?></a> pour <?php echo $eval['prenom']." ".$eval['nom'];?></h2>
</p>
<p>
<h2>Premi&egrave;re session</h2>
</p>
<p>
<form name="evaluation" id="evaluation" method="post"
	action="reg_eval.php">

	<?php

	echo affiche_champs("appreciation_1",$eval['appreciation_1'],80);
	echo "<br />NOTE : <select name=\"note_1\" width=\"40\" STYLE=\"width: 40px\">";
	for($l=1; $l<7; $l++){
		$carac=chr(64+$l);
		echo "<option value='".$carac."' ";
		echo ($eval['note_1']==$carac)?"selected ":"";
		echo ">".$carac."</option>\n";
	}
	echo "</select></p>\n<p>";
	if(!empty($eval['note_1'])){
		if(strpos("__efEF",$eval['note_1'])){
			echo "<h2>Deuxi&egrave;me session</h2></p><p>";
			echo affiche_champs("appreciation_2",$eval['appreciation_2'],80);
			echo "<select name=\"note_2\">";
			for($l=1; $l<7; $l++){
				$carac=chr(64+$l);
				echo "<option value='".$carac."' ";
				echo ($eval['note_2']==$carac)?"selected ":"";
				echo ">".$carac."</option>\n";
			}
			echo "</select></p>\n";
		}
	}
	echo "<input type=\"hidden\" name=\"eval\" value=\"".$eval['id']."\" >";
	//echo "<input type=\"hidden\" name=\"session\" value=\"".$eval['session']."\" >";
	echo "<input type=\"submit\" value=\"enregistrer l'&eacute;valuation\" >";
	echo "</form></body></html>";
}
?>