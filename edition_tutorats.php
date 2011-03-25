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
$dateCourante = date("Y-m-d");
//echo $semestre['titre'];
if (isset($_POST['tuteur']) or isset($_GET['tuteur'])){
	//$req = "SELECT * FROM tutorats where professeur = '".$_POST['tuteur']."' AND semestre='".$_POST['periode']."' AND trash !=1;";
	$tuteur = $_POST['tuteur'];
	$tuteur = isset($_GET['tuteur'])?$_GET['tuteur']:$tuteur;
	$rprof = "SELECT * FROM professeurs WHERE id='".$tuteur."';";
	$resprof = mysql_query($rprof);
	$prof = mysql_fetch_array($resprof);
	$nomTuteur = $prof['nom_complet'];
}else{
	$tuteur = $_SESSION['userid'];
	$nomTuteur = $_SESSION['username'];
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

$req = "SELECT * FROM periodes WHERE id='".$_GET["nPeriode"]."'";
$periode = mysql_fetch_array(mysql_query($req));
$datesLimiteEvalTous = explode(",",$periode["datelimite"]);
$limiteEvalActive = false;
foreach ($datesLimiteEvalTous as $dateLimiteEvalEcole)
{
	$dateLimiteEval = explode("@",$dateLimiteEvalEcole);
	if($dateLimiteEval[0]==$_SESSION["ecole"])
	{
		$limiteEvalActive = true;
		break;
	}
}
if ($nres>0){
	
	//echo "nres :".$nres;
	$chaineNot = "SELECT * FROM etudiants WHERE id !='";
	$tablEvals ="<table><tr>\n<th>Etudiant</th>";
	if(!($dateLimiteEval[1]<date("Y-m-d H:i:s",time()) && $limiteEvalActive == true))$tablEvals .= "\t<th>Annuler <br/>inscription</th>\n";
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
		$tablEvals .= "\n\t</td>\n\t";
		//desinscription de l'étudiant
		if(!($dateLimiteEval[1]<date("Y-m-d H:i:s",time()) && $limiteEvalActive == true))$tablEvals .= "<td><a class=\"bouton\" href=\"javascript:desinscrire(".$tutorat["id"].",'".utf8_encode($etudiant['prenom'])." ".utf8_encode($etudiant['nom'])."')\">désinscrire</a></td>";
		$tablEvals .=  "\n<td>";
		$sauf[] = $etudiant['id'];
		for($d=1;$d<=5;$d++){
			if(is_array($rdv[$d-1])){
				$tablEvals .= "<a class=\"bouton\" href=\"edit_rdv.php?rdv=".$rdv[$d-1]['id']."&nPeriode=$semestre_courant\">";
				if (strlen($rdv[$d-1]['cr'])>1){
					$tablEvals .= "modifier";
				}else{
					$tablEvals .= "créer";
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
		$tablEvals .="<a class=\"bouton\" href=\"edit_tutorats.php?eval=".$eval['id']."&nPeriode=$semestre_courant\">";
		//$tablEvals .="<a href = \"edit_eval.php?eval=".$eval['id']."\">";
		$tablEvals .= (empty($eval['note_1']))?"&eacute;diter":$eval['note_1'];
		$tablEvals .= "</a></td>\n";
		$tablEvals .="<td>";
		if(!empty($eval['note_1'])){
			if(strpos("__efEF",$eval['note_1'])){
				//$tablEvals .="<a href = \"edit_eval.php?eval=".$eval['id']."&session_name()=".session_id()."\">";
				$tablEvals .="<a class=\"bouton\" href=\"edit_tutorats.php?eval=".$eval['id']."\">";
				$tablEvals.= (empty($eval['note_2']) )?"&eacute;diter":$eval['note_2'];
				$tablEvals .= "</a>";
			}
		}
		$tablEvals .= "</td><td>";
		$tablEvals .= "<a class=\"bouton\" href=\"edition_contrats.php?id=".$tutorat['etudiant']."&nPeriode=$semestre_courant\">Contrat d'étude</a>";
		$tablEvals .= "</td><td>";
		$tablEvals .= "<a class=\"bouton\" href=\"vue_bulletin.php?id_etudiant=".$tutorat['etudiant']."&nPeriode=$semestre_courant\">Bulletin</a>";
		$tablEvals .= "</td><td>";
		$tablEvals .= "<a class=\"bouton\" href=\"vue_cursus.php?id=".$tutorat['etudiant']."&nPeriode=$semestre_courant\">Cursus</a>";
		$tablEvals .= "</td>\n</tr>";
		//vue_bulletin.php?id_etudiant=784&nPeriode=26
		$ntuts++;
	}
	$tablEvals .="\n</table>";
}
//echo $chaineNot;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<?php include("inc_css_thing.php");	?>
		<title>Cursus <?php revision();?> / Tutorats : <?php echo utf8_encode($nomTuteur); ?></title>
		<?php
			$_LIMELIGHT_PATH = "com/umxprime/limelight/";
			include_once($_LIMELIGHT_PATH."core/limelight.php");
		?>
	</head>
	<body>
		<div id="global">
			
			<?php
			$outil="tutorat";
			include("barre_outils.php");
			$plus_nav_semestre[0] = array("var"=>"tuteur","val"=>"$tuteur");
			include("inc_nav_sem.php");
			?>
			<input type="hidden" id="nPeriode" value="<?php echo $periode_courante;?>"/>
			<input type="hidden" id="tuteur_id" value="<?php echo $tuteur;?>"/>
			<table>
			<tr><td>
			<?php
			if($droits[$_SESSION['auto']]['admin_tutorats'])
			{ 
				$req = "SELECT nom,prenom,id FROM professeurs WHERE 1 ";
				if(!$droits[$_SESSION["auto"]]["voir_tous_sites"]) $req.="AND ecole='".$_SESSION["ecole"]."' ";
				$req.= "ORDER BY nom,prenom;"; 
				$res = mysql_query($req); 
				$listeEnseignants = new HtmlFieldSelect();
				$listeEnseignants->setFieldId("tuteur");
				while($enseignant = mysql_fetch_array($res))
				{
					$listeEnseignants->appendFieldOption($enseignant["id"],utf8_encode($enseignant["nom"]." ".$enseignant["prenom"]));
				}
				$listeEnseignants->setLabel("Tuteur :");
				$listeEnseignants->renderField();
			}
			?>
			
			<p>
			<?php
				if($nres>0)
				{
					echo $tablEvals;
			?>
			</p>
			<?php
				}
				$chaineNot.= " GROUP BY semestre, nom";
				//echo $chaineNot;
				$resNot = mysql_query($chaineNot);
			?>
			<?php
				if(($dateLimiteEval[1]<date("Y-m-d H:i:s",time()) && $limiteEvalActive == true))
				{
				echo "<h2 style=\"color:#E40;font-weight:bold\">La saisie des évaluations est clôturée pour cette période.</h2>";
				}
				else
				{ 
			?>
			<h2>Inscrire un étudiant en tutorat</h2>
			<form id="formulaire2" action="reg_tutorats.php?nPeriode=<?php echo $semestre_courant;?>" method="post">
				
				<fieldset style="border-style:none;">
					Nom de l'etudiant :
					<select id="etudiant" name="etudiant">
						<?php
							echo liste_etudiants($sauf, $connexion, $periode['id'],$_SESSION["ecole"],$droits[$_SESSION['auto']]['voir_tous_sites']);
						?>
					</select>
					<input type="hidden" id="periode" name="periode" value="<?php echo $semestre_courant; ?>" />
					<input type="hidden" id="tuteur" name="tuteur" value="<?php echo $tuteur; ?>" />
					<input type="hidden" id="tutorat" name="tutorat" value="0"/>
					<input type="hidden" id="action" name="action" value="inscrire"/>
					<input type="submit" value="inscrire"/>
				</fieldset>
			</form>
			<?php 
				}
			?>
			</td></tr>
			</table>
		</div>
	</body>
</html>
