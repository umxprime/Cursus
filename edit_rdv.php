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

require "include/necessaire.php";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="cursusn.css" type="text/css"/>
<?php

$idRdv = ($_POST['rdv'])?$_POST['rdv']:$_GET['rdv'];
if(!$idRdv){
	$idRdv=$_SESSION['lerdv'];
}else{
	session_register('lerdv');
	$_SESSION['lerdv']=$idRdv;
}
$req = "SELECT * FROM rdv WHERE id = '".$idRdv."';";
$res = mysql_query($req);
$rdv = mysql_fetch_array($res);

$req = "select * from professeurs where id='".$rdv['id_prof']."';";
$res = mysql_query($req);
$prof = mysql_fetch_array($res);
$req = "select * from etudiants where id='".$rdv['id_etudiant']."';";
$res = mysql_query($req);
$etu = mysql_fetch_array($res);
$req = "select * from tutorats where id='".$rdv['tutorat']."';";
$res = mysql_query($req);
$tuto = mysql_fetch_array($res);
$req = "select * from periodes where id='".$tuto['semestre']."';";
$res = mysql_query($req);
$sem = mysql_fetch_array($res);
if(isset($_POST['date'])){
	$dateRdv =$_POST['date'];
	$heureRdv = $_POST['heure'];
}else{
	$expDate = explode(" ",$rdv['date']);
	$expJour = explode("-",$expDate[0]);
	$expHeure = explode(":",$expDate[1]);
	$dateRdv = array('annee'=>$expJour[0],'mois'=>$expJour[1],'jour'=>$expJour[2]);
	$heureRdv = array('heure'=>$expHeure[0],'minutes'=>$expHeure[1],'secondes'=>$expHeure[2]);
}
$semestre_courant = $sem["semestre"];
include("inc_sem_courant.php");
?>
<title>Edition d'un rendez-vous</title>

</head>
<body>
	<div id="global">
		<?php
		$outil="tutorat";
		include("barre_outils.php");
		$disableNavSemPrec=true;
		$disableNavSemSuiv=true;
		include("inc_nav_sem.php");
		?>
		<input type="hidden" id="semestre_courant" value="<?php echo $semestre_courant;?>"/>
		<table><tr><td>
			<h2>
				<?php echo "Tutorat ".$etu['prenom']." ".$etu['nom']."/ rdv ".$rdv['ordre']; ?>
			</h2>
			<form id="formulaire" action="reg_rdv.php"
				method="post">
				<?php
				echo "<div class=\"selecteur_dates\" >Date du rendez-vous : ";
				echo selecteurDate("edit_rdv.php?rdv=".$idRdv,"date", $dateRdv['mois'] , $dateRdv['annee'], $dateRdv['jour']);
				echo "\n</div>";
				echo "<div class=\"selecteur_heure\" >Heure du rendez-vous : ";
				echo selecteurHeure("edit_rdv.php?rdv=".$idRdv,"heure", $heureRdv['heure'] , $heureRdv['minutes'], $heureRdv['secondes']);
				echo "\n</div>";
				echo "<div class=\"compte_rendu\"><p>Compte-rendu :</p>";
				echo affiche_champs("cr",$rdv['cr'],80, 16);
				echo "\n</div>";
				?>
				<fieldset>
					<input type="submit" name="action" value="valider"/>
					<br/>
					<input type="hidden" name="id_rdv" value="<?php echo $rdv['id']; ?>"/>
				</fieldset>
			</form>
		</td></tr></table>
	</div>
</body>
</html>
