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
include("fonctions.php");
//on requiert les variables de connexion;
require("connect_info.php");
//puis la connexion standard;
require("connexion.php");
include("regles_utilisateurs.php");
if(!$droits[$_SESSION["auto"]]["edit_niveaux"]) re_root("login.php");
include("inc_sem_courant.php");
(isset($_POST['id_etudiant']))?($id_etudiant=$_POST['id_etudiant']):((isset($_GET['id_etudiant']))?($id_etudiant=$_GET['id_etudiant']):($id_etudiant=0));
(isset($_POST['cycle']))?($cycle=$_POST['cycle']):((isset($_GET['cycle']))?($cycle=$_GET['cycle']):($cycle=0));

//echo $id_etudiant;
// si on entre sur la page avec une valeur de periode_sortie en post on applique les modifications à la table;
if (isset($_POST['periode_sortie'])){
	$req = "UPDATE etudiants SET periode_sortie='".$_POST['periode_sortie']."' WHERE id='".$id_etudiant."';";
	mysql_query($req);
	re_root("passages_niveaux.php?nPeriode=".$semestre_courant);
}
if (isset($_POST['nouveau_cycle'])){
	$req = "UPDATE niveaux SET cycle='".$_POST['nouveau_cycle']."' WHERE etudiant='".$id_etudiant."' AND periode='".$semestre_courant."';";
	mysql_query($req);
	re_root("passages_niveaux.php?nPeriode=".$semestre_courant."&cycle=".$_POST['nouveau_cycle']);
}
//origine contient en post ou en get la page d'où l'on vient
(isset($_POST['origine']))?($origine=$_POST['origine']):((isset($_GET['origine']))?($origine=$_GET['origine']):($origine="passages_niveaux.php"));
$req = "SELECT id, nom, prenom, periode_sortie from etudiants where id='".$id_etudiant."' ";
$res = mysql_query($req);
$etudiant = mysql_fetch_array($res);

?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
	<?php include("inc_css_thing.php"); echo "\n"; ?>
	<title>Spécifier le moment du départ d'un étudiant</title>
	<style type="text/css">
		<?php include("cursus2.css"); ?>
	</style>
</head>
<body>
	<form method="post" action="exclure_etudiant.php" id="changer">
		<?php
		echo "<p>Notifier le changemet de cycle de : ".$etudiant['prenom']." ".$etudiant['nom']."<br />";
		echo "Indiquez le nouveau cycle";
		echo selecteur_cycle($conn, $cycle,"nouveau_cycle", "passages_niveaux.php",-1);
		echo "</p>";?>
		<fieldset>
			<input type=submit value="Valider le changement"/>
			<input type="hidden" name="id_etudiant" value="<?php echo $id_etudiant; ?>"/>
		</fieldset>
		</form>
		<form method="post" action="exclure_etudiant.php" id="depart">
		<?php
		echo "<p>Notifier la sortie de : ".$etudiant['prenom']." ".$etudiant['nom']."<br />";
		echo "Indiquez le semestre de départ de l'établissement (la date de fin de semestre est retenue)";
		echo selecteur_semestres($connexion,$semestre_courant,'periode_sortie','exclure_etudiant.php');
		echo "</p>";
		?>
		<fieldset>
		<input type=submit value="Valider le départ"/>
			<input type="hidden" name="id_etudiant" value="<?php echo $id_etudiant; ?>"/>
			<input type="hidden" name="origine" value="<?php echo $origine; ?>"/>
		</fieldset>
	</form>
</body>
</html>