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

include "include/necessaire.php";

(!$_GET['session'])?$sessionId = $_POST['session']:$sessionId = $_GET['session'];
$req = "SELECT * FROM session where id = '".$sessionId."';";
//$req = "SELECT * FROM session, periodes, modules, evaluations, "
$res = mysql_query($req);
$session = mysql_fetch_array($res);
$req2 = "SELECT * FROM periodes where id = '".$session['periode']."';";
$res2 = mysql_query($req2);
$semestre = mysql_fetch_array($res2);
$datesLimiteEvalTous = explode(",",$semestre["datelimite"]);
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
//echo $semestre['titre'];
if($droits[$_SESSION['auto']]["edit_tous_modules"]){
	$req = "select intitule,enseignants from modules where id = '".$session['module']."';";
}else if($droits[$_SESSION['auto']]["edit_modules"]){
	$req = "select intitule,enseignants from modules where id = '".$session['module']."' AND enseignants LIKE '%".$_SESSION['username']."%';";
}else{
	$req="select id from etudiants wehre id <0;";
}
//echo $req;
$res = mysql_query($req);
$module = mysql_fetch_array($res);
//echo "error :".mysql_error();
$ava=mysql_num_rows($res);
//echo "nres :".$ava;
if($ava>0){
	$req = "SELECT evaluations.*, etudiants.nom, etudiants.prenom, etudiants.log, etudiants.mail, etudiants.id as id_etudiant ";
	$req .= "FROM evaluations, etudiants WHERE evaluations.session='".$sessionId."' and etudiants.id=evaluations.etudiant ";
	$req .= "ORDER BY etudiants.nom";
	$resEvals = mysql_query($req);
	echo mysql_error();
	$nres = mysql_num_rows($resEvals);
	//echo $req;
	if ($nres>0){
		//echo "nres :".$nres;
		$destsMail="";
		$tablEvals ="<table><tr>\n<th style=\"padding:20px;\">Etudiant</th>";
		$tablEvals .= "\t<th style=\"padding:20px;\">Mail</th>\n";
		if(!($dateLimiteEval[1]<date("Y-m-d H:i:s",time()) && $limiteEvalActive==true))$tablEvals .= "\t<th style=\"padding:20px;\"><a class=\"bouton\" href=\"javascript:tout_desinscrire();\">Tout désinscrire</a></th>\n";
		for($i=1; $i<=10;$i++){
			$tablEvals .= "\t<th><a class=\"bouton\" href=\"javascript:presence($i)\">$i</a></th>\n";
		}
		$tablEvals .= "\t<th style=\"padding:20px;\">Session <br/>#1</th>\n";
		$tablEvals .= "\t<th style=\"padding:20px;\">Rattrapage</th>\n";
		$tablEvals .= "\t<th style=\"padding:20px;\"><a class=\"bouton\" href=\"javascript:publier();\">...</a></th>\n";
		$tablEvals.= "</tr>";

		$neval = 1;
		while($eval = mysql_fetch_array($resEvals))
		{
			//			$req = "SELECT * FROM etudiants WHERE id='".$eval['etudiant']."';";
			//echo "requete : ".$req."\n";
			//			$res= mysql_query($req);
			//			$etudiant = mysql_fetch_array($res);
			$tablEvals .= "<tr>\n\t<td>";
			$tablEvals .= utf8_encode($eval['prenom']). " ".utf8_encode($eval['nom']);
			$tablEvals .= "\n\t</td>\n";
			$tablEvals .= "\t<td class=\"center\">\n";
			$tablEvals .= "<a class=\"bouton\" href=\"mailto:".utf8_encode($eval['prenom'])." ".utf8_encode($eval['nom'])."<".$eval["log"]."@esa-npdc.net>\">".$eval["log"]."</a>";
			$tablEvals .= "\t</td>\n\t";
			$destsMail .= $eval['prenom']." ".$eval['nom'];
			$destsMail .= "<".$eval["log"]."@esa-npdc.net>";
			if($neval<$nres){$destsMail .=", ";}
			//desinscription de l'étudiant
			if(!($dateLimiteEval[1]<date("Y-m-d H:i:s",time()) && $limiteEvalActive==true))$tablEvals .= "<td class=\"center\">\n<a class=\"bouton\" href=\"javascript:desinscrire(".$eval['id'].",'".utf8_encode($eval['prenom'])." ".utf8_encode($eval['nom'])."');\">Désinscrire</a></td>\n";
			$tablEvals .= "<td class=\"center\">";
			$sauf[] = $eval['id_etudiant'];
			for($d=1;$d<=10;$d++){
				$tablEvals .= "<input id=\"presence_$neval-$d\" name=\"presences".$eval['id']."[]\" value=\"".$d."\" type=\"checkbox\" ";
				if (strpos($eval['presences'], "".$d)){
					$tablEvals .= "checked=\"checked\"";
				}
				$tablEvals .= ">\n";
				$tablEvals .="\t</td>\n\t<td class=\"center\">\n";
				//echo $d."\n";
			}
			//$tablEvals .="</td>\n";
			$tablEvals .="<a class=\"bouton\" href = \"edit_eval.php?eval=".$eval['id']."&nPeriode=$semestre_courant\">";
			//$tablEvals .="<a href = \"edit_eval.php?eval=".$eval['id']."\">";
			$tablEvals .= (empty($eval['note_1']) or $eval['note_1']=='-')?"Éditer":$eval['note_1'];
			$tablEvals .= "</a></td>\n";
			$tablEvals .="<td>";
			if(!empty($eval['note_1'])){
				if(strpos("__efEF",$eval['note_1'])){
					$tablEvals .="<a class=\"bouton\" href = \"edit_eval.php?eval=".$eval['id']."&nPeriode=$semestre_courant\">";
					//$tablEvals .="<a href = \"edit_eval.php?eval=".$eval['id']."\">";
					$tablEvals.= (empty($eval['note_2']) or $eval['note_2']=='-')?"&eacute;diter":$eval['note_2'];
					$tablEvals .= "</a>";
				}
			}
			$tablEvals .="</td><td>";
			$tablEvals .= "<input type=\"checkbox\" id=\"publier_".$neval."\"";
			if ($eval['publier']==1){
				$tablEvals .= " checked=\"checked\"";
			}
			$tablEvals .= "/>\n";
			$tablEvals .= "</td>\n</tr>";
			$neval++;
		}
		$tablEvals .="\n</table>";
		//echo $chaineNot;
	}else
	{
		$chaineNot = "SELECT etudiants.*, niveaux.niveau, niveaux.id as id_niveau FROM etudiants, niveaux WHERE ";
		$chaineNot .="niveaux.periode='".$semestre_courant."' AND niveaux.niveau>0 AND niveaux.niveau <11 AND etudiants.id =niveaux.etudiant";
	}

	?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
		<?php
		include("inc_css_thing.php");
		?>
		<title>Cursus <?php revision();?> / Gestion de module : <?php echo utf8_encode($module['intitule']) ?></title>
		<?php
			$_LIMELIGHT_PATH = "com/umxprime/limelight/";
			include_once($_LIMELIGHT_PATH."core/limelight.php");
		?>
	</head>
	<body>
		<div id="global">
		<?php
			$outil="modules";
			include("barre_outils.php") ;
			$disableNavSemPrec=true;
			$disableNavSemSuiv=true;
			include("inc_nav_sem.php");
		?>
		<input type="hidden" id="session" value="<?php echo $sessionId; ?>"/>
		<input type="hidden" id="semestre_courant" value="<?php echo $semestre_courant; ?>"/>
		<table class="center"><tr><td class="center">
		<h2><?php echo utf8_encode($module['intitule'])?> dispensé par <?php echo utf8_encode($module['enseignants'])?></h2>
		<?php
		$session = $_GET["session"];
		$req = "SELECT session.id as session_id, session.module as session_module, modules.id as module_id, modules.ecole as module_ecole, modules.obligatoire FROM session, modules WHERE session.id='$sessionId' AND session.module=modules.id;";
		//echo $req;
		$res = mysql_fetch_array(mysql_query($req));
		$ecoles = explode("--",substr($res["module_ecole"],1,strlen($res["module_ecole"])-2));
		if(!($dateLimiteEval[1]<date("Y-m-d H:i:s",time()) && $limiteEvalActive==true))
		{
		?>
		<h2>Inscrire des étudiants à ce module</h2>
		<p>
			<select class="design" id="etudiant">
			<?php
			if($droits[$_SESSION["auto"]]["voir_tous_sites"])
			{
				for($i=0;$i<count($ecoles);$i++)
				{
					echo liste_etudiants($sauf, $connexion, $semestre_courant, $ecoles[$i],false,true,false);
				}
			} else {
				echo liste_etudiants($sauf, $connexion, $semestre_courant, $_SESSION["ecole"],false,true,false);
			}
			?>
			</select>
			<a class="bouton" href="javascript:inscrire();">Inscrire</a>
		</p>
		<?php 
		} else {
			if ($res["obligatoire"]>0)
			{
			?>
			<p>Ce module est obligatoire pour les étudiants en semestre <?php echo intval($res["obligatoire"]);?></p>
			<?php
			}
			//if(($dateLimiteEval[1]<date("Y-m-d H:i:s",time()) && $limiteEvalActive == true))
			echo "<h2 style=\"color:#E40;font-weight:bold\">La saisie des évaluations est clôturée pour cette période.</h2>";
		}
			if($nres>0){
		?>
		<form id="fpresences" action="reg_session.php" method="post">
			<?php
			echo $tablEvals;
			?>
			<fieldset style="border-style:none;">
					<input type="hidden" name="session" value="<?php echo $sessionId; ?>"/>
					<input type="hidden" name="nPeriode" value="<?php echo $periode_courante; ?>"/>
					<input type="submit" value="valider les présences"/>
					<!-- <a class="bouton" href="javascript:appliquer();">Appliquer les modifications</a> -->
			</fieldset>
		</form>
		<?php
			}
		?>
		<p><?php echo affiche_champs("dests",$destsMail,80,8); ?></p>
		<?php
			}
		?>
		<fieldset>
			<input type="hidden" id="neval" value="<?php echo $neval-1;?>"/>
		</fieldset>
		</td></tr></table>
		</div>
	</body>
</html>
