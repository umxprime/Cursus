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
 * Cursus uses FPDF released by Olivier PLATHEY
 *
 * Cursus uses the Limelight Framework
 * released under the GPL <http://www.gnu.org/licenses/>
 * by Maxime CHAPELET (umxprime@umxprime.com)
 * 
 **/

require "fpdf/pdf.php";
require "include/necessaire.php";

if($_SESSION["auto"]=="e") header("Location: index.php");

$largeurPage = 297;
$hauteurPage = 210;

$nomPeriode = $periode["nom"];
$anneePeriode = $periode["annee"];

$id=$_GET["id"];

$all=$_GET["all"];

$req = "SELECT id,nom,prenom,credits FROM etudiants WHERE id='$id';";
$etudiant = mysql_fetch_array(mysql_query($req));

$req = "SELECT niveaux.niveau, ecoles.nom, niveaux.periode FROM niveaux,cycles,ecoles WHERE ";
if(!$all) $req.= "niveaux.periode='$periode_courante' AND ";
$req.= "niveaux.etudiant='$id' AND niveaux.cycle=cycles.id AND cycles.ecole=ecoles.id ORDER BY niveaux.periode DESC";
$niveauEtudiant = mysql_result(mysql_query($req),0,"niveau");
$nomEcole = mysql_result(mysql_query($req),0,"nom");
$periode_courante = mysql_result(mysql_query($req),0,"periode");
$nomEtudiant = $etudiant["nom"];
$prenomEtudiant = $etudiant["prenom"];
$creditsDeBase = $etudiant["credits"]; 

$req = "SELECT modules.code, modules.intitule, modules.credits, modules.obligatoire, modules.enseignants, ";
$req.= "evaluations.note_1, evaluations.note_2, evaluations.appreciation_1, evaluations.appreciation_2, ";
$req.= "periodes.id as idPeriode, periodes.nom as nomPeriode, periodes.annee as anneePeriode, niveaux.niveau ";
$req.= "FROM evaluations,session,modules,niveaux,periodes WHERE ";
$req.= "evaluations.etudiant='$id' AND session.module=modules.id AND evaluations.session=session.id AND session.periode=niveaux.periode AND session.periode<='$periode_courante' AND niveaux.etudiant=evaluations.etudiant AND periodes.id=niveaux.periode AND niveaux.niveau>0 AND niveaux.niveau<11 ";
$req.= "ORDER BY niveaux.niveau ASC, modules.code ASC;";
$resEvaluations = mysql_query($req);

$req = "SELECT professeurs.nom_complet as enseignants, niveaux.niveau as obligatoire, ";
$req.= "evaluations.note_1, evaluations.note_2, evaluations.appreciation_1, evaluations.appreciation_2, ";
$req.= "periodes.id as idPeriode, periodes.nom as nomPeriode, periodes.annee as anneePeriode, niveaux.niveau ";
$req.= "FROM evaluations,tutorats,periodes,professeurs,niveaux WHERE ";
$req.= "tutorats.semestre=niveaux.periode AND tutorats.etudiant='$id' AND evaluations.tutorat=tutorats.id AND periodes.id=niveaux.periode AND tutorats.professeur=professeurs.id ";
$req.= "AND niveaux.periode<='$periode_courante' AND niveaux.etudiant=tutorats.etudiant AND tutorats.trash=0 AND niveaux.niveau>0 AND niveaux.niveau<11 ";
$req.= "ORDER BY niveaux.niveau ASC, niveaux.periode ASC;";
$resTutorats = mysql_query($req);

$req = "SELECT stages.credits, stages.valide, stages.appreciation, stages.lieu, stages.debut, stages.fin, ";
$req.= "periodes.id as idPeriode, periodes.nom as nomPeriode, periodes.annee as anneePeriode, niveaux.niveau ";
$req.= "FROM stages,periodes,niveaux WHERE ";
$req.= "stages.periode=niveaux.periode AND stages.etudiant='$id' AND periodes.id=niveaux.periode ";
$req.= "AND niveaux.periode<='$periode_courante' AND niveaux.etudiant=stages.etudiant AND niveaux.niveau>0 AND niveaux.niveau<11 ";
$req.= "ORDER BY niveaux.niveau ASC, niveaux.periode ASC;";

$resStages = mysql_query($req);

$pdf=new PDF("L","mm",array($largeurPage,$hauteurPage));
$pdf->SetAutoPageBreak(true);
$pdf->AliasNbPages();
$pdf->SetTopMargin(0);
$pdf->SetLeftMargin(0);
$pdf->AddPage();
$pdf->SetXY(0,0);
$pdf->AddFont('Georgia');
$pdf->SetFont('Georgia','',18);
$pdf->SetFillColor("white");
$pdf->SetTextColor(96,172,191);

if($all) $texte = utf8_decode("Bilan du Cursus");
else $texte = utf8_decode("Bulletin semestriel");
$pdf->Cell($hauteurPage,10,$texte,0,1,"C",true);
$pdf->Cell($hauteurPage,10,"$nomEcole",0,1,"C",true);

$pdf->SetFont('Georgia','',12);
$pdf->Cell($hauteurPage,10,utf8_decode("Édité le ".date("d/m/Y")." sur la période $anneePeriode $nomPeriode"),0,1,"C",true);

$pdf->ln(10);
$pdf->SetFontSize(18);
$texte = utf8_decode("Étudiant(e) : ")."$nomEtudiant $prenomEtudiant";
$pdf->Cell($pdf->GetStringWidth($texte)+10,8,$texte,0,0,"C",true);

$texte = "Niveau actuel : semestre $niveauEtudiant";
$pdf->SetX($hauteurPage-$pdf->GetStringWidth($texte)-10);
$pdf->Cell($pdf->GetStringWidth($texte)+10,8,$texte,0,1,"C",true);

$texte = utf8_decode("ECTS initiaux : ").$etudiant["credits"];
$pdf->Cell($pdf->GetStringWidth($texte)+10,8,$texte,0,1,"C",true);
$pdf->ln(10);

$evaluations = array();

while($eval=mysql_fetch_assoc($resEvaluations))
{
	array_push($evaluations,$eval);
}
while($eval=mysql_fetch_assoc($resTutorats))
{
	$eval["code"]="TUTO_1";
	$eval["intitule"]="Tutorat";
	$eval["credits"]=Tutorat::calculerCredits($eval["niveau"]);
	array_push($evaluations,$eval);
}
while($eval=mysql_fetch_assoc($resStages))
{
	$eval["code"]=Evaluation::CODE_STAGE;
	$eval["enseignants"]="";
	$eval["obligatoire"]="";
	$eval["note_1"]=$eval["valide"];
	$eval["note_2"]="";
	$eval["appreciation_1"]=$eval["appreciation"];
	$eval["appreciation_2"]="";
	$eval["intitule"]="Stage ".$eval["lieu"];
	array_push($evaluations,$eval);
}

$evaluations = array_orderby($evaluations,'idPeriode',SORT_ASC,'code',SORT_STRING);

//var_dump($evaluations);exit();

$periodes = array();
$periodeActuelle=-1;
$nPeriode=-1;
for($i=0;$i<count($evaluations);$i++)
{
	if($periodeActuelle!=$evaluations[$i]["idPeriode"])
	{
		$periodeActuelle=$evaluations[$i]["idPeriode"];
		$periodes[] = array();
		$nPeriode++;
	}
	$periodes[$nPeriode][] = $evaluations[$i]; 
}


for($i=0;$i<count($periodes);$i++)
{
	$evaluations = $periodes[$i];
	for($j=0;$j<count($evaluations);$j++)
	{
		if(strpos("_".$evaluations[$j]["code"],"TUTO_"))
		{
			array_push($evaluations,$evaluations[$j]);
			array_splice($evaluations,$j,1);
			break;
		}
	}
	for($j=0;$j<count($evaluations);$j++)
	{
		if(strpos("_".$evaluations[$j]["code"],"PP_EVL_"))
		{
			array_push($evaluations,$evaluations[$j]);
			array_splice($evaluations,$j,1);
			break;
		}
	}
	$periodes[$i] = $evaluations;
}

$acquisCursus=$etudiant["credits"];
for ($i=0;$i<count($periodes);$i++)
{
	$acquisPeriode = 0;
	$evaluations = $periodes[$i];
	$bandeauPeriode=true;
	
	for ($j=0;$j<count($evaluations);$j++)
	{	
		$evaluation = $evaluations[$j];
		
		$intituleModule = $evaluation["intitule"];
		$codeModule = $evaluation["code"];
		$creditsModule = $evaluation["credits"];
		$note1 = $evaluation["note_1"];
		$note2 = $evaluation["note_2"];
		$appreciation1 = $evaluation["appreciation_1"];
		$appreciation2 = $evaluation["appreciation_2"];
		
		$objetEvaluation = new Evaluation($note1,$note2,$appreciation1,$appreciation2,$creditsModule,$codeModule);
		$couleursPDF = $objetEvaluation->couleursPDF();
		if(!$objetEvaluation->aUneNoteSuffisante() && $evaluation["niveau"]!=$niveauEtudiant) continue;
		
		$creditsAcquis = $objetEvaluation->estCrediteeDe();
		$enseignantsModule = $evaluation["enseignants"];
		$acquisPeriode+= $creditsAcquis;
		$acquisPeriode = min(30,$acquisPeriode);
		$acquisCursus += $creditsAcquis;
		$acquisCursus = min($evaluation["niveau"]*30,$acquisCursus);
		
		if(intval($evaluation["niveau"])<intval($niveauEtudiant) && !$all) continue;
		
		$pdf->SetX(0);
		
		if($bandeauPeriode)
		{
			$bandeauPeriode=false;
			$pdf->SetFontSize(18);
			$pdf->SetFillColor("#60ACBF");
			$pdf->SetTextColor("white");
			$texte = utf8_decode("ECTS acquis en ".$evaluation["anneePeriode"]." ".$evaluation["nomPeriode"]);
			$pdf->Cell($pdf->GetStringWidth($texte)+10,10,$texte,0,0,"C",true);
			$pdf->SetFontSize(12);
			$texte = "Semestre ".$evaluation["niveau"];
			$pdf->Cell($pdf->GetStringWidth($texte)+10,5,$texte,0,1,"C",true);
			$pdf->SetXY(0,$pdf->GetY()+10);
		}
		
		$titreEval = "$codeModule : $intituleModule";
		$pdf->SetFontSize(10);
		$tailleBloc = 15+max(10,$pdf->WordWrap($appreciation1,$hauteurPage-70)*5);
		if($objetEvaluation->estUneNoteInsuffisante($note1)) $tailleBloc=15+max(10,$pdf->WordWrap($appreciation1,$hauteurPage-70)*5)+max(15,5+$pdf->WordWrap($appreciation2,$hauteurPage-70)*5);
		if($pdf->GetY()+$tailleBloc+5>$largeurPage)$pdf->SetXY($pdf->GetX(),$pdf->GetY()+$tailleBloc);
		
		// TITRE
		$pdf->SetFillColor($couleursPDF["couleurTitreFond"]);
		$pdf->SetTextColor($couleursPDF["couleurTitreTexte"]);
		$pdf->SetFontSize(12);
		$texte = $titreEval;
		$pdf->Cell($pdf->GetStringWidth($texte)+10,10,$texte,0,0,"C",true);
		
		// ENSEIGNANTS
		$pdf->Cell($pdf->GetStringWidth($enseignantsModule)+10,10,$enseignantsModule,0,1,"C",true);
		$debut = $pdf->GetY()-10;
		
		// ECTS
		$pdf->SetFillColor($couleursPDF["couleurECTSFond"]);
		$pdf->SetTextColor($couleursPDF["couleurECTSTexte"]);
		$pdf->SetFontSize(20);
		$pdf->Cell(20,20,"$creditsAcquis/$creditsModule",0,1,"C",true);
		
		// NOTE 1
		$pdf->SetXY(30,$pdf->GetY()-15);
		$pdf->SetX(35);
		$debutNote1 = $pdf->GetY();
		$pdf->SetFillColor($couleursPDF["couleurNote1Fond"]);
		$pdf->SetTextColor($couleursPDF["couleurNote1Texte"]);
		$pdf->SetDrawColor($couleursPDF["couleurNote1Texte"]);
		$pdf->SetFontSize(14);
		$pdf->Cell(10,10,$objetEvaluation->conformerNote($note1),1,0,"C",true);
		
		// APPRECIATION 1
		$pdf->SetX($pdf->GetX()+5);
		$tailleChamps = $hauteurPage-$pdf->GetX()-10;
		$pdf->SetTextColor("#303030");
		$pdf->SetFontSize(10);
		$pdf->MultiCell($tailleChamps,5,$appreciation1,0,"L",false);
		
		if($objetEvaluation->estUneNoteInsuffisante($note1))
		{
			$pdf->Ln(5);
			
			// NOTE 2
			if($pdf->GetY()-30<$debut)$pdf->SetY($debut+30);
			$pdf->SetX(35);
			$debutNote2 = $pdf->GetY();
			$pdf->SetFillColor($couleursPDF["couleurNote2Fond"]);
			$pdf->SetTextColor($couleursPDF["couleurNote2Texte"]);
			$pdf->SetDrawColor($couleursPDF["couleurNote2Texte"]);
			$pdf->SetFontSize(14);
			$pdf->Cell(10,10,$objetEvaluation->conformerNote($note2),1,0,"C",true);
			
			// APPRECIATION 2
			$pdf->SetX($pdf->GetX()+5);
			$tailleChamps = $hauteurPage-$pdf->GetX()-10;
			$pdf->SetTextColor("#303030");
			$pdf->SetFontSize(10);
			$pdf->MultiCell($tailleChamps,5,$appreciation2,0,"L",false);
		}
		
		$fin = $pdf->GetY();
		if($fin-$debutNote1<15 && !$objetEvaluation->estUneNoteInsuffisante($note1)) $pdf->SetY($pdf->GetY()+(15-($fin-$debutNote1)));
		else if($fin-$debutNote2<15 && $objetEvaluation->estUneNoteInsuffisante($note1)) $pdf->SetY($pdf->GetY()+(15-($fin-$debutNote2)));
		else $pdf->SetY($pdf->GetY()+5);
		$pdf->SetY($debut+$tailleBloc+5);
	}
	
	// ECTS Periode & Cumul
	if(!(intval($evaluation["niveau"])<intval($niveauEtudiant) && !$all))
	{
		$pdf->SetX(0);
		$pdf->SetFillColor("#60ACBF");
		$pdf->SetTextColor("white");
		
		$pdf->SetFontSize(12);
		$texteA = "ECTS acquis en ".$evaluation["anneePeriode"]." ".$evaluation["nomPeriode"]." :";
		$texteATaille = $pdf->GetStringWidth($texteA)+5;
		
		$pdf->SetFontSize(20);
		$texteB = $acquisPeriode."/30";
		$texteBTaille = $pdf->GetStringWidth($texteB)+5;
		
		$pdf->SetFontSize(12);
		$texteC = utf8_decode("ECTS cumulés au semestre ".$evaluation["niveau"]." :");
		$texteCTaille = $pdf->GetStringWidth($texteC)+5;
		
		$pdf->SetFontSize(20);
		$texteD = $acquisCursus."/".($evaluation["niveau"]*30);
		$texteDTaille = $pdf->GetStringWidth($texteD)+5;
		
		$pdf->SetFontSize(12);
		$pdf->SetX($hauteurPage-$texteDTaille-$texteCTaille-$texteBTaille-$texteATaille);
		$pdf->Cell($pdf->GetStringWidth($texteA)+5,10,$texteA,0,0,"C",true);
		
		$pdf->SetFontSize(20);
		$pdf->SetX($hauteurPage-$texteDTaille-$texteCTaille-$texteBTaille);
		$pdf->Cell($pdf->GetStringWidth($texteB)+5,15,$texteB,0,0,"C",true);
		
		$pdf->SetFontSize(12);
		$pdf->SetX($hauteurPage-$texteDTaille-$texteCTaille);
		$pdf->Cell($pdf->GetStringWidth($texteC)+5,10,$texteC,0,0,"C",true);
		
		$pdf->SetFontSize(20);
		$pdf->SetX($hauteurPage-$texteDTaille);
		$pdf->Cell($pdf->GetStringWidth($texteD)+5,15,$texteD,0,1,"C",true);
		
		if($i+1<count($periodes))$pdf->AddPage();
	}
}

$pdf->SetFontSize(12);
$pdf->Ln(15);
$pdf->SetX(5);
$texte = utf8_decode("Observations et remarques");
$pdf->Cell($pdf->GetStringWidth($texte)+5,10,$texte,0,1,"C",true);
$pdf->Ln(20);
$pdf->SetX(5);
$texte = "Signature du coordonateur semestriel";
$pdf->Cell($pdf->GetStringWidth($texte)+5,10,$texte,0,0,"C",true);
$texte = utf8_decode("Signature du directeur de l'établissement");
$pdf->SetX($hauteurPage-($pdf->GetStringWidth($texte)+10));
$pdf->Cell($pdf->GetStringWidth($texte)+5,10,$texte,0,0,"C",true);

if($all) $nomFichierPDF ="BILAN-";
else $nomFichierPDF ="BULLETIN-";
$nomFichierPDF.= utf8_encode($nomEtudiant).".".utf8_encode($prenomEtudiant)."-$anneePeriode"."-".$nomPeriode.".pdf";
$pdf->Output($nomFichierPDF,"D");

?>