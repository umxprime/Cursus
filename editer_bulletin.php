<?php
require "fpdf/pdf.php";
require "include/necessaire.php";

$largeurPage = 297;
$hauteurPage = 210;

$nomPeriode = $periode["nom"];
$anneePeriode = $periode["annee"];

$id=$_GET["id"];

$all=$_GET["all"];

$req = "SELECT id,nom,prenom,credits FROM etudiants WHERE id='$id';";
$etudiant = mysql_fetch_array(mysql_query($req));

$req = "SELECT niveau FROM niveaux WHERE niveaux.periode='$periode_courante' AND niveaux.etudiant='$id'";
$niveauEtudiant = mysql_result(mysql_query($req),0,"niveau");
$nomEtudiant = $etudiant["nom"];
$prenomEtudiant = $etudiant["prenom"];
$creditsDeBase = $etudiant["credits"]; 

$req = "SELECT modules.code, modules.intitule, modules.credits, modules.obligatoire, modules.enseignants, ";
$req.= "evaluations.note_1, evaluations.note_2, evaluations.appreciation_1, evaluations.appreciation_2, ";
$req.= "periodes.id as idPeriode, periodes.nom as nomPeriode, periodes.annee as anneePeriode, niveaux.niveau ";
$req.= "FROM evaluations,session,modules,niveaux,periodes WHERE ";
$req.= "evaluations.etudiant='$id' AND session.module=modules.id AND evaluations.session=session.id AND session.periode=niveaux.periode AND session.periode<='$periode_courante' AND niveaux.etudiant=evaluations.etudiant AND periodes.id=niveaux.periode AND niveaux.niveau>0 AND niveaux.niveau<11 ";
$req.= "ORDER BY niveaux.niveau ASC, modules.code ASC;";
//echo $req;exit();
$resEvaluations = mysql_query($req);

$req = "SELECT professeurs.nom_complet as enseignants, niveaux.niveau as obligatoire, ";
$req.= "evaluations.note_1, evaluations.note_2, evaluations.appreciation_1, evaluations.appreciation_2, ";
$req.= "periodes.id as idPeriode, periodes.nom as nomPeriode, periodes.annee as anneePeriode, niveaux.niveau ";
$req.= "FROM evaluations,tutorats,periodes,professeurs,niveaux WHERE ";
$req.= "tutorats.semestre=niveaux.periode AND tutorats.etudiant='$id' AND evaluations.tutorat=tutorats.id AND periodes.id=niveaux.periode AND tutorats.professeur=professeurs.id ";
$req.= "AND niveaux.periode<='$periode_courante' AND niveaux.etudiant=tutorats.etudiant AND tutorats.trash=0 AND niveaux.niveau>0 AND niveaux.niveau<11 ";
$req.= "ORDER BY niveaux.niveau ASC, niveaux.periode ASC;";
//echo $req;exit();
$resTutorat = mysql_query($req);

$pdf=new PDF("L","mm",array($largeurPage,$hauteurPage));
$pdf->SetAutoPageBreak(true);
$pdf->SetTopMargin(0);
$pdf->AddPage();
$pdf->SetXY(0,0);
$pdf->AddFont('Georgia');
$pdf->SetFont('Georgia','',18);
$pdf->SetFillColor("white");
$pdf->SetTextColor(96,172,191);
if($all) $pdf->Cell($hauteurPage,10,"Bilan du Cursus",0,1,"C",true);
else $pdf->Cell($hauteurPage,10,"Bulletin semestriel $anneePeriode $nomPeriode",0,1,"C",true);
$pdf->SetXY(0,10);
$pdf->SetFontSize(12);
$texte = "$nomEtudiant $prenomEtudiant";
$pdf->Cell($pdf->GetStringWidth($texte)+10,8,$texte,0,0,"C",true);
$texte = "Semestre $niveauEtudiant";
$pdf->SetX($hauteurPage-$pdf->GetStringWidth($texte)-10);
$pdf->Cell($pdf->GetStringWidth($texte)+10,8,$texte,0,1,"C",true);
$nLigne=0;
$acquisCursus=$etudiant["credits"];
$acquisSemestre = 0; 
$periodeActuelle=-1;
$niveauActuel=-1;
$evaluations = array();

while($eval=mysql_fetch_assoc($resEvaluations))
{
	array_push($evaluations,$eval);
}
while($eval=mysql_fetch_assoc($resTutorat))
{
	$eval["code"]="TUTO_1";
	$eval["intitule"]="Tutorat";
	$eval["credits"]=credits_tutorat($eval["niveau"]);
	array_push($evaluations,$eval);
}

$evaluations = array_orderby($evaluations,'niveau',SORT_ASC,'idPeriode',SORT_ASC,'code',SORT_STRING);

//var_dump($evaluations);exit();

for($i=0;$i<count($evaluations);$i++)
{	
	if($evaluations[$i]["idPeriode"]==$periode["id"] && strpos("_".$evaluations[$i]["code"],"PP_EVL_"))
	{
		array_push($evaluations,$evaluations[$i]);
		array_splice($evaluations,$i,1);
		break;
	}
}
//exit();
for ($i=0;$i<count($evaluations);$i++)
{	
	$evaluation = $evaluations[$i];
	$intituleModule = $evaluation["intitule"];
	$codeModule = $evaluation["code"];
	$creditsModule = $evaluation["credits"];
	$note1 = $evaluation["note_1"];
	$note2 = $evaluation["note_2"];
	$appreciation_1 = $evaluation["appreciation_1"];
	$appreciation_2 = $evaluation["appreciation_2"];
	$validationModule = validerEvaluationPDF($note1,$note2,$appreciation_1,$appreciation_2,$creditsModule);
	if(!$validationModule["acquis"] && $evaluation["idPeriode"]!=$periode_courante) continue;
	$creditsAcquis = $validationModule["credits"];
	$enseignantsModule = $evaluation["enseignants"];
	if(intval($evaluation["niveau"])<intval($niveauEtudiant) && !$all)
	{
		if($niveauActuel!=$evaluation["niveau"])
		{
			$niveauActuel = $evaluation["niveau"];
			$acquisSemestre = 0;
		}
		$acquisSemestre+= $creditsAcquis;
		$acquisSemestre = min(30,$acquisSemestre);
		$acquisCursus += $creditsAcquis;
		$acquisCursus = min($evaluation["niveau"]*30,$acquisCursus);
		continue;
	}
	$pdf->SetX(0);
	if($periodeActuelle!=$evaluation["idPeriode"])
	{
		if($periodeActuelle!=-1)
		{
			$pdf->SetFontSize(12);
			$pdf->SetFillColor("#60ACBF");
			$pdf->SetTextColor("white");
			$texte = "ECTS Acquis en semestre ".(($all==1)?($evaluation["niveau"]-1):($evaluation["niveau"]))." :";
			$pdf->Cell($pdf->GetStringWidth($texte)+10,10,$texte,0,0,"C",true);
			$pdf->SetFontSize(20);
			$texte = $acquisSemestre."/30";
			$pdf->Cell($pdf->GetStringWidth($texte)+10,15,$texte,0,0,"C",true);
			$pdf->SetX($pdf->GetX()+5);
			$texte = "ECTS Acquis :";
			$pdf->Cell($pdf->GetStringWidth($texte)+10,10,$texte,0,0,"C",true);
			$pdf->SetFontSize(20);
			$texte = $acquisCursus."/".((($all==1)?($evaluation["niveau"]-1):($evaluation["niveau"]))*30);
			$pdf->Cell($pdf->GetStringWidth($texte)+10,15,$texte,0,1,"C",true);
			$pdf->AddPage();
		}
		$pdf->SetFontSize(18);
		$periodeActuelle=$evaluation["idPeriode"];
		$pdf->SetFillColor("#60ACBF");
		$pdf->SetTextColor("white");
		$pdf->SetXY(0,$pdf->GetY()+5);
		$texte = utf8_decode("ECTS acquis en ".$evaluation["anneePeriode"]." ".$evaluation["nomPeriode"]." pour le semestre ".$evaluation["niveau"]);
		$pdf->Cell($pdf->GetStringWidth($texte)+10,10,$texte,0,1,"C",true);
		$pdf->SetXY(0,$pdf->GetY()+5);
	}
	if($niveauActuel!=$evaluation["niveau"])
	{
		$niveauActuel = $evaluation["niveau"];
		$acquisSemestre = 0;
	}
	$acquisSemestre+= $creditsAcquis;
	$acquisSemestre = min(30,$acquisSemestre);
	$acquisCursus += $creditsAcquis;
	$acquisCursus = min($evaluation["niveau"]*30,$acquisCursus);
	
	$titreEval = "$codeModule : $intituleModule";
	$pdf->SetFontSize(10);
	$tailleBloc = 15+max(10,$pdf->WordWrap($appreciation_1,$hauteurPage-70)*5);
	if(strpos("_EFef",verif($note1))) $tailleBloc=15+max(10,$pdf->WordWrap($appreciation_1,$hauteurPage-70)*5)+max(15,5+$pdf->WordWrap($appreciation_2,$hauteurPage-70)*5);
	if($pdf->GetY()+$tailleBloc+5>$largeurPage)$pdf->SetXY($pdf->GetX(),$pdf->GetY()+$tailleBloc);
	//echo $titreEval." ".$pdf->GetY()." ".$hauteurPage."<br/>";
	// TITRE
	$pdf->SetFillColor($validationModule["couleurTitreFond"]);
	$pdf->SetTextColor($validationModule["couleurTitreTexte"]);
	$pdf->SetFontSize(12);
	$texte = $titreEval." : ".$tailleBloc;
	$pdf->Cell($pdf->GetStringWidth($texte)+10,10,$texte,0,0,"C",true);
	// ENSEIGNANTS
	$pdf->Cell($pdf->GetStringWidth($enseignantsModule)+10,10,$enseignantsModule,0,1,"C",true);
	$debut = $pdf->GetY()-10;
	// ECTS
	$pdf->SetFillColor($validationModule["couleurECTSFond"]);
	$pdf->SetTextColor($validationModule["couleurECTSTexte"]);
	$pdf->SetFontSize(20);
	$pdf->Cell(20,20,"$creditsAcquis/$creditsModule",0,1,"C",true);
	// PREMIERE SESSION
	$pdf->SetXY(30,$pdf->GetY()-15);
	/*
	$pdf->SetFillColor("white");
	$pdf->SetTextColor("#303030");
	$pdf->SetFontSize(10);
	$pdf->Cell($pdf->GetStringWidth(utf8_decode("Première session"))+10,10,utf8_decode("Première session"),0,0,"C",true);
	*/
	// NOTE 1
	$pdf->SetX(35);
	$debutNote1 = $pdf->GetY();
	$pdf->SetFillColor($validationModule["couleurNote1Fond"]);
	$pdf->SetTextColor($validationModule["couleurNote1Texte"]);
	$pdf->SetDrawColor($validationModule["couleurNote1Texte"]);
	$pdf->SetFontSize(14);
	$pdf->Cell(10,10,verif($note1),1,0,"C",true);
	// APPRECIATION 1
	$pdf->SetX($pdf->GetX()+5);
	$tailleChamps = $hauteurPage-$pdf->GetX()-10;
	$pdf->SetTextColor("#303030");
	$pdf->SetFontSize(10);
	$pdf->MultiCell($tailleChamps,5,$appreciation_1,0,"L",false);
	if(strpos("_EFef",verif($note1)))
	{
		$pdf->Ln(5);
		// RATTRAPAGES
		$pdf->SetX(30);
		if($pdf->GetY()-30<$debut)$pdf->SetXY(30,$debut+30);
		/*
		$pdf->SetFillColor("white");
		$pdf->SetTextColor("#303030");
		$pdf->Cell($pdf->GetStringWidth(utf8_decode("Rattrapages"))+10,10,utf8_decode("Rattrapages"),0,0,"C",true);
		*/
		// NOTE 2
		$pdf->SetX(35);
		$debutNote2 = $pdf->GetY();
		$pdf->SetFillColor($validationModule["couleurNote2Fond"]);
		$pdf->SetTextColor($validationModule["couleurNote2Texte"]);
		$pdf->SetDrawColor($validationModule["couleurNote2Texte"]);
		$pdf->SetFontSize(14);
		$pdf->Cell(10,10,verif($note2),1,0,"C",true);
		// APPRECIATION 2
		$pdf->SetX($pdf->GetX()+5);
		$tailleChamps = $hauteurPage-$pdf->GetX()-10;
		$pdf->SetTextColor("#303030");
		$pdf->SetFontSize(10);
		$pdf->MultiCell($tailleChamps,5,$appreciation_2,0,"L",false);
	}
	$fin = $pdf->GetY();
	//if($fin<$debut)$debut-=$largeurPage;
	if($fin-$debutNote1<15 && !strpos("_EFef",verif($note1))) $pdf->SetY($pdf->GetY()+(15-($fin-$debutNote1)));
	else if($fin-$debutNote2<15 && strpos("_EFef",verif($note1))) $pdf->SetY($pdf->GetY()+(15-($fin-$debutNote2)));
	else $pdf->SetY($pdf->GetY()+5);
	$pdf->SetY($debut+$tailleBloc+5);
}
$pdf->SetX(0);
$pdf->SetFontSize(12);
$pdf->SetFillColor("#60ACBF");
$pdf->SetTextColor("white");
$texte = "ECTS Acquis en semestre ".($evaluation["niveau"])." :";
$pdf->Cell($pdf->GetStringWidth($texte)+10,10,$texte,0,0,"C",true);
$pdf->SetFontSize(20);
$texte = $acquisSemestre."/30";
$pdf->Cell($pdf->GetStringWidth($texte)+10,15,$texte,0,0,"C",true);
$pdf->SetX($pdf->GetX()+5);
$texte = "ECTS Acquis :";
$pdf->Cell($pdf->GetStringWidth($texte)+10,10,$texte,0,0,"C",true);
$pdf->SetFontSize(20);
$texte = $acquisCursus."/".(($evaluation["niveau"])*30);
$pdf->Cell($pdf->GetStringWidth($texte)+10,15,$texte,0,1,"C",true);

$pdf->Output("bulletin.pdf","I");

?>