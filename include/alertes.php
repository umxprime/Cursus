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