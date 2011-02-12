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
require "include/necessaire.php";

if($_GET["eval"]>0){
	$id_eval=$_GET["eval"];
	$req = "SELECT * FROM evaluations WHERE id='".$id_eval."';";
	$res = mysql_query($req);
	
	$eval = mysql_fetch_array($res);
	$req = "select module, periode from session where id = '".$eval['session']."';";
	$res = mysql_query($req);
	$session = mysql_fetch_array($res);
	$req2 = "SELECT * FROM periodes where id = '".$session['periode']."';";
	$res2 = mysql_query($req2);
	$periode = mysql_fetch_array($res2);
	if($droits[$_SESSION['auto']]["edit_tous_evaluations"]){
		$req = "select intitule,enseignants from modules where id = '".$session['module']."';";
	}else if($droits[$_SESSION['auto']]["edit_evaluations"]){
		$req = "select intitule,enseignants from modules where id = '".$session['module']."' AND enseignants LIKE '%".$_SESSION['username']."%';";
	}else{
		$req="select id from etudiants where id <0;";
	}
	//echo $req;
	$res = mysql_query($req);
	$module = mysql_fetch_array($res);
	$ava=mysql_num_rows($res);
	if($ava>0){
		$req = "select id,nom, prenom from etudiants where id = '".$eval['etudiant']."';";
		$res = mysql_query($req);
		$etudiant = mysql_fetch_array($res);
		?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" href="cursusn.css" type="text/css"/>
	<title>Évaluation de <?php echo utf8_encode($etudiant['prenom'])." ".utf8_encode($etudiant['nom']);?> pour le module : <?php echo utf8_encode($module['intitule']); ?></title>
</head>
<body>
<div id="global">
<?php 
	$outil="modules";
	include("barre_outils.php");
	$disableNavSemPrec=true;
	$disableNavSemSuiv=true;
	include("inc_nav_sem.php");
?>
<table class="center"><tr><td>
<h2>Evaluation de <?php echo utf8_encode($etudiant['prenom'])." ".utf8_encode($etudiant['nom']);?> pour le module : <a class="bouton" href="gestion_modules.php?nPeriode=<?php echo $semestre_courant;?>&session=<?php echo $eval['session'];?>"><?php echo utf8_encode($module['intitule']);?></a></h2>
<h2>Première session</h2>
<form id="evaluation" method="post" action="reg_eval.php">

	<?php
		echo affiche_champs("appreciation_1",$eval['appreciation_1'],80,8);
		echo "<br />NOTE : <select name=\"note_1\" width=\"40\" STYLE=\"width: 40px\">";
		echo "<option value='' ";
		echo (!strpos("__abcdefABCDEF",verif($eval['note_1'])))?"selected ":"";
		echo ">-</option>\n";
		for($l=1; $l<7; $l++)
		{
			$carac=chr(64+$l);
			echo "<option value='".$carac."' ";
			echo ($eval['note_1']==$carac)?"selected ":"";
			echo ">".$carac."</option>\n";
		}
		
		echo "</select></p>\n<p>";
		if(!empty($eval['note_1'])){
			if(strpos("__efEF",$eval['note_1'])){
				echo "<h2>Deuxième session</h2></p><p>";
				echo affiche_champs("appreciation_2",$eval['appreciation_2'],80,false);
				echo "<br/>NOTE : <select name=\"note_2\">";
				echo "<option value='' ";
				echo (!strpos("__abcdefABCDEF",verif($eval['note_2'])))?"selected ":"";
				echo ">-</option>\n";
				for($l=1; $l<7; $l++){
					$carac=chr(64+$l);
					echo "<option value='".$carac."' ";
					echo ($eval['note_2']==$carac)?"selected ":"";
					echo ">".$carac."</option>\n";
				}
				echo "</select></p>\n";
			}
		}
		echo "<input type=\"hidden\" name=\"eval\" value=\"".$eval['id']."\" >";
		echo "<input type=\"hidden\" name=\"session\" value=\"".$eval['session']."\" >";
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
		if($dateLimiteEval[1]<date("Y-m-d H:i:s",time()) && $limiteEvalActive == true/*|| $droits[$_SESSION['auto']]["edit_evaluations_toujours_actif"]*/)
		{
			echo "<h2 style=\"color:#E40;font-weight:bold\">La saisie des évaluations est clôturée pour cette période.</h2>";
		} else {
			echo "<input type=\"submit\" value=\"enregistrer l'évaluation\" >";
		}
	}

}?>
</form>
</td></tr></table>
</div>
</body>
</html>