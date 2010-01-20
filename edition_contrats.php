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
	$outil="coordination";
	include("inc_sem_courant.php");
	include("regles_utilisateurs.php");
	if ($_SESSION['auto']=="e")	header("location:login.php");
	//if (!$droits[$_SESSION['auto']]['edit_contrats'])
	$id = $_GET["id"];
	$req = "SELECT "; 
	$req .= "modules.id,";
	$req .= "modules.code,";
	$req .= "modules.intitule,";
	$req .= "modules.credits,";
	$req .= "evaluations.id as evaluation_id";
	$req .= " FROM ";
	$req .= "`evaluations`,`session`,`modules`";
	$req .= " WHERE ";
	$req .= "evaluations.etudiant='$id' AND ";
	$req .= "evaluations.session=session.id AND ";
	$req .= "session.periode='$semestre_courant' AND ";
	$req .= "session.module=modules.id ORDER BY modules.code ASC;";
	$inscrits = mysql_query($req) or die(mysql_error());
	$inscriptions = array();
	while($inscrit = mysql_fetch_array($inscrits))
	{
		array_push($inscriptions,array("id"=>$inscrit["id"],"code"=>$inscrit["code"],"intitule"=>$inscrit["intitule"],"credits"=>$inscrit["credits"],"evaluation_id"=>$inscrit["evaluation_id"]));
	}
	$req = "SELECT etudiants.nom,etudiants.prenom,niveaux.niveau FROM etudiants,niveaux WHERE etudiants.id='".$_GET["id"]."' AND niveaux.etudiant=etudiants.id AND niveaux.periode='$semestre_courant';";
	echo $req;
	$etudiant = mysql_fetch_array(mysql_query($req)) or die(mysql_error());
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<?php 
			include("inc_css_thing.php");
		?>
		<title>Cursus <?php revision();?> / Contrat d'étude de <?php echo utf8_encode($etudiant["nom"]." ".$etudiant["prenom"]);?> <?php echo $periode['nom']?></title>
		<?php
			include("potajx/incpotajx.php");
		?>
	</head>
	<body>
		<div id="global">
			<?php include("barre_outils.php"); ?>
			<?php include("inc_nav_sem.php"); ?>
			<input type="hidden" id="semestre_courant" value="<?php echo $semestre_courant;?>"/>
			<table id="contrat" class="center">
				<tr>
					<td>Code</td>
					<td>Intitulé</td>
					<td>Désinscription</td>
					<td>Crédits</td>
				</tr>
			<?php
				$where = array("modules.id");
				$total = 0;
				$seuil = 30;
				//Modules
				for($i=0;$i<count($inscriptions);$i++)
				{
					echo "<tr><td>";
					echo $inscriptions[$i]["code"];
					echo "</td><td>";
					echo utf8_encode($inscriptions[$i]["intitule"]);
					echo "</td><td>";
					echo "<a href=\"javascript:desinscrire(".$inscriptions[$i]["evaluation_id"].",$id,$semestre_courant)\">Désinscrire</a>";
					echo "</td><td>";
					echo $inscriptions[$i]["credits"]." cr";
					echo "</td></tr>";
					$total += intval($inscriptions[$i]["credits"]);
					array_push($where,"modules.id!='".intval($inscriptions[$i]["id"])."'");
				}
				//Tutorat
				echo "<tr><td>";
				echo "TUT";
				echo "</td><td>";
				echo "Tutorat";
				echo "</td><td>";
				echo "</td><td>";
				$total += credits_tutorat($etudiant["niveau"]);
				echo credits_tutorat($etudiant["niveau"])." cr";
				echo "</td></tr>";
				//Total
				echo "<tr><td>";
				echo "</td><td>";
				echo "Total";
				echo "</td><td>";
				echo "</td><td>";
				echo $total." cr";
				echo "</td></tr>";
				echo "<tr><td>";
				if($total<$seuil) echo "<a href=\"javascript:inscrire($id,$semestre_courant);\">Inscrire</a>";
				echo "</td><td>";
				if($total<$seuil)
				{
					$req = "SELECT session.id,modules.code,modules.intitule,modules.credits FROM modules,session WHERE ";
					$req .= implode(" AND ",$where);
					$req .= " AND modules.desuetude='0000-00-00' AND session.module=modules.id AND session.periode='$semestre_courant' AND modules.credits<=".($seuil-$total)." ORDER BY modules.code ASC;";
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
				echo "</td></tr>";
			?>
			</table>
		</div>
	</body>
</html>