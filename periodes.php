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
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<head>
<?php 
include("inc_css_thing");
?>

<?php
require("connect_info.php");
require("connexion.php");
include("fonctions.php");
$id = ($_POST['id'])?$_POST['id']:$_GET['id'];
$dateCourante = date("d:m:Y:H:i");
//echo $dateCourante;
$expDate = explode(":",$dateCourante);
$arrDateCourante= array('jour'=>$expDate[0],'mois'=>$expDate[1],'annee'=>$expDate[2]);
//echo "dateCourante : ".$arrDateCourante['jour'].$arrDateCourante['mois'].$arrDateCourante['annee']."<br/>";
//echo "jour : ".$expDate[0]."  mois : ".$expDate[1]."  annee : ".$expDate[2];

//echo "dateDebut : ".implode(":",$dateDebut)."<br/>";

//echo "dateFin : ".$dateFin['jour'].$dateFin['mois'].$dateFin['annee']."<br/>";

?>
<title>Ajout d'une période</title>

</head>
<body>
<?php
if(!$id or $id<0 or $id==$_POST['old_id']){
	$ligne = FALSE;
	$nom=(empty($_POST['nom']))?"nom de la période":$_POST['nom'];
	$activite=(empty($_POST['activite']))?-1:$_POST['activite'];
	$dateFin = (empty($_POST['dateFin']))?$arrDateCourante:$_POST['dateFin'];
	$dateDebut = (empty($_POST['dateDebut']))?$arrDateCourante:$_POST['dateDebut'];
}
else 
{
	$requete = "SELECT * FROM periodes WHERE id = '".$id."';";
	$resultat = mysql_query($requete, $connexion);
	$ligne = mysql_fetch_array($resultat);
	$nom = stripslashes($ligne['nom']);
	$activite= $ligne['activite'];
	$expDate = explode("-",$ligne['debut']);
	$dateDebut = array('annee'=>$expDate[0],'mois'=>$expDate[1],'jour'=>$expDate[2]);
	$expDate = explode("-",$ligne['fin']);
	$dateFin = array('annee'=>$expDate[0],'mois'=>$expDate[1],'jour'=>$expDate[2]);
	
}

?>
<form id="formulaire" name="formulaire" action="ajouter_periode.php"
	method="post">
	<?php
	echo "<div class=\"selecteur_periode\">P&eacute;riode : ";
	echo selecteur_objets("periodes.php", "periodes", "nom", "id", $connexion, $id,0,1);
	echo "\n</div>";
	echo "<div class=\"nom_periode\">";
	echo affiche_ligne("nom",$nom);
	echo "\n</div>";
	echo "<div class=\"selecteur_dates\" >Date de d&eacute;but : ";
	echo selecteurDate("periodes.php","dateDebut", $dateDebut['mois'] , $dateDebut['annee'], $dateDebut['jour']);
	echo "\n</div>";
	echo "<div class=\"selecteur_dates\" >Date de fin : ";
	echo selecteurDate("periodes.php","dateFin", $dateFin['mois'] , $dateFin['annee'], $dateFin['jour']);
	echo "\n</div>";
	echo "<div class=\"selecteur_activite\" >Activit&eacute; : ";
	echo selecteurObjets("periodes.php","activites", "activite","nom", "id", $connexion, $activite, 0, 0);
	echo "\n</div>";

	if($id > 0){
		?>
<input type="submit" name="action" value="modifier">
<br />
<input type="submit" name="action" value="supprimer">
<?php
	}
	else{
		?>
<input type="submit" name="action" value="ajouter">
		<?php
	}
	?>
	<input type="hidden" name="old_id" value="
<?php echo $id ?>">
<input type="hidden" name="table" value="
<?php echo $table ?>">
</form>
<table width="100%">

</table>
</body>
</html>
