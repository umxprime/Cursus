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

include("lesotho.php");
include("fonctions.php");
include("fonctions_eval.php");
//on requiert les variables de connexion;
require("connect_info.php");
//puis la connexion standard;
require("connexion.php");
$outil="modules";
include("inc_sem_courant.php");
include("regles_utilisateurs.php");

if($_SESSION['auto']=="etudiant") exit();

//trouver les modules ayant déjà une session dans ce semestre
$req = "SELECT session.*, modules.intitule, modules.credits, modules.id as id_module, modules.code as module_code ";
$req .="FROM session, modules, professeurs ";
$req .="WHERE professeurs.id='".$_SESSION['userid']."' AND session.periode='".$semestre_courant."' AND modules.id=session.module ";
if($droits[$_SESSION['auto']]['voir_tous_modules']==false)
{
	$req .="AND modules.enseignants LIKE '%".$_SESSION['username']."%' ORDER BY modules.intitule ASC;";
}
else if($droits[$_SESSION['auto']]['voir_tous_modules']==true)
{
	$req .= "ORDER BY modules.code ASC;";
} else {
	header("Location: login.php?origine=".$_SERVER['PHP_SELF']);
}
//$req = "select session.*, module.intitule from session, modules where session.periode = '".$periode['id']."' and modules.id=session.module ORDER BY modules.code";
//echo $req;
$sessions = mysql_query($req) or die();
$c = mysql_num_rows($sessions);
//echo "c======".$c."\n";

	$chaineNot = "Select * from modules where id !='";
	$n=0;
	$tablModule = "";
	$nrow=0;
	while($session=mysql_fetch_array($sessions) )
	{
		$nrow++;
		if ($n>0) $chaineNot.=" and id!='";
		
		//echo $_SESSION['auto'];
		$req = "SELECT * FROM evaluations WHERE evaluations.session='".$session["id"]."';";
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
		$tablModule .="<a title=\"".utf8_encode($session["intitule"])." / ".$session["credits"]." cr\" href=\"gestion_modules.php?session=".$session["id"]."&nPeriode=".$periode["id"]."\">";
		$intitule = substr(utf8_encode($session["intitule"]),0,50);
		if (strlen(utf8_encode($session["intitule"]))>50) $intitule.="...";
		$tablModule .=$intitule."</a>\n</td>";
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
		$tablModule .="<td class=\"module\">";
		//if(!$eval_ok)$tablModule .="<a href=\"mail_fill_eval.php\">signaler</a>";
		$tablModule .="</td>";
		$tablModule .="<td class=\"module\">";
		if($droits[$_SESSION["auto"]]["edit_modules_adv"]) $tablModule .= "<a href=\"edition_modules.php?id=".$session['id_module']."&nPeriode=$semestre_courant\">Modifier le module</a>";
		$tablModule .="</td></tr>";
		
		$chaineNot .= $session["id_module"]."'";
		$n++;
	}
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
	</head>
	<body>
		<div id="global">
			<?php
			include("barre_outils.php"); 		
			include("inc_nav_sem.php");
			?>
			
				<table id="table_modules">
					<tr style="text-transform:uppercase;font-weight:bold;font-size:1.2em">
						<td>Code</td>
						<td>Intitulé</td>
						<td>Notes saisies</td>
						<td>Appréciations saisies</td>
						<td>Signaler</td>
						<td></td>
					</tr>
					<?php echo $tablModule;	?>
				</table>
			
			<?php
			if ($droits[$_SESSION['auto']]["ajouter_module"])
			{
			?>
			
			<form id="formulaire" action="ajouter_session.php" method="post">
			<fieldset style="border-style:none">
				<input type="hidden" name="nPeriode" value="<?php echo $periode["id"]; ?>"/>
				<table class="center">
					<tr><td>
						<h2>Ajouter un module pour ce semestre</h2>
					</td></tr>
					<tr><td>
						<label for="module">Choisir le module à ajouter :</label>
					</td></tr>
					<tr><td>
						<select id="module" name="module">
						<?php
							$n=0;
							while($resteModule = mysql_fetch_array($resNot)){
								//echo $resteModule['code']."\n";
								$l[$n]['val']=$resteModule["id"];
								$l[$n]['aff']=$resteModule["code"]." / ".$resteModule["intitule"];
								$n++;
							}
							echo affiche_options($l,"",0);
						?>
						</select>
					</td></tr>
					<tr><td>
						<label for="titre">Donner un titre sécifique à ce module pour ce semestre :</label>
					</td></tr>
					<tr><td>
						<?php echo affiche_ligne("titre","",false); ?>
					</td></tr>
					<tr><td>
						<input type="submit" value="ajouter"/>
					</td></tr>
				</table>
				</fieldset>
			</form>
			<?php
			}
			?>
		</div>
	</body>
</html>
