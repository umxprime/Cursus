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
include("regles_utilisateurs.php");
include("fonctions.php");
$outil="tutorat";
//echo $idd_session."|";
include("inc_sem_courant.php");
$dateCourante = date("Y-m-d");
include("inc_nav_sem.php");
$form0 ="";
//echo $semestre['titre'];
if (isset($_POST['tuteur'])){
	//$req = "SELECT * FROM tutorats where professeur = '".$_POST['tuteur']."' AND semestre='".$_POST['periode']."' AND trash !=1;";
	$tuteur = $_POST['tuteur'];
	$rprof = "SELECT * FROM professeurs WHERE id='".$tuteur."';";
	$resprof = mysql_query($rprof);
	$prof = mysql_fetch_array($resprof);
	$nomTuteur = $prof['nom_complet'];
}else{
	$tuteur = $_SESSION['userid'];
	$nomTuteur = $_SESSION['username'];
}
if($droits[$_SESSION['auto']]['admin_tutorats']){
	
	$form0 .= "<form id=\"formulaire\" name=\"formulaire\" action=\"tutorats.php?nPeriode=$semestre_courant\" method=\"post\">";
	$form0 .= selecteurObjets("tutorats.php?nPeriode=$semestre_courant","professeurs","tuteur","nom_complet","id",$connexion,$tuteur,0,0,"nom");
	$form0 .="<input type=\"hidden\" name=\"nPeriode\" value=\"".$semestre_courant."\">";
	$form0 .= "</form>";
}
if($droits[$_SESSION['auto']]['voir_tutorats']){
	$req = "SELECT * FROM tutorats WHERE professeur = '".$tuteur."' AND semestre='".$semestre_courant."' AND trash !=1;";
}else{
	$req="";
}
//echo $req;
$res = mysql_query($req);
//echo $req;
$nres=mysql_num_rows($res);

if ($nres>0){
	
	//echo "nres :".$nres;
	$chaineNot = "SELECT * FROM etudiants WHERE id !='";
	$tablEvals ="<table><tr>\n<th>Etudiant</th>";
	$tablEvals .= "\t<th>Annuler <br/>inscription</th>\n";
	for($i=1; $i<=5;$i++){
		$tablEvals .= "\t<th>Rdv<br/>#".$i."</th>\n";
	}
	$tablEvals .= "\t<th>Session <br/>#1</th>\n";
	$tablEvals .= "\t<th>Session <br/>#2</th>\n";
	$tablEvals.= "</tr>";

	$ntuts = 1;
	while($tutorat = mysql_fetch_array($res)){
		$req = "SELECT * FROM rdv WHERE tutorat='".$tutorat['id']."';";
		$resRdv = mysql_query($req);
		$rdv=array();
		while($rdv[]=mysql_fetch_array($resRdv)){
			;
		}
		$req = "SELECT * FROM etudiants WHERE id='".$tutorat['etudiant']."';";
		//echo "requete : ".$req."\n";
		$resEtu= mysql_query($req);
		$etudiant = mysql_fetch_array($resEtu);
		$tablEvals .= "<tr>\n\t<td>";
		$tablEvals .= utf8_encode($etudiant['prenom'])." ".utf8_encode($etudiant['nom']);
		$tablEvals .= "\n\t</td>\n\t<td>";
		//desinscription de l'étudiant
		$tablEvals .= "<a href=\"#\" onClick=\"document.formulaire2.action.value='desinscrire';";
		$tablEvals .= "document.formulaire2.tutorat.value='".$tutorat['id']."';";
		$tablEvals .="document.formulaire2.submit();\">d&eacute;sinscrire</a></td>\n<td>";
		$sauf[] = $etudiant['id'];
		for($d=1;$d<=5;$d++){
			if(is_array($rdv[$d-1])){
				$tablEvals .= "<a href ='edit_rdv.php?rdv=".$rdv[$d-1]['id']."'>";
				if (strlen($rdv[$d-1]['cr'])>1){
					$tablEvals .= "modifier";
				}else{
					$tablEvals .= "cr&eacute;er";
				}
				$tablEvals .= "<a>\n";
			}
			$tablEvals .="\t</td>\n\t<td>\n";
			//echo $d."\n";
		}
		//$tablEvals .="</td>\n";
		$req = "SELECT * FROM evaluations WHERE tutorat = '".$tutorat['id']."';";
		$resEval = mysql_query($req);
		$eval = mysql_fetch_array($resEval);
		$tablEvals .="<a href = \"edit_tutorats.php?eval=".$eval['id']."\">";
		//$tablEvals .="<a href = \"edit_eval.php?eval=".$eval['id']."\">";
		$tablEvals .= (empty($eval['note_1']))?"&eacute;diter":$eval['note_1'];
		$tablEvals .= "</a></td>\n";
		$tablEvals .="<td>";
		if(!empty($eval['note_1'])){
			if(strpos("__efEF",$eval['note_1'])){
				//$tablEvals .="<a href = \"edit_eval.php?eval=".$eval['id']."&session_name()=".session_id()."\">";
				$tablEvals .="<a href = \"edit_tutorats.php?eval=".$eval['id']."\">";
				$tablEvals.= (empty($eval['note_2']) )?"&eacute;diter":$eval['note_2'];
				$tablEvals .= "</a>";
			}
		}
		$tablEvals .= "</td>\n</tr>";
		$ntuts++;
	}
	$tablEvals .="\n</table>";
}
//echo $chaineNot;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<head>
<link rel="stylesheet" href="cursus.css" type="text/css">
<title><?php echo $module['intitule'] ?></title>
</head>
<body>
<?php include("barre_outils.php"); ?>

<p>
<?php echo $form0;?>
<h2>
<?php //echo $chaineNot;
echo "Tutorats ".utf8_encode($nomTuteur)." / <a href=\"sessions.php?nPeriode=".$periode['id']."\">".$periode['nom'] ?></a></h2>
</p>
<p>
<?php if($nres>0){ ?>

<?php
echo $tablEvals;
?></p>
<?php
}
$chaineNot.= " GROUP BY semestre, nom";
//echo $chaineNot;
$resNot = mysql_query($chaineNot);
?>
<p>
<h2>Inscrire un étudiant à ce module</h2>
</p>
<P>
<form id="formulaire2" name="formulaire2" action="reg_tutorats.php"
	method="post">
<P>Nom de l'etudiant : <select id="etudiant" name="etudiant">
<?php
echo liste_etudiants($sauf, $connexion, $periode['id'],$_SESSION["ecole"],$droits[$_SESSION['auto']]['voir_tous_sites']);
?>
</select></P>
<input type="hidden" name="periode" value="<?php echo $semestre_courant; ?>" >
<input type="hidden" name="tuteur" value="<?php echo $tuteur; ?>" >
<input type="hidden" name="tutorat" value="0">
<input type="hidden" name="action" value="inscrire">
<input type="submit" value="inscrire">
</form>
</p>
<?php ?>
</body>
</html>
