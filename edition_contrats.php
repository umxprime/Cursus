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
	$outil="coordination";
	include("inc_sem_courant.php");
	include("regles_utilisateurs.php");
	if ($_SESSION['auto']=="e")	header("location:login.php");
	//if (!$droits[$_SESSION['auto']]['edit_contrats'])
	$id = $_GET["id"];
	$req = "SELECT "; 
	$req .= "modules.id,";
	$req .= "modules.code,";
	$req .= "modules.intitule";
	$req .= " FROM ";
	$req .= "`evaluations`,`session`,`modules`";
	$req .= " WHERE ";
	$req .= "evaluations.etudiant='$id' AND ";
	$req .= "evaluations.session=session.id AND ";
	$req .= "session.periode='$semestre_courant' AND ";
	$req .= "session.module=modules.id;";
	$res = mysql_query($req);
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<?php 
			include("inc_css_thing.php");
		?>
		<title><?php echo $periode['nom']?></title>
		<?php
			include("potajx/incpotajx.php");
		?>
	</head>
	<body>
		<div id="global">
			<?php include("barre_outils.php"); ?>
			<input type="hidden" id="semestre_courant" value="<?php echo $semestre_courant;?>"/>
			<table id="contrat">
			</table>
		</div>
	</body>
</html>