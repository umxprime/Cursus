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
include("inc_sem_courant.php");
$id = ($_POST['id'])?$_POST['id']:$_GET['id'];
if(!$id or $id<0 or $id==$_POST['old_id']){

	$cols = liste_colonnes("etudiants");
	for($nc=0;$nc<count($cols);$nc++){
		$nom = $cols[$nc]['nom'];
		$ligne[$nom]=(empty($_POST[$nom]))?-1:$_POST[$nom];
		//echo "cooooool!:".$nom.":".$line[$nom]."\n";
	}
	
	
}
else
{
//	$requete = "SELECT etudiants.*, niveaux.cycle FROM etudiants, niveaux WHERE ";
//	$requete .= "etudiants.id = '".$id."' and niveaux.etudiant=etudiants.id and niveaux.periode='".$semestre_courant."';";
	$requete = "SELECT etudiants.* FROM etudiants WHERE etudiants.id = '".$id.";'";
	$resultat = mysql_query($requete, $connexion);
	$ligne = mysql_fetch_array($resultat);
	
	if ($ligne['passw']==''){
		echo "passw";
		$ligne['passw']=generatePassword();
	}
	if ($ligne['log']==''){
		echo "log";
		$prenomFormat = strtolower(str_replace(utf8_encode("é"),"e",$ligne['prenom']));
		$nomFormat = strtolower($ligne['nom']);
		$log = $prenomFormat{0}.$nomFormat;
		$ligne['log']=$log;
	}
	if ($ligne['mail']==''){
		echo "mail";
		$prenomFormat = strtolower(str_replace(utf8_encode("é"),"e",$ligne['prenom']));
		$nomFormat = strtolower($ligne['nom']);
		$log = $prenomFormat{0}.$nomFormat;
		$ligne['mail']=$log."@esa-npdc.net";
	}
	
	
	$requete = "SELECT * FROM niveaux WHERE etudiant='".$id."' AND periode='".$semestre_courant."';";
	$resultat = mysql_query($requete,$connexion);
	$semestre_actuel = mysql_fetch_array($resultat);
	$req = "SELECT niveaux.*, professeurs.nom_complet from niveaux,tutorats, professeurs";
	$req .= " WHERE niveaux.etudiant='".$ligne['id']."' AND tutorats.etudiant='".$ligne['id']."'";
	$req .= " AND tutorats.semestre = niveaux.periode AND professeurs.id=tutorats.professeur ORDER BY niveaux.periode;";
	$resultat = mysql_query($req);
	$nt=0;
	$old=-1;
	$adj="";
	$tbl_tut="<table>\n\t<tr>\n\t\t<td>\n\t\t";
	while($tutorat[$nt]=mysql_fetch_array($resultat)){
		if ($old!=$tutorat[$nt]['semestre']){
			$tbl_tut .= $adj;
			$tbl_tut .="\n<table>\n\t<tr>\n\t\t<td>Semestre ".$tutorat[$nt]['niveau']."\n\t\t</td>\n\t</tr>";
			$old=$tutorat[$nt]['semestre'];
		}
		$tbl_tut .="\n\t<tr>\n\t\t<td>".$tutorat[$nt]['nom_complet']."\n\t\t</td>\n\t</tr>";
		$nt++;
		$adj="\n</table>\n\t\t</td>\n\t\t<td>";
	}
	$tbl_tut .="\n</table>\n\t\t</td>\n\t</tr>\n</table>\n";
	
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<head>
<link rel="stylesheet" href="cursus.css" type="text/css">
<title><?php echo $ligne['prenom']." ".$ligne['nom']; ?></title>
</head>
<body>
<form id="formulaire" name="formulaire" action="reg_etudiant.php"
	method="post">
<table style="text-align: left; width: 626px; height: 228px;" border="1"
	cellpadding="2" cellspacing="2">
	<tbody>
		<tr>

			<td style="width: 70px;">Nom</td>
			<td colspan="7" rowspan="1">
			<?php
			$peut_ajouter=($_SESSION['auto']=="a")?1:0;
			//echo selecteurObjets("edition_modules.php","modules","id","intitule","id",$connexion,$ligne['id'],0,$peut_ajouter);
			echo selecteurObjets("edition_etudiant.php","etudiants","id","prenom,nom","id",$connexion,$ligne['id'],0,$peut_ajouter, "nom");

			?></td>
		</tr>
		<tr>
			<td style="width: 70px;">Modifier ici</td>
			<td colspan="3" rowspan="1">
			<?php echo affiche_ligne("nom",$ligne['nom'],30); ?></td>
			<td>Pr&eacute;nom</td>
			<td colspan="3" rowspan="1">
			<?php echo affiche_ligne("prenom",$ligne['prenom'],30); ?></td>
		</tr>
		<tr>
			<td style="width: 70px;">Semestre</td>
			<td style="width: 52px;">
			<?php echo affiche_ligne("niveau",$semestre_actuel['niveau'],2); ?></td>
			<td style="width: 70px;">log</td>
			<td style="width: 78px;">
			<?php echo affiche_ligne("log",$ligne['log'],10); ?></td>
			<td style="width: 54px;">mail</td>
			<td style="width: 83px;">
			<?php echo affiche_ligne("mail",$ligne['mail'],30); ?></td>
			<td style="width: 105px;">password</td>
			<td style="width: 58px;"><?php echo affiche_ligne("passw",$ligne['passw'],8); ?></td>
		</tr>
			<tr>
			<td style="width: 70px;">Cycle :</td>
			<td colspan="6" rowspan="1">
			<?php echo selecteurObjets("",'cycles','cycle', 'nom', 'id', $connexion, $semestre_actuel['cycle'],'',0, 'nom'); ?></td>
			<td>Pr&eacute;nom</td>
		</tr>
		<tr>
			<td colspan="8" rowspan="1">
			<?php echo $tbl_tut; ?></td>
		</tr>

	</tbody>
</table>
<input type="hidden" name="old_id" value="
<?php echo ($ligne["id"])?$ligne["id"]:-1; ?>">
<input type="submit" name="action" value="valider les modifications">
</form>
</body>
</html>
