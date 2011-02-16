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

//on requiert les variables de connexion;
require "include/necessaire.php";

if($_SESSION['auto']=="e") header("Location:etudiants.php?nPeriode=$semestre_courant");
//si un mumero de semestre d'etude est transmis (pas la p�riode temporelle, le semestre d'enseignement
// c'est � dire les semestres de 1 � 10
$ns=1;
if(isset($_GET['ns'])) $ns = $_GET['ns'];
$niveau=$ns;
$etudiant_id = $_GET["id"];
$req = "SELECT id,nom,prenom,credits FROM etudiants WHERE id='$etudiant_id'";
$etudiant = mysql_fetch_array(mysql_query($req));

$req = "SELECT niveaux.id,niveaux.niveau,niveaux.periode,periodes.annee as anneePeriode,periodes.nom as nomPeriode FROM niveaux,periodes WHERE niveaux.niveau>0 AND niveaux.niveau<11 AND niveaux.etudiant='$etudiant_id' AND niveaux.periode=periodes.id ORDER BY periodes.annee ASC, periodes.nom ASC;";
//echo $req;
$res_sem = mysql_query($req);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
	<head>
		<?php include("inc_css_thing.php");?>
		<title>Cursus <?php revision();?> / Coordination des étudiants en semestre <?php echo $ns;?> pour la période : <?php echo $periode["nom"]; ?></title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" href="etu_sem.css" type="text/css"/>
		<!-- <link rel="stylesheet" href="etu_sem_print.css" type="text/css" media="print" />-->
		<link rel="stylesheet" href="etu_style.css" type="text/css" media="screen" />
		<?php
			$_LIMELIGHT_PATH = "com/umxprime/limelight/";
			include_once($_LIMELIGHT_PATH."core/limelight.php");
		?>
	</head>
	<body>
		<div id="global">
		<?php
		$outil="coordination";
		include("barre_outils.php");
		//include("inc_nav_sem.php");
		?>
		<input type="hidden" id="semestre_courant" value="<?php echo $semestre_courant;?>"/>
		<input type="hidden" id="etudiant_id" value="<?php echo $etudiant_id;?>"/>
		<div id="BulletinEntete">
			<ul class="bulletinNom">
				<li class="titre">nom</li>
				<li class="titreReponse"><?php echo utf8_encode($etudiant["nom"]);?></li>
				<li class="titre">prénom</li>
				<li class="titreReponse"><?php echo utf8_encode($etudiant["prenom"]);?></li>
				<li>
					<select class="design" id="etudiant">
					<?php
						$session = $_GET["session"];
						$req = "SELECT session.id as session_id, session.module as session_module, modules.id as module_id, modules.ecole as module_ecole, modules.obligatoire FROM session, modules WHERE session.id='$sessionId' AND session.module=modules.id;";
						//echo $req;
						$res = mysql_fetch_array(mysql_query($req));
						$ecoles = explode("--",substr($res["module_ecole"],1,strlen($res["module_ecole"])-2));
						if($droits[$_SESSION["auto"]]["voir_tous_sites"])
						{
							for($i=0;$i<count($ecoles);$i++)
							{
								echo liste_etudiants($sauf, $connexion, $semestre_courant, $ecoles[$i],false,false,false);
							}
						} else {
							echo liste_etudiants($sauf, $connexion, $semestre_courant, $_SESSION["ecole"],false,false,false);
						}
					?>
					</select>
				</li>
				<li><a href="editer_bulletin.php?id=<?php echo $etudiant_id;?>&all=1" class="bouton">Éditer le cursus</a></li>
			</ul>
		</div>
		
		<div id="content">
			
			<?php
			//récupérer les étudiants inscit dans le semestre d'étude durant la période choisie
			//pour chaque etudiant afficher un mini bulletin avec ses modules, son tutorat et son évaluation
			$global_inscrit=0;
			$global_acquis=$etudiant["credits"];
			while($niveauSemestre=mysql_fetch_array($res_sem))
			{
				echo "<ul class=\"bulletin\">\n";
				echo "<li class=\"nomBulletin\"><a href='vue_bulletin.php?id_etudiant=".$etudiant_id."&nPeriode=".$niveauSemestre["periode"]."'>".$niveauSemestre["nomPeriode"]." ";
				echo $niveauSemestre["anneePeriode"]." (s".$niveauSemestre["niveau"].")";
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
				$req_evals .= " evaluations.etudiant = '".$etudiant_id."' AND session.id=evaluations.session";
				$req_evals .= " AND session.periode = '".$niveauSemestre["periode"]."' AND modules.id=session.module ORDER BY modules.code ASC;";
			
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
				$req = "SELECT * FROM stages WHERE etudiant='".$etudiant_id."' AND periode='".$niveauSemestre["periode"]."';";
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
						$comm = "validé";
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
				if($niveauSemestre["niveau"]>2)
				{
					$req = "SELECT tutorats.*, professeurs.nom_complet FROM tutorats, professeurs WHERE tutorats.trash != 1 AND tutorats.semestre = '".$niveauSemestre["periode"];
					$req .="' AND tutorats.etudiant= '".$etudiant_id."' AND professeurs.id= tutorats.professeur;";
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
					echo " | ".credits_tutorat($niveauSemestre['niveau'])." Cr.</li>";
					$n_lignes -=1;
					$total_inscrit += credits_tutorat($niveauSemestre['niveau']);
				}
			
				//compensation des lignes inoccup�es
				while($n_lignes>0)
				{
					echo "<li>&nbsp;</li>";
					$n_lignes -=1;
				}
				//affichage de l'�valuation semestrielle en bas de liste
				echo $sem_eval;
				echo "<li class=\"credits\"><a class=\"contrat\" href=\"edition_contrats.php?id=".$etudiant_id."&nPeriode=".$niveauSemestre["periode"]."\">Contrat d'étude</a><span class=\"bleu\">".min($total_acquis,30)."</span> / ".min($total_inscrit,30)."</li>";
				$global_acquis+=min($total_acquis,30);
				$global_inscrit=$niveauSemestre["niveau"]*30;
				echo "<li class=\"credits\"><span class=\"bleu\">".min($global_acquis,$global_inscrit)."</span> / ".$global_inscrit."</li>";
				echo "</ul>";
			}
			
			?>
			<div id="footer"></div>
		</div>
		</div>
	</body>
</html>

