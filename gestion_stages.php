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
//echo $idd_session."|";
//on requiert les variables de connexion;
require("connect_info.php");
//puis la connexion standard;
//echo $idd_session."|";
require("connexion.php");
//echo $idd_session."|";
include("fonctions.php");
//echo $idd_session."|";
include("inc_sem_courant.php");
//arrive-t-on avec un identifiant de stage (édition du stage concerné ou nouveau stage);

(!$_GET['session'])?$idd_session = $_POST['session']:$idd_session = $_GET['session'];
if(!$idd_session){
	$idd_session=$_SESSION['lasession'];
}else{
	session_register('lasession');
	$_SESSION['lasession']=$idd_session;
}
$phrase_en_cours = "Voir stages en cours";
//quelle action;
(!$_POST['afaire'])?$afaire = "rien":$afaire = $_POST['afaire'];
//echo "ACTION : ".$afaire."<br />";
$dateCourante = date("d:m:Y:H:i");
//echo $dateCourante;
$expDate = explode(":",$dateCourante);
$arrDateCourante= array('jour'=>$expDate[0],'mois'=>$expDate[1],'annee'=>$expDate[2]);
	$fin = (empty($_POST['fin']))?$arrDateCourante:$_POST['fin'];
	$debut = (empty($_POST['debut']))?$arrDateCourante:$_POST['debut'];

//si nouveau stage : l'enregistrer et récuperer son indentifiant
if($afaire=="nouveau"){
	$req = "INSERT INTO stages (id, etudiant, debut, fin,lieu, rapport, credits, valide, appreciation) VALUES ";
	$req .= "('','".$_POST["etudiant"]."','";
	$req.= $_POST["debut"]['annee']."-".$_POST["debut"]['mois']."-".$_POST["debut"]['jour']."','";
	$req .= $_POST["fin"]['annee']."-".$_POST["fin"]['mois']."-".$_POST["fin"]['jour']."','".$_POST["lieu"];
	$req .= "','".$_POST["rapport"]."','".$_POST["credits"]."','".$_POST["valide"]."','".$_POST["appreciation"]."');";
	//echo $req;
	$res = mysql_query($req);
	$stage_id = mysql_insert_id();
}
if($afaire=="valid_modif"){
	$req = "UPDATE stages set etudiant='$etudiant_mod', debut='";
	$req .= $_POST["debut_mod"]['annee']."-".$_POST["debut_mod"]['mois']."-".$_POST["debut_mod"]['jour']."', fin='";
	$req .= $_POST["fin_mod"]['annee']."-".$_POST["fin_mod"]['mois']."-".$_POST["fin_mod"]['jour']."', lieu='".$_POST["lieu_mod"];
	$req .= "',rapport='".$_POST["rapport"]."', credits='".$_POST["credits"]."', valide='".$_POST["valide"]."',";
	$req .= "appreciation='".$_POST["appreciation"]."', soutenance='".$_POST["soutenance"]."', convention='".$_POST["convention"]."' ";
	$req .= ", periode='".$_POST['periode_mod']."' WHERE id=".$_POST['stage_id'].";";
//	echo $req;
	$res = mysql_query($req);
	$err = mysql_error();
}
if($afaire=="supprimer"){
	$req = "DELETE FROM stages WHERE id='".$stage_id."';";
	$res = mysql_query($req);
}
(!$_GET['stage_id'])?$stage_id = $_POST['stage_id']:$stage_id = $_GET['stage_id'];
if($stage_id>0){
	$req = "SELECT stages.*, etudiants.nom, etudiants.prenom FROM stages, etudiants ";
	$req .= "where stages.id='".$stage_id."' and etudiants.id=stages.etudiant;";
	$res = mysql_query($req);
	$stage2edit = mysql_fetch_array($res);
	//echo implode(" - ", $stage2edit);
}
//le sélecteur de début de période de recherche est-il positionné sur une date;
(!$_GET['stages_debut'])?$stages_debut = $_POST['stages_debut']:$stages_debut = $_GET['stages_debut'];
//le selecteur de fin de période de recherche est-il positionné sur une date;
(!$_GET['stages_fin'])?$stages_fin = $_POST['stages_fin']:$stages_fin = $_GET['stages_fin'];
//date de début de recherche sur le début du semestre courant ou sur la date de début de recherche du sélecteur;
($stages_debut)?$lim_inf=$stages_debut:$lim_inf=$periode['debut'];
//date de fin de recherche sur la fin du semestre courant ou sur la date de fin de recherche du sélecteur;
($stages_fin)?$lim_sup=$stages_fin:$lim_sup=$periode['fin'];

// établissement de la liste des stages à afficher;
// quelle requête formuler en fonction des données entrantes;
if ($afaire=$phrase_en_cours){
$req = "SELECT stages.*, etudiants.nom, etudiants.prenom, etudiants.mail FROM stages, etudiants ";
$req .= "where (stages.valide = '0' or (stages.debut>='".$lim_inf."' and stages.debut<='".$lim_sup."')) and etudiants.id=stages.etudiant;";
}else{
	$req = "SELECT stages.*, etudiants.nom, etudiants.prenom FROM stages, etudiants ";
	$req .= "where stages.debut>='".$lim_inf."' and stages.debut<='".$lim_sup."' and etudiants.id=stages.etudiant;";
	}
	//echo $req;
$res = mysql_query($req);
//$stages = mysql_fetch_array($res);
$nres = mysql_num_rows($res);
	//echo $req;
	if ($nres>0){
		//echo "nres :".$nres;
		$destsMail="";
		//$chaineNot = "SELECT * FROM etudiants WHERE id !='";
		$tablEvals ="<table><tr>\n<th>Etudiant</th>";
		$tablEvals .= "\t<th>Supprimer</th>\n";
		$tablEvals .= "\t<th>Debut</th>\n";
		$tablEvals .= "\t<th>Fin</th>\n";
		$tablEvals .= "\t<th>Lieu</th>\n";
		$tablEvals .= "\t<th>Validation <br/>#1</th>\n";
		$tablEvals .= "\t<th>Edition</th>\n";
		$tablEvals.= "</tr>";

		$neval = 1;
		while($stage = mysql_fetch_array($res)){
			$tablEvals .= "<tr>\n\t<td>";
			$tablEvals .= $stage['prenom']. " ".$stage['nom'];
			$tablEvals .= "\n\t</td>\n\t<td>";
			$prenomFormat = strtolower(str_replace(utf8_encode("é"),"e",$stage['prenom']));
			$nomFormat = strtolower($stage['nom']);
			$destsMail .= $stage['prenom']." ".$stage['nom'];
			$destsMail .= "<".$prenomFormat{0}.$nomFormat."@esa-cambrai.net>";
			if($neval<$nres){$destsMail .=", ";}
			//desinscription de l'étudiant
			$tablEvals .= "<a href=\"#\" onClick=\"javascript:document.formulaire.stage_id.value=".$stage['id'];
			$tablEvals .= ";document.formulaire.afaire.value='supprimer';";
			$tablEvals .= " document.formulaire.submit();\">d&eacute;sinscrire</a></td>\n";
			//$tablEvals .="</td>\n";
			$tablEvals .="<td>";
			$tablEvals .=$stage['debut'];
			$tablEvals .= "</td>\n";
			$tablEvals .="<td>";
			$tablEvals .=$stage['fin'];
			$tablEvals .= "</td>\n";
			$tablEvals .="<td>";
			$tablEvals .=$stage['lieu'];
			$tablEvals .= "</td>\n";
			$tablEvals .="<td>";
			switch($stage['valide']){
				case 0: $tablEvals .= "en cours";
				break;
				case 1 : $tablEvals .= "valid&eacute;";
				break;
				case 2 : $tablEvals .= "non valid&eacute;";
				break;
			}
			$tablEvals .= "</td>\n<td>";
			$tablEvals .= "<a href=\"#\" onClick=\"javascript:document.formulaire.stage_id.value=".$stage['id'];
			$tablEvals .= ";document.formulaire.afaire.value='modifier';";
			$tablEvals .= " document.formulaire.submit();\">&Eacute;diter</a></td>\n";
			
			$neval++;
		}
		$tablEvals .="</tr>\n</table>";
		//echo $chaineNot;
	}

	?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<?php
	include("inc_css_thing.php");
	?>

<title>Stages <?php echo $periode['nom'] ?></title>
</head>
<body>
	<?php
	include("barre_outils.php") ;
	//include("inc_nav_sem.php");
	?>

<p>
<?php if($nres>0){ 
echo $tablEvals;
}
$chaineNot.= " SELECT etudiants.*, niveaux.niveau, niveaux.etudiant FROM niveaux, etudiants WHERE niveaux.periode = '".$semestre_courant."' AND niveaux.niveau<11 and niveaux.etudiant=etudiants.id ORDER BY niveaux.niveau";
//echo $chaineNot;
$resNot = mysql_query($chaineNot);
?>

<br />
<form id="formulaire" name="formulaire" action="gestion_stages.php" method="post">
	<input type="hidden" name="afaire" value="" >
<?php
if ($stage_id >0){
	echo "<input type=\"hidden\" name=\"stage_id\" value=\"".$stage_id."\" >";
	}
	else
	{
		echo "<input type=\"hidden\" name=\"stage_id\" value=\"-1\" >"; 
	}
//echo "Stage ID : ".$stage_id;

if ($stage_id >0){
	echo "<P><h2>-------------------- Modification d'un stage --------------------------<br /></h2>";
	echo "<div class=\"nom\">Etudiant : ";
	echo "<input type=hidden name='etudiant_mod' value='".$stage2edit['etudiant']."' >";
	echo $stage2edit['prenom']." ".$stage2edit['nom'];
	echo "\n</div>";
	
	if(!$_POST["debut_mod"]['mois'] or $_POST['afaire']=='modifier'){
		$arrDeb = explode("-",$stage2edit["debut"]);
		$debut_mod['mois']=$arrDeb[1];		
		$debut_mod['annee']=$arrDeb[0];
		$debut_mod['jour']=$arrDeb[2];
		$arrFin = explode("-",$stage2edit["fin"]);
		$fin_mod['mois']=$arrFin[1];		
		$fin_mod['annee']=$arrFin[0];
		$fin_mod['jour']=$arrFin[2];
	}
	echo "<div class=\"selecteur_dates\" >Date de d&eacute;but : ";
	echo selecteurDate("gestion_stages.php","debut_mod", $debut_mod['mois'] , $debut_mod['annee'], $debut_mod['jour']);
	echo "\n</div>";
	echo "<div class=\"selecteur_dates\" >Date de fin : ";
	echo selecteurDate("gestion_stages.php","fin_mod", $fin_mod['mois'] , $fin_mod['annee'], $fin_mod['jour']);
	echo "\n</div>";
	echo "<div class=\"lieu\">Lieu du stage : ";
	//echo $stage2edit['lieu'];
	echo affiche_ligne("lieu_mod",$stage2edit['lieu']);
	echo "\n</div>";
	echo "<br />";
	echo "Nombre de cr&eacute;dits allou&eacute;s au stage :";
	echo selecteur_objets("",0,"credits","credits",$connexion,$stage2edit['credits'],liste_numero(1,20,1,"","&nbsp;"),0);
	echo "<br /><br />";
	echo "<div class=\"acoche\">Convention OK<input type=checkbox name=\"convention\" value=1";
	if($stage2edit['convention']){echo " checked ";}
	echo ">\n";
	echo "M&eacute;moire OK<input type=checkbox name=\"memoire\" value=1";
	if($stage2edit['memoire']){echo " checked ";}
	echo ">\n";
	echo "Soutenance OK<input type=checkbox name=\"soutnance\" value=1";
	if($stage2edit['soutenance']){echo " checked ";}
	echo ">\n";
	echo "Valid&eacute;<input type=checkbox name=\"valide\" value=1";
	if($stage2edit['valide']){echo " checked ";}
	echo ">\n";
	echo "\n</div>";
	echo "<br />";
	echo "semestre d'affectation du stage : ";
	if($_POST['$periode_mod']){$sem=$_POST['$periode_mod'];}else if($stage2edit['periode']){$sem=$stage2edit['periode'];}else{$sem= $semestre_courant;}
	echo selecteur_semestres($connexion, $sem, "periode_mod", '');
	echo "<br />";
	echo "appr&eacute;ciation : <br />";
	echo affiche_champs("appreciation",$stage2edit['appreciation'],80,4);
	echo "<br />";
	echo "<input type=button name='valid_modif' value='Valider les modifications' ";
	echo "onclick=\"javascript:document.formulaire.afaire.value='valid_modif';document.formulaire.submit();\" />";
}

?>

</p>
<br /><br /><br />
<P><h2>-------------------- Nouveau stage --------------------------<br /></h2>

<P>Nom de l'etudiant : <select id="etudiant" name="etudiant" 
onchange="javascript:document.formulaire.stage_id.value=-1;document.formulaire.submit();">
<?php
$n=0;
$label_sem=0;
$c_select="";
while($etu = mysql_fetch_array($resNot)){
	//echo $resteModule['code']."\n";
	if ($label_sem != $etu['niveau']){
		if($label_sem!=0){$c_select .= "\t<\optgroup>";}
		$c_select .="\t<optgroup label='semestre ".$etu['niveau']."'>";
		$label_sem=$etu['niveau'];
	}
	$c_select .= "\t\t<option value=\"".$etu["id"]."\"";
	if($etu['id']==$stage2edit['etudiant']){$c_select .= " selected ";}
	if($etu['id']==$_POST['etudiant']){$c_select .= " selected ";}
	$c_select .= ">".$etu['nom']." ".$etu['prenom']."</option> <br />\n";
}
$c_select .= "\t<\optgroup>";
echo $c_select;
?>
</select></P>
<?php
echo "<div class=\"selecteur_dates\" >Date de d&eacute;but : ";
	echo selecteurDate("gestion_stages.php","debut", $debut['mois'] , $debut['annee'], $debut['jour']);
	echo "\n</div>";
	echo "<div class=\"selecteur_dates\" >Date de fin : ";
	echo selecteurDate("gestion_stages.php","fin", $fin['mois'] , $fin['annee'], $fin['jour']);
	echo "\n</div>";
	echo "<div class=\"lieu\">Lieu du stage : ";
	echo affiche_ligne("lieu",$_POST['lieu']);
	echo "\n</div>";
	
?>

<input type="hidden" name="session" value="<?php echo $idd_session; ?>" />


<input type="submit" value="nouveau stage" onClick="javascript:document.formulaire.afaire.value='nouveau';" />
<p>
<?php //echo affiche_champs("dests",$destsMail,80,8); ?></p>
</form>
</body>
</html>
