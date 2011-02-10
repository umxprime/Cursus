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
		$id_clef = ($_POST['id'])?$_POST['id']:$_GET['id'];
		$nom=(empty($_POST['nom']))?"nom de l'activit&eacute;":$_POST['nom'];
		$couleur=(empty($_POST['couleur']))?"#000000":$_POST['couleur'];
		?>
		<title>Ajout d'une activit&eacute;</title>
	</head>
	<body>
	<?php
	if(!$id_clef){
		$id_clef = -1;
		$ligne = FALSE;
		$nom=(empty($_POST['nom']))?"nom de l'activit&eacute;":$_POST['nom'];
		$couleur=(empty($_POST['couleur']))?"#000000":$_POST['couleur'];
	}
	else
	{
		$requete = "SELECT * FROM periodes WHERE id = '".$id_clef."';";
		$resultat = mysql_query($requete, $connexion);
		$ligne = mysql_fetch_array($resultat);
		$nom=$ligne['nom'];
		$couleur=$ligne['couleur'];
	}
	
	?>
	
		<form id="formulaire" action="ajouter_activite.php" method="post">
		<fieldset>
			<?php
			echo "<div class=\"selecteur_periode\">P&eacute;riode : ";
			echo selecteur_objets("activites.php", "activites", "nom", "id", $connexion, $id_clef,0,1);
			echo "\n</div>";
			echo "<div class=\"nom_periode\">";
			echo affiche_ligne("nom",$nom);
			echo "\n</div>";
			echo "<div class=\"code_couleur\">";
			echo affiche_ligne("couleur",$couleur);
			echo "\n</div>";
		
			if($id_clef > 0){
			?>
		<input type="submit" name="action" value="modifier"/>
		<br/>
		<input type="submit" name="action" value="supprimer"/>
		<?php
			}
			else{
				?>
		<input type="submit" name="action" value="ajouter"/>
				<?php
			}
			?>
		<input type="hidden" name="table" value="<?php echo $table ?>"/>
		</fieldset>
		</form>
		
	</body>
</html>
