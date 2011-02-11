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
require("connect_info.php");
//puis la connexion standard;
require("connexion.php");
$dateCourante = date("Y-m-d");
include("inc_sem_courant.php");
function fait_liste_eval($titre, $code,$n1, $a1, $n2, $a2, $creds){
	if(strpos("_ABCDabcd",$n1)){
		$affCreds=$creds;
		$suffixe = "Oui";
	}else{
		$affCreds="0";
		$suffixe = "Non";
	}
	$lst .="<ul class=\"bulletinSeul\">\n";
	$lst .="	<li class=\"traitNom\">\n";
	$lst .="		<ul class=\"bulletinNom\">\n";
	$lst .="			<li class=\"titre\">code</li>\n";
	$lst .="			<li class=\"titreReponse\">".$code."</li>\n";
	$lst .="			<li class=\"titre\">intitul&eacute;</li>\n";
	$lst .="			<li class=\"titreReponse\">".$titre."</li>\n";
	$lst .="		</ul>\n";
	$lst .="</li>\n";

	$lst .="	<li class=\"traitseul\">\n";
	$lst .="		<ul class=\"bulletinComplement\">\n";
	$lst .="			<li class=\"";
	$lst .= "note".$suffixe;
	$lst .= "\">\n";
	$lst .=$n1."</li>\n";
	$lst .="			<li class=\"bulletinCredits";
	$lst .= $suffixe."\"><strong >\n";
	$lst .=$affCreds;
	$lst .="</strong>/".$creds."</li>\n";
	$lst .="			<li class=\"titre\">Appr&eacute;ciation</li>\n";
	$lst .="			<li class=\"bulletinAppreciation\">".$a1."</li>\n";
	$lst .="		</ul>\n";
	$lst .="	</li>\n";
	
	

	if($suffixe=="Non"){
	if(strpos("_ABCDabcd",$n2)){
		$affCreds=$creds;
		$suffixe = "Oui";
	}else{
		$affCreds="0";
		$suffixe = "Non";
	}
		$lst .="	<li class=\"traitseul\">\n";
	$lst .="		<ul class=\"bulletinComplement\">\n";
	$lst .="			<li class=\"";
	$lst .= "note".$suffixe;
	$lst .= "\">\n";
	$lst .=$n2."</li>\n";
	$lst .="			<li class=\"bulletinCredits";
	$lst .= $suffixe."\"><strong >\n";
	$lst .=$affCreds;
	$lst .="</strong>/".$creds."</li>\n";
	$lst .="			<li class=\"titre\">Appr&eacute;ciation</li>\n";
	$lst .="			<li class=\"bulletinAppreciation\">".$a2."</li>\n";
	$lst .="		</ul>\n";
	$lst .="	</li>\n";
	}
	$lst .="</ul>\n";
	return $lst;
}
//echo "semestre courant : ".$semestre_courant;
if(isset($_GET['id_etudiant'])){
	$id_etudiant = $_GET['id_etudiant'];
}else{
	$id_etudiant=1;
}


$requete= "select etudiants.*, niveaux.niveau from etudiants, niveaux where etudiants.id ='".$id_etudiant."'";
$requete .=" AND niveaux.etudiant = '".$id_etudiant."' AND niveaux.periode='".$semestre_courant."';";
$resreq = mysql_query($requete);
$etudiant = mysql_fetch_array($resreq);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<head>
<?php
include("inc_css_thing.php");
?>
<link rel="stylesheet" href="etu_style.css" type="text/css" />
<title><?php echo "vue bulletin de ".$etudiant["prenom"]." ".$etudiant["nom"]." | ".$periode["nom"] ?></title>
</head>
<body>
<div id="global">
<?php include("barre_outils.php"); ?>
<div id="BulletinEntete">
	<ul class="bulletinNom">
		<li class="titre">nom</li>
		<li class="titreReponse"><?php echo $etudiant["nom"];?></li >
		<li class="titre">pr&eacute;nom</li>
		<li class="titreReponse"><?php echo $etudiant["prenom"];?></li>
		<li class="titre">semestre</li>
		<li class="titreReponse"><?php echo $etudiant["niveau"]; ?></li>
	</ul>
</div>
	
	<div id="content">
<?php

$id_etudiant = $etudiant["id"];
$nom_etudiant = $etudiant["nom"];
$prenom_etudiant = $etudiant["prenom"];
$semestre = $etudiant["niveau"];
$tuteur_1 = $etudiant["tuteur_1"];
$tuteur_2 = $etudiant["tuteur_2"];
$total1_credits=0;
$total2_credits=0;
if ($etudiant["semestre"]>4){
	$req = "select evaluations.*, tutorats.niveau, professeurs.nom_complet as enseignants from tutorats, evaluations, professeurs ";
	$req .= "where tutorats.etudiant='".$id_etudiant."' and tutorats.niveau='".$semestre."' ";
	$req .= "and tutorats.trash!=1 and evaluations.tutorat=tutorats.id and professeurs.id = tutorats.professeur;";
	$restut = mysql_query($req);
	//echo "erreur : ".mysql_error();
	
	$nligne = 1;
	$valide=0;
	while($tut = mysql_fetch_array($restut)){
		
		if($tut['niveau']>8){
			$plus = 6;
		}else{
			$plus = floor(($tut['niveau']-1)/2+1);
		}
		$credits=$plus;
		//echo fait_table_eval();
		$titre = "Tutorat ".$tut["enseignants"];
		$code = "TUTO_".$nligne;
		$n1 = (isset($tut['note_1']))?$tut['note_1']:"";
		//$appreciation_1 = (isset($tut['appreciation_1']))?utf8_decode($tut['appreciation_1']):"";
		$a1 = (isset($tut['appreciation_1']))?$tut['appreciation_1']:"";
		$profs = $tut['enseignants'];
		$n2 = (isset($tut['note_2']))?$tut['note_2']:"";
		$a2 = $tut['appreciation_2'];
		if(strpos("_abcdABCD",$n1) or strpos("_abcdABCD",$n2)){
			$valide+=1;
		}
		echo fait_liste_eval($titre,$code,$n1,$a1,$n2,$a2, $plus*($valide-1));
		$nligne++;
	}
	if ($valide >=2){
		$total_acquis += $plus;
	}
	$total_inscrit += $plus;
}

$total_inscrit+=credits;
$requete= "select evaluations.*, session.module as module, modules.credits, modules.intitule, modules.code, modules.enseignants";
$requete .=", niveaux.niveau from evaluations, niveaux, session, modules where evaluations.etudiant = '".$id_etudiant."' ";
$requete .=" and niveaux.niveau = '".$semestre."' and niveaux.etudiant = '".$id_etudiant."' ";
$requete .= "and session.semestre=niveaux.periode and evaluations.session=session.id and modules.id = session.module";
$requete .=";";
//echo $requete;
$resEvals = mysql_query($requete);
//echo mysql_error($resEvals);
//echo mysql_num_rows($resEvals);
while($eval = mysql_fetch_array($resEvals)){
	if($eval['code']!="PP_EVL_".$etudiant['niveau']){
		$titre = $eval["intitule"];
		$code = $eval["code"];
		$n1 = (isset($eval['note_1']))?$eval['note_1']:"";
		//$a1 = (isset($eval['appreciation_1']))?utf8_decode($eval['appreciation_1']):"";
		$a1 = (isset($eval['appreciation_1']))?$eval['appreciation_1']:"";
		$profs = $eval['enseignants'];
		$n2 = (isset($eval['note_2']))?$eval['note_2']:"";
		$a2 = $eval['appreciation_2'];

		echo fait_liste_eval($titre,$code,$n1,$a1,$n2,$a2, $eval[credits]);
		$nligne++;
		if(strpos("_abcdABCD",$n1) or strpos("_abcdABCD",$n2)){
			$total_acquis += $eval['credits'];
		}
		$total_inscrit += $eval['credits'];
	}else{
		//evaluation semestrielle;
		$note1_eval= (isset($eval['note_1']))?$eval['note_1']:"";
		$note2_eval= (isset($eval['note_2']))?$eval['note_2']:"";
		$appr1_eval= (isset($eval['appreciation_1']))?$eval['appreciation_1']:"";
		$appr2_eval= (isset($eval['appreciation_2']))?$eval['appreciation_2']:"";
		$tabl_eval=fait_liste_eval($eval['intitule'],$eval['code'], $note1_eval,$appr1_eval,$note2_eval,$appr2_eval,$eval['credits']);
		if(strpos("_abcdABCD",$note1_eval) or strpos("_abcdABCD",$note2_eval)){
			$total_acquis += $eval['credits'];
		}
		$total_inscrit += $eval['credits'];
	}
}
$total_acquis = min($total_acquis,30);
echo $tabl_eval;
?>
<div id="creditsObtenus">
		Cr&eacute;dit obtenus <strong><?php echo $total_acquis; ?></strong>/<?php echo $total_inscrit; ?>
</div>
</div>
</div>
<p>
<a href="fait_bulletin.php?id_etudiant=<?php echo $id_etudiant; ?>">Editer le bulletin</a>
</p>
</body>
</html>