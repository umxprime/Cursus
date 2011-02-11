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
$table = $_POST["table"];
if(!$table){$table="semestres";}
$id_clef = donne_clef($table, $connexion); 
$$id_clef = ($_POST[$id_clef])?$_POST[$id_clef]:$_GET[$id_clef];
$dates_recup = array("deb"=>"D&eacute;but","fin"=>"Fin","rattrapage"=>"Rattrapage","validation"=>"Validation");
foreach($dates_recup as $nom_gene=>$aff){
	$nom_var = "mois_".$nom_gene;
	$$nom_var = ($_POST[$nom_var])?$_POST[$nom_var]:$_GET[$nom_var];
	if(!$$nom_var){$$nom_var=date("m");}
	//echo $nom_var." : ".$$nom_var."<br />\n";
	$nom_var = "annee_".$nom_gene;
	$$nom_var = ($_POST[$nom_var])?$_POST[$nom_var]:$_GET[$nom_var];
	//echo $nom_var." : ".$$nom_var."<br />\n";
	if(!$$nom_var){$$nom_var=date("Y");}
	$nom_var = "jour_".$nom_gene;
	$$nom_var = ($_POST[$nom_var])?$_POST[$nom_var]:$_GET[$nom_var];
	if(!$$nom_var){$$nom_var=date("d");}
}
?>
		<title>Ajout d'un &eacute;l&eacute;ment dans la base "<?php echo $table; ?>"</title>

	</head>
	<body>
	<?php
	if(!$$id_clef){
		$$id_clef = -1;
		$ligne = FALSE;
	}
	else
	{
		$requete = "SELECT * FROM ".$table." WHERE ".$id_clef." = '".$$id_clef."';";
		$resultat = mysql_query($requete, $connexion);
		$ligne = mysql_fetch_array($resultat);
	}

	?>
	<form id="formulaire" name= "formulaire" action="ajouter.php" method="post">
	<?php
	echo "<div class=\"selecteur_semestre\">Semestre : ".selecteur_objets("semestres.php", "semestres", "nom", $id_clef, $connexion, $$id_clef,0,1);
	foreach($dates_recup as $nom_gene=>$nom_aff){
		$mois = "mois_".$nom_gene;
		$annee = "annee_".$nom_gene;
		$jour = "jour_".$nom_gene;
	echo "<div class=\"selecteur_dates\" >".$nom_aff." : ".selecteur_date("semestres.php",$nom_gene, $$mois , $$annee, $$jour);
	}
	
	if($$id_clef > 0){
		?>
			<input type="submit" name="action" value="modifier"><br />
			<input type="submit" name="action" value="supprimer">
			<?php
		}
		else{
			?>
			<input type="submit" name="action" value="ajouter">
			<?php
		}
	?>
	<input type="hidden" name="table" value="<?php echo $table ?>">
	</form>
	</body>
</html>
