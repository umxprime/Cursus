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

require "include/necessaire.php";

if($_SESSION['auto']=="e") header("Location:etudiants.php?nPeriode=$semestre_courant");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
	<head>
		<?php
		include("inc_css_thing.php");
		?>
		<title>
			Cursus <?php revision();?> / Coordination des étudiants en semestre <?php echo $ns;?> pour la période : <?php echo $periode["nom"]; ?>
		</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" href="etu_sem.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="etu_sem_print.css" type="text/css" media="print" />
		<link rel="stylesheet" href="etu_style.css" type="text/css" media="screen" />
		<?php
		$_LIMELIGHT_PATH = "com/umxprime/limelight/";
		include_once($_LIMELIGHT_PATH."core/limelight.php");
		?>
	</head>
	<body>
		<div id="global">
			<?php
			$outil="alertes";
			include("barre_outils.php");
			include("inc_nav_sem.php");
			?>
			<input type="hidden" id="semestre_courant" value="<?php echo $semestre_courant;?>"/>
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
			$req .= "evaluations.id as evaluationId, ";
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
			$req .= "tutorats.trash != 1 AND ";
			$req .= "tutorats.semestre = $semestre_courant;";
			//echo $req;
			$listeTutorats = mysql_query($req);
			$toutesListesEvaluations = array($listeModules,$listeTutorats);
			?>
			<div id="content">
				
				<table id="alertes" class="center full">
				<tr><td class="center"><h2>Alertes</h2></td></tr>
				<?php
				$evaluationsToutesOk=true;
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
						?>
						<tr class="line"><td>
							Vous devez saisir
							<a href="<?php
							if(!$evaluation["tutoratId"])
							{
								echo "edit_eval.php?eval=".$evaluation["evaluationId"]."&nPeriode=$semestre_courant";
							} else {
								echo "edit_tutorats.php?eval=".$evaluation["evaluationId"]."&nPeriode=$semestre_courant";
							}
							?>" class="lienBouton">
							<?php
							if(!$appreciationOk && !$noteOk)
							{
								echo "la note et l'appréciation";
							} else if(!$appreciationOk)
							{
								echo "l'appréciation";
							} else {
								echo "la note";
							}
							?>
							<?php if($unRattrapage) echo "pour le rattrapage";?>
							de
							<?php echo utf8_encode($evaluation["prenomEtudiant"])." ".utf8_encode($evaluation["nomEtudiant"]);?>
							</a>
							<?php
							if(!$evaluation["tutoratId"])
							{ 
							?>
							pour le module
							<a href="gestion_modules.php?session=<?php echo $evaluation["idSession"];?>&nPeriode=<?php echo $semestre_courant;?>" class="lienBouton">
							<?php echo "[".$evaluation["codeModule"]."] ".utf8_encode($evaluation["intituleModule"]);?>
							</a>
							<?php
							} else { 
							?>
							en <a href="tutorats.php?nPeriode=<?php echo $semestre_courant;?>" class="lienBouton">tutorat</a>
							<?php
							} 
							?>
						</td></tr>
						<?php
					}
				}
				if($evaluationsToutesOk)
				{
					?>
					<tr class="line"><td>
						Aucune évaluation à saisir pour cette période.
					</td></tr>
					<?php
				}
				?>
				</table>
				<div id="footer"></div>
			</div>
		</div>
	</body>
</html>

