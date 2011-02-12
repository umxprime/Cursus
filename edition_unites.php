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
    require "include/necessaire.php";
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
			<?php
			$outil="unités d'évaluation";
			include("barre_outils.php");
			include("inc_nav_sem.php");
			?>
			<input type="hidden" id="semestre_courant" value="<?php echo $semestre_courant;?>"/>
			<table class="center"><tr><td>
			<h1>Unités</h1>
			<select id="unites">
				<?php
					$req = "SELECT unites.id, unites.nom FROM unites;";
					$res = mysql_query($req);
					while($unite=mysql_fetch_array($res))
					{
						echo "<option value=\"".$unite["id"]."\">";
						echo utf8_decode($unite["nom"]);
						echo "</option>";
					}
				?>
			</select>
			Nom :
			<input type="text" id="nomUnite"/>
			<a href="javascript:modifierUnite()">Modifier</a>
			<h1>Nouvelle Unité</h1>
			Nom :
			<input type="text" id="nomNouvelleUnite"/>
			<a href="javascript:creerUnite()">Créer</a>
			</td></tr></table>
		</div>
	</body>
</html>