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

$ns = 1;
if(isset($_GET['ns'])) $ns = $_GET['ns'];
$niveau = $ns;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
	<head>
		<?php include("inc_css_thing.php");?>
		<title>Cursus <?php revision();?> / Coordination des étudiants en semestre <?php echo $ns;?> pour la période : <?php echo $periode["nom"]; ?></title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
		<link rel="stylesheet" href="etu_sem.css" type="text/css" media="screen"/>
		<link rel="stylesheet" href="etu_sem_print.css" type="text/css" media="print"/>
	</head>
	<body>
		<div id="global">
			<?php
			$outil="coordination";
			include("barre_outils.php");
			include("inc_nav_sem.php");
			
			if($droits[$_SESSION['auto']]['edit_coordination'])
			{
				if($droits[$_SESSION['auto']]['edit_coordination_s13'])
				{
					$coords=array(-1,1,2,3,4,5,6,7,8,9,10,13);
				}else{
					$coords=array(-1,1,2,3,4,5,6,7,8,9,10);
				}
				echo "<div id=\"choixSemestre\">";
				echo "<ul>";
			
				//le semestre d'�tude actuellement s�lectionn� est trait� diff�remment � l'affichage
				//et sont onglet est inactif (pas de lien)
				foreach($coords as $niv)
				{
					$semestre = "s".$niv;
					if($niv==-1)$semestre="Tous";
					if($niv==33)$semestre="Auditeurs Libres";
					if($niv==$ns)
					{
						echo "<li id=\"courant\"><a href=\"#\">".$semestre."</a></li>";
					}else
					{
						echo "<li><a href=\"vue_coordination.php?ns=".$niv."&nPeriode=".$periode['id']."\">".$semestre."</a></li>";
					}
				}
				echo "<ul>";
				echo "</div>";
			}
			?>
			<div id="content">
				<?php
				//récupérer les étudiants inscit dans le semestre d'étude durant la période choisie
				$req_etu = "SELECT etudiants.id, etudiants.nom, etudiants.prenom, niveaux.niveau as sem_etu ";
				$req_etu .= " FROM etudiants, niveaux, cycles WHERE ";
				if($ns>0)$req_etu .= "niveaux.niveau ='".$ns."' AND niveaux.periode='".$semestre_courant."'";
				else $req_etu .= "niveaux.niveau>0 AND niveaux.niveau<11 AND niveaux.periode='".$semestre_courant."'";
				$req_etu .= "AND etudiants.id= niveaux.etudiant AND niveaux.cycle=cycles.id AND cycles.ecole='".$_SESSION["ecole"]."' ORDER BY etudiants.nom;";
				$res_etu = mysql_query($req_etu);
				//pour chaque etudiant afficher un mini bulletin avec ses modules, son tutorat et son �valuation
				while($etudiant=mysql_fetch_array($res_etu))
				{
					echo "<ul class=\"bulletin\">\n";
					echo "<li class=\"nomBulletin\"><a href='vue_cursus.php?id=".$etudiant['id']."&nPeriode=".$semestre_courant."'>".$etudiant["nom"]." ";
					echo utf8_encode($etudiant["prenom"]);
					if($ns==-1) echo " (s".$etudiant["sem_etu"].")";
					echo "</a></li>\n";
					//mise � 0 des cr�dits pr�vus et des cr�dits acquis
					$total_inscrit = 0;
					$total_acquis = 0;
					//gestion de l'affichage (15 lignes pour tous les modules)
					$n_lignes=15;
					//******** Evaluations des modules de cours
					//les modules (d�finitions p�dagogiques et fonctionnelles) son instanci�s
					//c'est � dire qu'un m�me module p�dagogique peut avoir lieu surant diff�rents semestres temporels
					//l'association module<->semestre temporel est une session
					//les �tudiants sont donc inscrits � des sessions de modules p�dagogiques
					$req_evals = "SELECT evaluations.note_1,evaluations.note_2,evaluations.appreciation_1, evaluations.appreciation_2,evaluations.session, evaluations.id as eval_id, ";
					$req_evals .= "session.periode, session.module, ";
					$req_evals .= "modules.id as module_id, modules.code, modules.intitule, modules.credits, modules.enseignants";
					$req_evals .= " FROM evaluations, session, modules WHERE ";
					$req_evals .= " evaluations.etudiant = '".$etudiant["id"]."' AND session.id=evaluations.session";
					$req_evals .= " AND session.periode = '".$semestre_courant."' AND modules.id=session.module ORDER BY modules.code ASC;";
				
					//evaluations des étudiants pour les sessions auxquelles ils sont inscrits
					$res_evals = mysql_query($req_evals);
					$sem_eval="";
					while($eval=mysql_fetch_array($res_evals))
					{
						//incr�mentation du nombre de cr�dits pr�vus
						$total_inscrit +=$eval['credits'];
						if(strstr($eval['code'],"PP_EVL_"))
						{
							//traitement de l'�valuation semestrielle
							// elle sera affich�e en bas de liste donc stockage dans une variable
							$valide = valide_eval($eval["note_1"],$eval["note_2"],$eval['credits']);
							$classe = $valide['classe'];
							$total_acquis += $valide['creds'];
							//echo $n_lignes;
							$sem_eval .= "<li class=\"".$classe."\">";
							$sem_eval .= "<a href=\"edition_session.php?session=".$eval["session"]."\" title=\"".utf8_encode($eval['intitule'])."\">";
							$sem_eval .= $eval["code"]." : </a>";
							$sem_eval .= "<a href=\"edit_eval.php?eval=".$eval["eval_id"]."\" title=\"".utf8_encode($eval['appreciation_1'])."\">";
							$sem_eval .= $eval['note_1']."</a>";
							$sem_eval .= "/";
							$sem_eval .= "<a href=\"edit_eval.php?eval=".$eval["eval_id"]."\" title=\"".utf8_encode($eval['appreciation_2'])."\">";
							$sem_eval .= $eval['note_2']."</a>";
							$sem_eval .= " | ".$eval['credits']." Cr.";
							$sem_eval .= "</li>\n";
						}else{
							//traitement des modules autres que l'�valuation
							$valide = valide_eval($eval["note_1"],$eval["note_2"],$eval['credits']);
							$classe = $valide['classe'];
							$total_acquis += $valide['creds'];
							//echo $n_lignes;
							echo "<li class=\"".$classe."\">";
							echo "<a href=\"edition_session.php?session=".$eval["session"]."\" title=\"".utf8_encode($eval['intitule'])." (".utf8_encode($eval["enseignants"]).")\">";
							echo $eval["code"]." : </a>";
							echo "<a href=\"edit_eval.php?eval=".$eval["eval_id"]."\" title=\"".utf8_encode($eval['appreciation_1'])."\">";
							echo $eval['note_1']."</a>";
							echo "/";
							echo "<a href=\"edit_eval.php?eval=".$eval["eval_id"]."\" title=\"".utf8_encode($eval['appreciation_2'])."\">";
							echo $eval['note_2']."</a>";
							echo " | ".$eval['credits']." Cr.";
							echo "</li>\n";
							$n_lignes -=1;
						}
					}
					// *************** Evaluation des stages
					//echo "stages ::::::";
					$req = "SELECT * FROM stages WHERE etudiant='".$etudiant["id"]."' AND periode='".$semestre_courant."';";
					//echo $req;
					$res = mysql_query($req);
					while($stage=mysql_fetch_array($res))
					{
						$total_inscrit +=$stage['credits'];
						if ($stage['valide']!=1)
						{
							$classe = "noneval";
							$comm="en cours";
						}else{
							$classe = "ok";
							$total_acquis += $stage['credits'];
							$comm="Valid&eacute;";
						}
						echo "<li class=\"".$classe."\">";
						//echo $n_lignes;
						echo "<a href=\"gestion_stages.php?stage_id=".$stage['id']."\" title=\"".$stage['lieu']."\">";
						$deb=$stage['debut'];
						$deb = explode("-",$deb);
						$chDates = $deb[2]."/".$deb[1]."->";
						$fin=$stage['fin'];
						$fin = explode("-",$fin);
						$chDates .= $fin[2]."/".$fin[1];
						echo "STAGE : "/*.$chDates*/." </a>";
						echo "<a href=\"gestion_stages.php?stage_id=".$stage['id']."\" title=\"".$stage['appreciation']."\">";
				
						echo $comm."</a> | ".$stage['credits']." Cr.";
						echo "</li>\n";
						$n_lignes -=1;
					}
				
					//**************** Tampon d'�quilibrage de l'affichage
					//passer les lignes inoccup�es pour affichage coh�rent
					while($n_lignes>2)
					{
						echo "<li>&nbsp;</li>";
						$n_lignes -=1;
					}
				
					// ************** Evaluation du tutorat
					if($etudiant['sem_etu']>2)
					{
						$req = "SELECT tutorats.*, professeurs.nom_complet FROM tutorats, professeurs WHERE tutorats.trash != 1 AND tutorats.semestre = '".$semestre_courant;
						$req .="' AND tutorats.etudiant= '".$etudiant['id']."' AND professeurs.id= tutorats.professeur;";
						$rtut = mysql_query($req);
						$tut = mysql_fetch_array($rtut);
						$req="SELECT * FROM evaluations";
						$req .=" WHERE evaluations.tutorat ='".$tut['id']."';";
						$reval = mysql_query($req);
						if (mysql_num_rows($reval)>0)
						{
							$eval = mysql_fetch_array($reval);
						}else{
							$eval = array(
							"note_1"=>"-", 
							"note_2"=>"-",
							"appreciation_1"=>"-",
							"appreciation_2"=>"-");
						}
						//echo $req;
						//un tutorat est pris en compte
						$valide = valide_eval($eval["note_1"],$eval["note_2"],credits_tutorat($tut['niveau']));
						$classe = $valide['classe'];
						$total_acquis += $valide['creds'];
				
						echo  "\t<li class=\"".$classe."\"><a href=\"#\" title=\"".$tut["nom_complet"]."\">Tutorat</a> : ";
						echo "<a href='#' title=\"".$eval["appreciation_1"]."\">".$eval["note_1"]."</a> / ";
						echo "<a href='#' title=\"".$eval["appreciation_2"]."\">".$eval["note_2"]."</a>\n";
						echo " | ".credits_tutorat($etudiant['sem_etu'])." Cr.</li>";
				
						$n_lignes -=1;
				
				
				
						$total_inscrit += credits_tutorat($tut['niveau']);
				
					}
				
					//compensation des lignes inoccup�es
					while($n_lignes>0)
					{
						echo "<li>&nbsp;</li>";
						$n_lignes -=1;
					}
					//affichage de l'�valuation semestrielle en bas de liste
					echo $sem_eval;
					echo "<li class=\"credits\"><a class=\"contrat\" href=\"edition_contrats.php?id=".$etudiant["id"]."&nPeriode=".$semestre_courant."\">Contrat d'étude</a><span class=\"bleu\">".min($total_acquis,30)."</span> /".min($total_inscrit,30)."</li>";
					echo "</ul>";
				}
				
				?>
				<div id="footer"></div>
			</div>
		</div>
	</body>
</html>

