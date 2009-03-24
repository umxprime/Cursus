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
$outil="infosperso";
include("inc_sem_courant.php");
$table="professeurs";
	$src= $_SERVER['PHP_SELF'];
$id = ($_POST['id'])?$_POST['id']:$_GET['id'];
if($_SESSION['auto']!='a'){$id=$_SESSION['userid'];}
if($_GET['change_id']){
	$old_id=-1;
}
//echo "id= ".$id;
//echo "old_id= ".$_POST['old_id'];
if((!$id or $id<0 or $id==$_POST['old_id']) and $_POST['change_id']!=1){
	$cols = liste_colonnes($table);
	for($nc=0;$nc<count($cols);$nc++){
		$nom = $cols['nom'];
		$ligne[$nom]=(empty($_POST[$nom]))?-1:$_POST[$nom];
	}
}
else
{	
	$requete = "SELECT * FROM ".$table." WHERE id = '".$id."';";
	$resultat = mysql_query($requete, $connexion);
	$ligne = mysql_fetch_array($resultat);
}
$ok_edit=($_SESSION['auto']=='a' or $_SESSION['userid']==$ligne['id'])?1:0;
//echo "ok_edit : ".$ok_edit;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<head>
<link rel="stylesheet" href="cursus.css" type="text/css">
<title>&Eacute;dition des informations personnelles de <?php echo $ligne['nom_complet'] ?></title>
</head>
<body>
<?php include("barre_outils.php"); ?>
<form id="formulaire" name="formulaire" action="reg_prof.php"
	method="post">
<table style="text-align: left; width: 802px; height: 88px;" border="1"
	cellpadding="2" cellspacing="2">
	<tbody>
		<tr>
			<td colspan="6" rowspan="1" style="width: 180px;">
			<?php
			if($ok_edit>0 and $_SESSION['auto']=='a'){
				$peut_ajouter=($_SESSION['auto']=="a")?1:0;
				echo selecteurObjets("edition_prof.php","professeurs","id","nom_complet","id",$connexion,$ligne['id'],0,$peut_ajouter);
			}else{
				echo $ligne['nom_complet'];	
				echo "<input type='hidden' name='id' value='".$id."'>";
			}
			?></td>
		</tr>
		<tr>
			<td style="width: 70px;">Nom</td>
			<td style="width: 180px;">
			<?php echo ($ok_edit>0)?affiche_ligne("nom",$ligne['nom'],16):$ligne['nom']; ?>
			</td>
			<td style="width: 70px;">Pr&eacute;nom</td>
			<td style="width: 180px;">
			<?php echo ($ok_edit>0)?affiche_ligne("prenom",$ligne['prenom'],16):$ligne['prenom']; ?>
			</td>
			<td style="width: 70px;">Identifiant</td>
			<td style="width: 180px;">
			<?php echo ($ok_edit>0)?affiche_ligne("log",$ligne['log'],16):""; ?>
			</td>
		</tr>
		<tr>
			<td colspan="4" rowspan="1" style="width: 180px;">Mot de passe :
			<?php 
			if($ok_edit>0){
				if($_POST['en_clair']=='1'){
					echo affiche_ligne("passw",($ligne['passw']),16);
				}else{
					echo affiche_pass("passw",($ligne['passw']));
				}
			}else{
				echo "---------------"; 
			}
				?>
				
				</td>
			
			<td colspan="2" rowspan="1" style="width: 180px;">
			en clair : <input type="checkbox" name="en_clair" value=1
			<?php if($_POST['en_clair']==1){echo " checked ";} ?>
			onChange="document.formulaire.action='edition_prof.php'; 
			document.formulaire.change_id.value=1; document.formulaire.submit();">
			</td>
		</tr>
		<tr>
			<td style="width: 70px;">Nouveau</td>
			<td style="width: 180px;">
			<?php 
		if($ok_edit>0){
				if($_POST['en_clair']=='1'){
					echo affiche_ligne("new_pass1","",16);
				}else{
					echo affiche_pass("new_pass1","");
				}
			}else{
				echo "---------------"; 
			}
			?></td>
			<td style="width: 70px;">Confirmer</td>
			<td style="width: 180px;">
			<?php 
			if($ok_edit>0){
				if($_POST['en_clair']=='1'){
					echo affiche_ligne("new_pass2","",16);
				}else{
					echo affiche_pass("new_pass2","");
				}
			}else{
				echo "---------------"; 
			}
			?></td>
			<td colspan="2" rowspan="1" style="width: 180px;">
		</tr>
	</tbody>
</table>
<?php 
	echo "<div class=\"selecteur_activite\" >&Eacute;cole; : ";
	echo selecteurObjets("","ecoles", "ecole","nom", "id", $connexion, $ligne['ecole'], 0, 0);
	echo "\n</div>";
?>
	
<input type="hidden" name="old_id" value="<?php echo ($ligne["id"])?$ligne["id"]:-1; ?>">
<input type="hidden" name="change_id" value="0">
<input type="submit" name="action" value="valider les modifications">
</form>
</body>
</html>
