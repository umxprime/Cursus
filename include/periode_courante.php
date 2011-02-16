<?php
/**
 * 
 * Copyright © 2007,2008,2009 Roland DECAUDIN (roland@xcvbn.net)
 * Copyright © 2008,2009,2010,2011 Maxime CHAPELET (umxprime@umxprime.com)
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
 * Cursus uses FPDF released by Olivier PLATHEY
 *
 * Cursus uses the Limelight Framework
 * released under the GPL <http://www.gnu.org/licenses/>
 * by Maxime CHAPELET (umxprime@umxprime.com)
 * 
 **/

//ce morceau de code regarde si une periode est envoy�e (post ou get)
//si c'est le cas, il ajuste la variable semestre_courant avec l'identifiant du semestre dans la table periodes
//et l'array periode avec les donn�es compl�tes de la p�riode dans la table �ponyme
//si aucune periode n'est transf�r�e, il prend, par d�faut le semestre dans lequel la date actuelle est incluse
$dateCourante = date("Y-m-d");
if(isset($_GET['nPeriode'])){//numero de periode envoy� par get
	$periode_courante = $_GET['nPeriode'];
	$requete = "select * from periodes where id = ".$periode_courante.";";
	$resreq = mysql_query($requete);
	$periode = mysql_fetch_array($resreq);
} else if(isset($_POST['nPeriode']))
{//num de periode envoy� par post
	$periode_courante=$_POST['nPeriode'];
	$requete = "select * from periodes where id = ".$periode_courante.";";
	$resreq = mysql_query($requete);
	$periode = mysql_fetch_array($resreq);
}
else{//periode incluant la date syst�me actuelle
	//$requete = "select * from periodes where activite='14' and debut <='".$dateCourante."' and fin >='".$dateCourante."';";
	$req = "SELECT valeur FROM reglages WHERE reglages.option='semestre_courant';";
	//echo $requete;
	$res = mysql_query($req);
	$reglage = mysql_fetch_array($res);
	$periode_courante = $reglage['valeur'];
	if($_SESSION["auto"]=="e")
	{
		$id = $_SESSION['userid'];
		$req = "SELECT niveaux.periode FROM niveaux WHERE niveaux.etudiant='$id' ORDER BY periode DESC;";
		$res = mysql_query($req);
		$periode_courante = mysql_result($res,0,"periode");
		//echo $req;
	}
	$req = "SELECT * FROM periodes WHERE id=".$periode_courante.";";
	$res = mysql_query($req);
	$periode = mysql_fetch_array($res);
}
$semestre_courant = $periode_courante;
?>
