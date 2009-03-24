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
include("fonctions_eval.php");
function fait_liste_eval($titre, $code,$n1, $a1, $n2, $a2, $creds){
//echo "ctrl-".strlen($n1)."-<br />\n";
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
	$lst .=($code=="STAGE")?"":$n1;
	$lst .="</li>\n";
	$lst .="			<li class=\"bulletinCredits";
	$lst .= $suffixe."\"><strong >\n";
	$lst .=$affCreds;
	$lst .="</strong>/".$creds."</li>\n";
	$lst .="			<li class=\"titre\">Appr&eacute;ciation</li>\n";
	$lst .="			<li class=\"bulletinAppreciation\">".$a1."</li>\n";
	$lst .="		</ul>\n";
	$lst .="	</li>\n";
	
	

	if($suffixe=="Non" and $a1!="-" and $code!="STAGE"){
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


$requete= "select etudiants.*,niveaux.niveau from etudiants, niveaux where etudiants.id ='".$id_etudiant."'";
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
<?php include("barre_outils.php"); 
$plus_nav_semestre = array(array("var"=>"id_etudiant","val"=>$_GET['id_etudiant']));
include("inc_nav_sem.php"); ?>
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
//$tuteur_1 = $etudiant["tuteur_1"];
//$tuteur_2 = $etudiant["tuteur_2"];
$total1_credits=0;
$total2_credits=0;
if ($etudiant["niveau"]>2 and $_SESSION['ecole']==1){
	$req = "select evaluations.note_1,evaluations.note_2,evaluations.appreciation_1, evaluations.appreciation_2, professeurs.nom_complet as enseignants";
	$req .=" from tutorats, evaluations, professeurs ";
	$req .= "where tutorats.etudiant='".$id_etudiant."' and tutorats.semestre='".$semestre_courant."' ";
	$req .= "and tutorats.trash!=1 and evaluations.tutorat=tutorats.id and professeurs.id = tutorats.professeur";
	$req .= ";";
	//echo $req;
	$restut = mysql_query($req);
	//echo "erreur : ".mysql_error();
	
	$nligne = 1;
	$tut = mysql_fetch_array($restut);
	//echo $tut;
		$credits=credits_tutorat($semestre);
		//echo fait_table_eval();
		$titre = "Tutorat ".$tut["enseignants"];
		$code = "TUTO_".$nligne;
		$n1 =verif($tut['note_1']);
		$a1 = verif($tut['appreciation_1']);
		$profs = $tut['enseignants'];
		$n2 = verif($tut['note_2']);
		$a2 = verif($tut['appreciation_2']);
		//echo "ctrl-n1:".$n1."-n2:".$n2."-credits;".$credits."<br />\n";
		$valide = valide_eval($n1,$n2,$credits);
		//echo "ctrl-credits".$valide['creds']."-<br />\n";
		if($tut) echo fait_liste_eval($titre,$code,$n1,$a1,$n2,$a2, $credits);
		
		$nligne++;
		$total_acquis += $valide['creds'];
		$total_inscrit += $credits;
}

//$total_inscrit+=credits;
$requete= "select evaluations.*, session.module as module, modules.credits, modules.intitule, modules.code, modules.enseignants";
$requete .=" from evaluations, session, modules where evaluations.etudiant = '".$id_etudiant."' ";
$requete .= "and session.semestre='".$semestre_courant."' and evaluations.session=session.id and modules.id = session.module";
$requete .=";";
//echo $requete;
$resEvals = mysql_query($requete);
while($eval = mysql_fetch_array($resEvals)){
	if($eval['code']!="PP_EVL_".$etudiant['niveau']){
		$titre = $eval["intitule"];
		$code = $eval["code"];
		$n1=verif($eval['note_1']);
		$a1 = verif($eval['appreciation_1']);
		$profs = $eval['enseignants'];
		$n2 = verif($eval['note_2']);
		$a2 = verif($eval['appreciation_2']);

		echo fait_liste_eval($titre,$code,$n1,$a1,$n2,$a2, $eval[credits]);
		$nligne++;
		if(strpos("_abcdABCD",$n1) or strpos("_abcdABCD",$n2)){
			$total_acquis += $eval['credits'];
		}
		$total_inscrit += $eval['credits'];
	}else{
		//evaluation semestrielle;
		$note1_eval= verif($eval['note_1']);
		$note2_eval= verif($eval['note_2']);
		$appr1_eval= verif($eval['appreciation_1']);
		$appr2_eval= verif($eval['appreciation_2']);
		$tabl_eval=fait_liste_eval($eval['intitule'],$eval['code'], $note1_eval,$appr1_eval,$note2_eval,$appr2_eval,$eval['credits']);
		if(strpos("_abcdABCD",$note1_eval) or strpos("_abcdABCD",$note2_eval)){
			$total_acquis += $eval['credits'];
		}
		$total_inscrit += $eval['credits'];
	}
}
// *************** Evaluation des stages
//echo "stages ::::::";
$req = "SELECT * FROM stages WHERE etudiant='".$etudiant["id"]."' AND periode='".$semestre_courant."';";
//echo $req;
$res = mysql_query($req);

while($stage=mysql_fetch_array($res)){
$total_inscrit +=$stage['credits'];

if ($stage['valide']!=1){
$comm="en cours";
$n1="E";
}else{
$n1="D";
$total_acquis += $stage['credits'];
}

$deb=$stage['debut'];
$deb = explode("-",$deb);
$chDates = $deb[2]."/".$deb[1]."->";
$fin=$stage['fin'];
$fin = explode("-",$fin);
$chDates .= $fin[2]."/".$fin[1];
echo fait_liste_eval($stage['lieu']." ".$chDates,"STAGE",$n1,$stage['appreciation'],$n1,$stage['appreciation'], $stage['credits']);
$n_lignes -=1;
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
<a href="fait_bulletin.php?id_etudiant=<?php echo $id_etudiant; ?>&nPeriode=<?php echo $semestre_courant; ?>">Editer le bulletin</a>
</p>
</body>
</html>