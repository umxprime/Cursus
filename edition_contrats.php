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
	
	if ($_SESSION['auto']=="e")	header("location:login.php");
	//if (!$droits[$_SESSION['auto']]['edit_contrats'])
	$id = $_GET["id"];
	$ecole = $_SESSION["ecole"];
	$req = "SELECT etudiants.id,etudiants.nom,etudiants.prenom,niveaux.niveau FROM etudiants,niveaux WHERE etudiants.id='".$_GET["id"]."' AND niveaux.etudiant=etudiants.id AND niveaux.periode='$semestre_courant';";
	//echo $req;
	$etudiant = mysql_fetch_array(mysql_query($req)) or die(mysql_error());
	$req = "SELECT "; 
	$req .= "modules.id,";
	$req .= "modules.code,";
	$req .= "modules.intitule,";
	$req .= "modules.credits,";
	$req .= "modules.obligatoire,";
	$req .= "evaluations.id as evaluation_id";
	$req .= " FROM ";
	$req .= "`evaluations`,`session`,`modules`";
	$req .= " WHERE ";
	$req .= "evaluations.etudiant='$id' AND ";
	$req .= "(modules.ecole LIKE '%-$ecole-%' OR modules.ecole = '$ecole') AND ";
	$req .= "evaluations.session=session.id AND ";
	$req .= "session.periode='$semestre_courant' AND ";
	$req .= "session.module=modules.id ORDER BY modules.code ASC;";
	$inscrits = mysql_query($req) or die(mysql_error());
	$inscriptions = array();
	while($inscrit = mysql_fetch_array($inscrits))
	{
		$obligatoire=true;
		if(intval($inscrit["obligatoire"])==-1)$obligatoire=false;
		array_push($inscriptions,array("type"=>"inscrit","id"=>$inscrit["id"],"code"=>$inscrit["code"],"intitule"=>$inscrit["intitule"],"credits"=>$inscrit["credits"],"obligatoire"=>$obligatoire,"evaluation_id"=>$inscrit["evaluation_id"]));
	}
	// Modules obligatoires à inscrire
	$req = "SELECT ";
	$req .= "session.id as session_id,";
	$req .= "modules.id,";
	$req .= "modules.code,";
	$req .= "modules.intitule,";
	$req .= "modules.credits";
	$req .= " FROM ";
	$req .= "`session`,`modules`";
	$req .= " WHERE ";
	$req .= "session.periode='$semestre_courant' AND ";
	$req .= "(modules.ecole LIKE '%-$ecole-%' OR modules.ecole = '$ecole') AND ";
	$where = array("modules.id");
	for($i=0;$i<count($inscriptions);$i++)
	{
		array_push($where,"modules.id!='".$inscriptions[$i]["id"]."'");
	}
	$where = implode(" AND ",$where);
	$req .= "$where AND ";
	$niveau="".$etudiant["niveau"];
	//while(strlen($niveau)<2)$niveau="0$niveau";
	$req .= "modules.obligatoire='$niveau' AND ";
	$req .= "session.module=modules.id ORDER BY modules.code ASC;";
	$missing = mysql_query($req) or die(mysql_error());
	$obligatoires = array();
	while($miss = mysql_fetch_array($missing))
	{
		array_push($inscriptions,array("type"=>"obligatoire","session_id"=>$miss["session_id"],"id"=>$miss["id"],"code"=>$miss["code"],"intitule"=>$miss["intitule"],"credits"=>$miss["credits"],"obligatoire"=>true,"evaluation_id"=>false));
	}
	// Stages
	$req = "SELECT * FROM stages WHERE etudiant='".$etudiant["id"]."' AND periode='$semestre_courant';";
	$stages = mysql_query($req);
	while($stage=mysql_fetch_array($stages))
	{
		array_push($inscriptions,array("type"=>"stage","id"=>$stage["id"],"credits"=>$stage["credits"],"lieu"=>$stage["lieu"],"debut"=>explode("-",$stage["debut"]),"fin"=>explode("-",$stage["fin"])));
	}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<?php 
			include("inc_css_thing.php");
		?>
		<title>Cursus <?php revision();?> / Contrat d'étude de <?php echo utf8_encode($etudiant["prenom"]." ".$etudiant["nom"]);?> <?php echo $periode['nom']?></title>
		<?php
			include("potajx/incpotajx.php");
		?>
	</head>
	<body>
		<div id="global">
			<?php
			$outil="coordination";
			include "barre_outils.php";
			$plus_nav_semestre = array(array("var"=>"id","val"=>$_GET['id']));
			include "inc_nav_sem.php";
			?>
			<table class="center"><tr><td>
				<h2>Contrat d'étude de <?php echo utf8_encode($etudiant["prenom"]." ".$etudiant["nom"]);?><?php echo "<a class=\"bouton\" href=\"vue_bulletin.php?nPeriode=$semestre_courant&id_etudiant=$id\">Voir bulletin</a>";?></h2>
			</td></tr></table>
			<input type="hidden" id="semestre_courant" value="<?php echo $semestre_courant;?>"/>
			<table id="contrat" class="center">
				<tr>
					<td>Code</td>
					<td>Intitulé</td>
					<td> </td>
					<td>Inscription</td>
					<td>Crédits</td>
				</tr>
			<?php
				$where = array("modules.id");
				$total = 0;
				$seuil = 34;
				//Modules
				for($i=0;$i<count($inscriptions);$i++)
				{
					if($inscriptions[$i]["type"]=="stage")
					{
						echo "<tr><td>";
						echo "STAGE";
						echo "</td><td>";
						$debut = $inscriptions[$i]["debut"][2]."/".$inscriptions[$i]["debut"][1]."/".$inscriptions[$i]["debut"][0];
						$fin = $inscriptions[$i]["fin"][2]."/".$inscriptions[$i]["fin"][1]."/".$inscriptions[$i]["fin"][0];
						echo $inscriptions[$i]["lieu"]." (du ".$debut." au ".$fin.")";
						echo "</td><td>";
						echo "<a class=\"bouton\" href=\"gestion_stages.php?stage_id=".$inscriptions[$i]["id"]."\">Éditer</a>";
						echo "</td><td>";
						echo intval($inscriptions[$i]["credits"])." cr";
						echo "</td></tr>";
						$total += intval($inscriptions[$i]["credits"]);
					}else{
						echo "<tr><td>";
						echo $inscriptions[$i]["code"];
						echo "</td><td>";
						echo utf8_encode($inscriptions[$i]["intitule"]);
						echo "</td><td>";
						echo "<a class=\"bouton\" href=\"edit_eval.php?eval=".$inscriptions[$i]["evaluation_id"]."\">Évaluation</a>";
						echo "</td><td>";
						if(!$inscriptions[$i]["obligatoire"])
						{
							echo "<a class=\"bouton\" href=\"javascript:desinscrire(".$inscriptions[$i]["evaluation_id"].",$id,$semestre_courant)\">Désinscrire</a>";
						}else if(!$inscriptions[$i]["evaluation_id"])
						{
							echo "<a class=\"bouton\" href=\"javascript:inscrire_obligatoire(".$inscriptions[$i]["session_id"].",$id,$semestre_courant)\">Inscrire</a>";
						}else{
							echo "Obligatoire";
						}
						echo "</td><td>";
						echo $inscriptions[$i]["credits"]." cr";
						echo "</td></tr>";
						$total += intval($inscriptions[$i]["credits"]);
						array_push($where,"modules.id!='".intval($inscriptions[$i]["id"])."'");
					}
				}
				//Tutorat
				if($etudiant["niveau"]>2)
				{
					echo "<tr><td>";
					echo "TUT";
					echo "</td><td>";
					echo "Tutorat";
					echo "</td><td>";
					echo "</td><td>";
					$total += credits_tutorat($etudiant["niveau"]);
					echo credits_tutorat($etudiant["niveau"])." cr";
					echo "</td></tr>";
				}
				//Total
				echo "<tr><td>";
				echo "</td><td>";
				echo "Total";
				echo "</td><td>";
				echo "</td><td>";
				echo $total." cr";
				echo "</td></tr>";
				echo "<tr><td>";
				echo "</td><td>";
				if($total<$seuil)
				{
					$req = "SELECT session.id,modules.code,modules.intitule,modules.credits FROM modules,session WHERE ";
					$req .= implode(" AND ",$where);
					$req .= " AND (modules.ecole LIKE '%-$ecole-%' OR modules.ecole = '$ecole')";
					$req .= " AND session.module=modules.id AND session.periode='$semestre_courant' AND modules.credits<=".($seuil-$total)." ORDER BY modules.code ASC;";
					//echo $req;
					$modules = mysql_query($req) or die(mysql_error());
					echo "<select id=\"session\">";
					while($module=mysql_fetch_array($modules))
					{
						echo "<option value=\"";
						echo $module["id"];
						echo "\">";
						echo $module["code"]." | ".utf8_encode($module["intitule"])." | ".$module["credits"]." cr";
						echo "</option>";
					}
					echo "</select>";
				}
				echo "</td><td>";
				if($total<$seuil) echo "<a class=\"bouton\" href=\"javascript:inscrire($id,$semestre_courant);\">Inscrire</a>";
				echo "</td></tr>";
			?>
			</table>
		</div>
	</body>
</html>