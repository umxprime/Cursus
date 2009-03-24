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
$id = ($_POST['id'])?$_POST['id']:$_GET['id'];
if(!$id or $id<0 or $id==$_POST['old_id']){
	$cols = liste_colonnes("ecoles");
	for($nc=0;$nc<count($cols);$nc++){
		$nom = $cols['nom'];
		$ligne[$nom]=(empty($_POST[$nom]))?-1:$_POST[$nom];
	}
	$action="ajouter";
}
else 
{
	$requete = "SELECT * FROM ecoles WHERE id = '".$id."';";
	$resultat = mysql_query($requete, $connexion);
	$ligne = mysql_fetch_array($resultat);
	$action = "modifier";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<head>
<link rel="stylesheet" href="cursus.css" type="text/css">
<title><?php echo $ligne['nom'] ?></title>
</head>
<body>
<form id="formulaire" name="formulaire" action="ajouter_ecole.php" method="post">
<?php 
	$src = $_SERVER['PHP_SELF'];
	echo "<div class=\"selecteur_periode\">P&eacute;riode : ";
	echo selecteur_objets($src, "ecoles", "nom", "id", $connexion, $id,0,1);
	echo "\n</div>";
	echo "<div class=\"selecteur_periode\">Nom : ";
	echo affiche_ligne("nom",$ligne['nom']); 
	echo "\n</div>";
	echo "<div class=\"selecteur_periode\">Rue : ";
	echo affiche_ligne("rue",$ligne['rue']); 
	echo "\n</div>";
	echo "<div class=\"selecteur_periode\">Code Postal : ";
	echo affiche_ligne("cp",$ligne['cp']); 
	echo "\n</div>";
	echo "<div class=\"selecteur_periode\">Ville : ";
	echo affiche_ligne("ville",$ligne['ville']); 
	echo "\n</div>";
	echo "<div class=\"selecteur_periode\">T&eacute;l&eacute;phone : ";
	echo affiche_ligne("tel",$ligne['tel']); 
	echo "\n</div>";
 ?>
<input type="hidden" name="old_id" value="<?php echo ($ligne["id"])?$ligne["id"]:-1; ?>">
<input type="submit" name="action" value="<?php echo $action; ?>">
</form>
</body>
</html>