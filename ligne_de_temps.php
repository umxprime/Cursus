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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<?php
require("connect_info.php");
require("connexion.php");
include("fonctions.php");
$dateCourante = date("d:m:Y:H:i");
//echo $dateCourante;
$expDate = explode(":",$dateCourante);
$arrDateCourante= array('jour'=>$expDate[0],'mois'=>$expDate[1],'annee'=>$expDate[2]);
?>
<title>Vision lin&eacute;aire</title>
</head>
<?php
$requete = "SELECT * FROM activites WHERE nom = 'semestre';";
$resultat = mysql_query($requete, $connexion);
$acti = mysql_fetch_array($resultat);
//echo implode(":",$acti);
$dateCourante = date("Y-m-d");
$requete = "SELECT * FROM periodes WHERE activite = '".$acti['id']."' AND debut <='".$dateCourante."' AND fin >='".$dateCourante."';";
$resultat = mysql_query($requete, $connexion);
$semestre = mysql_fetch_array($resultat);
//echo implode(":",$semestre);
$debut = dateBaseVersTime($semestre["debut"]);
$fin = dateBaseVersTime($semestre["fin"]);
$duree = $fin-$debut;
$nJours = $duree/60/60/24;
$nSemaines = $nJours/7;
//echo "debut : ".$debut."--fin : ".$fin."--duree : ".$duree."--nJours : ".$nJours;
$req = "SELECT * FROM activites;";
$res = mysql_query($req);
while($acti = mysql_fetch_array($res)){
	$couleurs[$acti["id"]]=$acti["couleur"];
}
?>
<body>
<table width="100%">
<tr>
<?php
$wdth = (int)100/($nSemaines+1);
for($nSemaine=0; $nSemaine<=(int)$nSemaines; $nSemaine++){
	$debutSem = $debut + $nSemaine*7*24*60*60;
	$finSem = $debutSem + 7*24*60*60;
	$req="SELECT * FROM periodes WHERE debut>='".date("Y-m-d",$debutSem)."'";
	$req .= " AND fin<='".date("Y-m-d", $finSem)."';";
	$res = mysql_query($req);
	$nCouche=0;
	while ($couches[$nCouche][$nSemaine]=mysql_fetch_array($res)){
		$couches[$nCouche][$nSemaine]["couleur"]=$couleurs[$couches[$nCouche][$nSemaine]["activite"]];
		//echo "coucheSup : ".$couches[$nCouche]."/".$nSemaine;
		$nCouche++;
	}
	echo "\n\t<th";
	echo " width=\"".(int)$wdth."%\"";
	echo " style=\"background-color: #CCCCCC;";
	echo " text-align: center;";
	echo " font-family: Helvetica,Arial,sans-serif;";
	echo "font-size : 9px;";
	echo "\">";
	echo "\n\t\t<a name=\"#\" title=\"du ".date("d/m",$debutSem)." au ".date("d/m",$finSem)."\">";
	echo "sem. ".($nSemaine+1)."</a>\n\t</th>";
}
echo "\n</tr>";
for($nc=0; $nc<count($couches)-1; $nc++){
	echo "\n<tr>";
	for($nSemaine=0; $nSemaine<=(int)$nSemaines; $nSemaine++){
		echo "\n\t<td";
		if(is_array($couches[$nc][$nSemaine])){
			echo " width=\"".(int)$wdth."%\"";
			echo " style=\"background-color: ".$couches[$nc][$nSemaine]["couleur"].";";
			echo " text-align: center;";
			echo " font-family: Helvetica,Arial,sans-serif;";
			echo "font-size : 9px;";
			echo "\">";
			echo "\n\t\t<a name=\"#\" title=\"".$couches[$nc][$nSemaine]["nom"]."\">";
			echo ($nSemaine+1)."</a>\n\t</td>";
		}else{
			echo "></td>";
		}
	}
	echo "\n<tr>";
}
?>
</table>
</body>
</html>
