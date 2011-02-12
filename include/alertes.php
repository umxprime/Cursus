<?php

$req = "SELECT ";
$req .= "evaluations.id as evaluationId, ";
$req .= "evaluations.appreciation_1, ";
$req .= "evaluations.appreciation_2, ";
$req .= "evaluations.valide_1, ";
$req .= "evaluations.valide_2, ";
$req .= "evaluations.note_1, ";
$req .= "evaluations.note_2, ";
$req .= "evaluations.tutorat, ";
$req .= "modules.intitule as intituleModule, ";
$req .= "modules.code as codeModule, ";
$req .= "session.id as idSession, ";
$req .= "etudiants.nom as nomEtudiant, ";
$req .= "etudiants.prenom as prenomEtudiant ";
$req .= "FROM session, evaluations, modules, etudiants WHERE ";
$req .= "modules.enseignants LIKE '%".$_SESSION["username"]."%' AND ";
$req .= "modules.id = session.module AND ";
$req .= "evaluations.session = session.id AND ";
$req .= "etudiants.id = evaluations.etudiant AND ";
$req .= "session.periode = $semestre_courant;";
$listeModules = mysql_query($req);

$req = "SELECT ";
$req .= "tutorats.id as tutoratId, ";
$req .= "evaluations.appreciation_1, ";
$req .= "evaluations.appreciation_2, ";
$req .= "evaluations.valide_1, ";
$req .= "evaluations.valide_2, ";
$req .= "evaluations.note_1, ";
$req .= "evaluations.note_2, ";
$req .= "evaluations.tutorat, ";
$req .= "etudiants.nom as nomEtudiant, ";
$req .= "etudiants.prenom as prenomEtudiant ";
$req .= "FROM evaluations, tutorats, etudiants WHERE ";
$req .= "tutorats.professeur = ".$_SESSION["userid"]." AND ";
$req .= "evaluations.tutorat = tutorats.id AND ";
$req .= "tutorats.etudiant = etudiants.id AND ";
$req .= "tutorats.trash = 0 AND ";
$req .= "tutorats.semestre = $semestre_courant;";
$listeTutorats = mysql_query($req);
$toutesListesEvaluations = array($listeModules,$listeTutorats);

$evaluationsToutesOk=true;
$nAlertes = 0;
foreach ($toutesListesEvaluations as $listeEvaluations)
{
	while($evaluation=mysql_fetch_array($listeEvaluations))
	{
		$unRattrapage = false;
		$noteOk = false;
		$appreciationOk = false;
		if ($evaluation["appreciation_1"]!='') $appreciationOk=true;
		if (verif($evaluation["note_1"])!='-') $noteOk=true;
		if ($noteOk && $evaluation["valide_1"]=='0')
		{
			$unRattrapage = true;
			$noteOk = false;
			$appreciationOk = false;
			if ($evaluation["appreciation_2"]!='') $appreciationOk=true;
			if (verif($evaluation["note_2"])!='-') $noteOk=true;
		}
		if($appreciationOk && $noteOk)continue;
		$evaluationsToutesOk=false;
		$nAlertes++;
	}
}
?>