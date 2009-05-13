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
include("fonctions_eval.php");
//echo $_GET["eval"];
if($_GET["eval"]>0){
	$id_eval=$_GET["eval"];
	$req = "SELECT * FROM evaluations WHERE id='".$id_eval."';";
	$res = mysql_query($req);
	
	$eval = mysql_fetch_array($res);
	$req = "select module, periode from session where id = '".$eval['session']."';";
	$res = mysql_query($req);
	$session = mysql_fetch_array($res);
	$req2 = "SELECT * FROM periodes where id = '".$session['periode']."';";
	$res2 = mysql_query($req2);
	$periode = mysql_fetch_array($res2);
	if($_SESSION['auto']=='a'){
		$req = "select intitule,enseignants from modules where id = '".$session['module']."';";
	}else if($_SESSION['auto']=='p'){
		$req = "select intitule,enseignants from modules where id = '".$session['module']."' AND enseignants LIKE '%".$_SESSION['username']."%';";
	}else{
		$req="select id from etudiants where id <0;";
	}
	//echo $req;
	$res = mysql_query($req);
	$module = mysql_fetch_array($res);
	$ava=mysql_num_rows($res);
	if($ava>0){
		$req = "select id,nom, prenom from etudiants where id = '".$eval['etudiant']."';";
		$res = mysql_query($req);
		$etudiant = mysql_fetch_array($res);
		?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<head>
<link rel="stylesheet" href="cursus.css" type="text/css">
<title>Evaluation de <?php echo utf8_encode($etudiant['prenom'])." ".utf8_encode($etudiant['nom']);?> pour le module : <?php echo utf8_encode($module['intitule']); ?></title>
</head>
<body>
<p>
<h2><?php echo $periode['nom']; ?></h2>
</p>
<p>
<h2>Evaluation de <?php echo utf8_encode($etudiant['prenom'])." ".utf8_encode($etudiant['nom']);?> pour le module : <?php echo utf8_encode($module['intitule']);?></h2>
</p>
<p>
<h2>Premi&egrave;re session</h2>
</p>
<p>
<form name="evaluation" id="evaluation" method="post"
	action="reg_eval.php">

	<?php

	echo affiche_champs("appreciation_1",$eval['appreciation_1'],80,8);
	echo "<br />NOTE : <select name=\"note_1\" width=\"40\" STYLE=\"width: 40px\">";
	echo "<option value='-' ";
	echo (!strpos("__abcdefABCDEF",verif($eval['note_1'])))?"selected ":"";
	echo ">-</option>\n";
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
			echo affiche_champs("appreciation_2",$eval['appreciation_2'],80,false);
			echo "<select name=\"note_2\">";
			echo "<option value='-' ";
			echo (!strpos("__abcdefABCDEF",$eval['note_2']))?"selected ":"";
			echo ">-</option>\n";
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
	echo "<input type=\"hidden\" name=\"session\" value=\"".$eval['session']."\" >";
	echo "<input type=\"submit\" value=\"enregistrer l'&eacute;valuation\" >";
	echo "</form></body></html>";
	}

}?>