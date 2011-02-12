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
	 * Cursus uses Potajx
	 * released under the GPL <http://www.gnu.org/licenses/>
	 * by Maxime CHAPELET (umxprime@umxprime.com)
	 * 
	 **/

require "include/necessaire.php";

if($_SESSION['auto']=="e") header("Location:etudiants.php?nPeriode=$semestre_courant");

//trouver les modules ayant déjà une session dans ce semestre
$req = "SELECT session.*, modules.intitule, modules.credits, modules.id as id_module, modules.code as module_code, modules.ecole as modules_ecole ";
$req .="FROM session, modules, professeurs ";
$req .="WHERE professeurs.id='".$_SESSION['userid']."' AND session.periode='$semestre_courant'AND modules.id=session.module ";
if(!$droits[$_SESSION['auto']]['voir_tous_modules'] or $_GET["vue"]==1)
{
	$req .="AND modules.enseignants LIKE '%".$_SESSION['username']."%' ORDER BY modules.intitule ASC;";
}
else if($droits[$_SESSION['auto']]['voir_tous_modules'])
{
	$req .= "ORDER BY modules.code ASC;";
} else {
	header("Location: login.php?origine=".$_SERVER['PHP_SELF']);
}
$sessions = mysql_query($req) or die();
$c = mysql_num_rows($sessions);


	$chaineNot = "SELECT * FROM modules WHERE ";
	$arrayNot = Array("id!='-1'");
	$tablModule = "";
	$nrow=0;
	$ecole = $_SESSION["ecole"];
	while($session=mysql_fetch_array($sessions) )
	{
		$ecoles = $session["modules_ecole"];
		if (!strstr($ecoles,"-$ecole-") && $ecoles!=$ecole && !$droits[$_SESSION["auto"]]["voir_tous_sites"])
		{
			array_push($arrayNot,"id!='".$session["id_module"]."'");
			continue;
		}
		$nrow++;
		array_push($arrayNot,"id!='".$session["id_module"]."'");
		//echo $_SESSION['auto'];
		$req = "SELECT evaluations.* FROM evaluations,etudiants WHERE etudiants.id=evaluations.etudiant AND evaluations.session='".$session["id"]."';";
		$res = mysql_query($req) or die(mysql_error());
		$inscrits_s1 = mysql_num_rows($res);
		$appreciation_s1 = 0;
		$notes_s1 = 0;
		$inscrits_s2 = 0;
		$appreciation_s2 = 0;
		$notes_s2 = 0;
		while ($eval = mysql_fetch_array($res))
		{
			if ($eval["appreciation_1"]!='') $appreciation_s1 ++;
			if (verif($eval["note_1"])!='-') $notes_s1 ++;
			if (verif($eval["note_1"])!='-' && $eval["valide_1"]=='0')
			{
				$inscrits_s2++;
				if ($eval["appreciation_2"]!='') $appreciation_s2 ++;
				if (verif($eval["note_2"])!='-') $notes_s2 ++;
			}
		}
		/*
		$req = "SELECT * FROM evaluations WHERE evaluations.session='".$session["id"]."' AND note_1!='';";
		$res = mysql_query($req) or die(mysql_error());
		$notes_s1 = mysql_num_rows($res);
		
		$req = "SELECT * FROM evaluations WHERE evaluations.session='".$session["id"]."' AND note_1!='' AND valide_1='0';";
		$res = mysql_query($req) or die(mysql_error());
		$inscrits_s2 = mysql_num_rows($res);
		$manque_s2 = 0;
		while ($eval = mysql_fetch_array($res))
		{
			if ($eval["appreciation_2"]=='') $manque_s2 ++;
		}
		$req = "SELECT * FROM evaluations WHERE evaluations.session='".$session["id"]."' AND note_1!='' AND valide_1='0' AND note_2!='';";
		$res = mysql_query($req) or die(mysql_error());
		$notes_s2 = mysql_num_rows($res);
		*/
		//$color_row="#EFE";
		//if($nrow%2)$color_row="";
		$tablModule .="<tr>\n<td class=\"module\">\n";
		$tablModule .= $session["module_code"];
		$tablModule .="</td>\n<td class=\"module\">\n";
		$tablModule .="<a title=\"".utf8_encode($session["intitule"])." / ".$session["credits"]." cr\" href=\"edition_session.php?session=".$session["id"]."&nPeriode=".$periode["id"]."\">";
		$intitule = substr(utf8_encode($session["intitule"]),0,50);
		if (strlen(utf8_encode($session["intitule"]))>50) $intitule.="...";
		$tablModule .=$intitule." (".$session["credits"]."cr)</a>\n</td>";
		$color_s1 = "color:#80B711;font-weight:bold;";
		$color_s2 = "color:black;";
		$eval_ok = true;
		if($notes_s1<$inscrits_s1)
		{
			$eval_ok = false;
			$color_s1 = "color:#E40;font-weight:bold";
		}
		if($notes_s2<$inscrits_s2)
		{
			$eval_ok = false;
			$color_s2 = "color:#E40;font-weight:bold";
		}
		if($notes_s2==$inscrits_s2 && $inscrits_s2>0)$color_s2 = "color:#80B711;font-weight:bold";
		if($inscrits_s1==0)
		{
			$eval_ok = false;
			$color_s1 = "color:#E40;font-weight:bold";
		}
		$tablModule .="<td class=\"module\">";
		$tablModule .="<span style='$color_s1'>$notes_s1 / $inscrits_s1</span> | ";
		$tablModule .="<span style='$color_s2'>$notes_s2 / $inscrits_s2</span>";
		$tablModule .="</td>";
		$color_s1 = "color:#80B711;";
		$color_s2 = "color:#80B711;";
		if($appreciation_s1==0 && $inscrits_s1==0) $color_s1 = "color:black;";
		if($appreciation_s2==0 && $inscrits_s2==0) $color_s2 = "color:black;";
		if($appreciation_s1<$inscrits_s1)
		{
			$eval_ok=false;
			$color_s1 = "color:#E40;font-weight:bold";
		}
		if($appreciation_s2<$inscrits_s2)
		{
			$eval_ok=false;
			$color_s2 = "color:#E40;font-weight:bold";
		}
		$tablModule .="<td class=\"module\">";
		$tablModule .="<span style='$color_s1'>$appreciation_s1 / $inscrits_s1</span> | ";
		$tablModule .="<span style='$color_s2'>$appreciation_s2 / $inscrits_s2</span>";
		$tablModule .="</td>";
		//$tablModule .="<td class=\"module\">";
		//if(!$eval_ok)$tablModule .="<a href=\"mail_fill_eval.php\">signaler</a>";
		//$tablModule .="</td>";
		$tablModule .="<td class=\"module\">";
		if($droits[$_SESSION["auto"]]["edit_modules_adv"]) $tablModule .= "<a href=\"edition_modules.php?id=".$session['id_module']."&nPeriode=$semestre_courant\">Modifier le module</a>";
		$tablModule .="</td></tr>";
	}
	$chaineNot .= implode(" AND ", $arrayNot);
	if(!$droits[$_SESSION["auto"]]["voir_tous_sites"])$chaineNot .= " AND ecole LIKE '%-$ecole-%' ";
	$chaineNot .= " AND (desuetude='0000-00-00' OR desuetude >'".$dateCourante."') ORDER BY code;";
	$resNot = mysql_query($chaineNot);
	$tablModule .="<tr>\n<td class=\"module\"></td><td class=\"module\">\n";
	$tablModule .="<a href=\"tutorats.php?session=".$session["id"]."&periode=".$periode['id']."\">";
	$tablModule .="Tutorat</a>\n</td class=\"module\"><td class=\"module\"></td><td class=\"module\"></td><td class=\"module\"></td></tr>";
	//afficher les modules
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<?php include("inc_css_thing.php");	?>
		<title>Cursus <?php echo revision();?> / Modules pour la période : <?php echo $periode['nom'];?></title>
		<?php
			$_LIMELIGHT_PATH = "com/umxprime/limelight/";
			include_once($_LIMELIGHT_PATH."core/limelight.php");
		?>
	</head>
	<body>
		<div id="global">
			<?php
			$outil="modules";
			include("barre_outils.php");
			$plus_nav_semestre[0] = array("var"=>"vue","val"=>$_GET["vue"]);	
			include("inc_nav_sem.php");
			?>
			<input type="hidden" id="semestre_courant" value="<?php echo $semestre_courant;?>"/>
			<?php
				if($droits[$_SESSION['auto']]['voir_tous_modules'])
				{
			?>
			<table class="center full">
				<tr class="line"><td>
						Voir
					<?php
						$listeVues = new HtmlFieldSelect();
						$listeVues->setFieldId("vue");
						$listeVues->setFieldOptions(Array("0","1"),Array("tous les modules","mes modules"));
						$listeVues->selectOption($_GET["vue"]);
						$listeVues->renderField();
					?>
				</td></tr>
			</table>
			<?php
				} 
			?>
			<?php if($droits[$_SESSION['auto']]['edit_modules_adv']){?>
				<table class="center full">
					<tr><td class="center">
						<a class="bouton" href="edition_modules.php?nPeriode=<?php echo $semestre_courant;?>&id=-1" class="button">Éditer un module</a>
						<a class="bouton" href="edition_unites.php?nPeriode=<?php echo $semestre_courant;?>" class="button">Gérer les unités</a>
					</td></tr>
					<tr><td class="center">
						<?php
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
						if(($dateLimiteEval[1]<date("Y-m-d H:i:s",time()) && $limiteEvalActive == true))
						echo "<h2 style=\"color:#E40;font-weight:bold\">La saisie des évaluations est clôturée pour cette période.</h2>"; 
						?>
					</td></tr>
				</table>
			<?php }?>
				<table id="table_modules">
					<tr style="text-transform:uppercase;font-weight:bold;font-size:1.2em">
						<td>Code</td>
						<td>Intitulé</td>
						<td>Notes saisies</td>
						<td>Appréciations saisies</td>
						<!--<td>Signaler</td>-->
						<td></td>
					</tr>
					<?php echo $tablModule;	?>
				</table>
				<table class="center full"><tr style="height:30px;"><td class="line"></td></tr></table>
		</div>
	</body>
</html>