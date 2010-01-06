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
	$outil="utilisateurs";
	include("inc_sem_courant.php");
	include("regles_utilisateurs.php");
	//exit();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<?php 
			include("inc_css_thing.php");
		?>
		<title>Cursus <?php echo revision();?> / Édition utilisateurs</title>
		<?php
			include("potajx/incpotajx.php");
		?>
	</head>
	<body>
		<div id="global">
			<?php include("barre_outils.php"); ?>
			<?php include("inc_nav_sem.php"); ?>
			<input type="hidden" id="semestre_courant" value="<?php echo $semestre_courant;?>"/>
			
			<table>
				<tr><td>
					<?php ajx_span("liste_types"); ajx_span("liste_utilisateurs"); ?>
					<a href="javascript:chg_utilisateur()" onclick="this.blur()">Recharger</a>
					<a href="javascript:init()" onclick="this.blur()">Nouveau</a>
					<a href="javascript:del_utilisateur()" onclick="this.blur()">Supprimer</a>
				</td></tr>
				<tr><td>
					Nom <?php ajx_span("nom");?> Prénom <?php ajx_span("prenom"); ?>
				</td></tr>
				<tr><td>
					Log <?php ajx_span("log"); ?> Type <?php ajx_span("logtype"); ?>
				</td></tr>
				<tr><td>
					Mot de passe <?php ajx_span("passw");?>
					<a href="javascript:ajx_genMotDePasse('passw');" onclick="this.blur();" >Générer</a>
				</td></tr>
				<tr><td>
					<table id="champs_etus">
						<tr><td>
							Semestre <?php ajx_span("liste_semestres"); ?>
						</td></tr>
						<tr><td>
							Cycle <?php ajx_span("liste_cycles"); ?>
						</td></tr>
					</table>
					<table id="champs_profs">
						<tr><td>
							Auto <?php ajx_span("autos"); ?>
						</td></tr>
						<tr><td>
							École <?php ajx_span("liste_ecoles"); ?>
						</td></tr>
					</table>
				</td></tr>
				<tr><td>
					<!-- <a href="javascript:submit()" onclick="this.blur()" id="submit">Valider</a> -->
					<a id="valider" href="" name="Valider">Valider</a>
				</td></tr><tr><td>
					<?php ajx_span("alerts"); ?>
					<?php ajx_span("loader");?>
				</td></tr>
			</table>
		</div>
	</body>
</html>