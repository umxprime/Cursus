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
//on requiert les variables de connexion;
require("connect_info.php");
//puis la connexion standard;
require("connexion.php");
$outil="modules";
include("inc_sem_courant.php");

//trouver les modules ayant déjà une session dans ce semestre
$req = "SELECT session.*, modules.intitule, modules.id as id_module ";
			$req .="FROM session, modules ";
			$req .="WHERE session.semestre='".$semestre_courant."' AND modules.id=session.module ";
if($_SESSION['auto']=='p'){
			$req .="AND modules.enseignants LIKE '%".$_SESSION['username']."%';";
		}else if($_SESSION['auto']=='a'){
			$req .= ";";
		}else{
			$req="select id from etudiants where id <0;";
		}
//$req = "select session.*, module.intitule from session, modules where session.semestre = '".$periode['id']."' and modules.id=session.module ORDER BY modules.code";
//echo $req;
		$res = mysql_query($req);
$c = mysql_num_rows($res);

//echo "c======".$c."\n";
if ($c >0){
	$chaineNot = "Select * from modules where id !='";
	$n=0;
	$tablModule = "";
	while($session =mysql_fetch_array($res) ){
		if ($n> 0){
			$chaineNot .= " and id!='";
		}
		//echo $_SESSION['auto'];
		
		
			$tablModule .="<TR>\n<TD>\n";
			$tablModule .="<A HREF=\"gestion_modules.php?session=".$session["id"]."\">";
			$tablModule .=utf8_encode($session["intitule"])."</A>\n</TD>";
			$tablModule .="<TD><a href=\"edition_modules.php?id=".$session['id_module']."\">Modifier le module</TD></tr>";
		
		$chaineNot .= $session["id_module"]."'";
		$n++;
	}
	$chaineNot .= " AND (desuetude='0000-00-00' OR desuetude >'".$dateCourante."') ORDER BY code;";
	$resNot = mysql_query($chaineNot);
	$tablModule .="<TR>\n<TD>\n";
	$tablModule .="<A HREF=\"tutorats.php?session=".$session["id"]."&periode=".$periode['id']."\">";
	$tablModule .="Tutorat</A>\n</TD><td></td></tr>";
	//afficher les modules
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<?php include("inc_css_thing.php");	?>
		<title><?php echo $periode['nom']?></title>
	</head>
	<body>
		<div id="global">
			<?php include("barre_outils.php"); 
			//echo "test session".implode("|", $_SESSION);
			?>			
			<?php include("inc_nav_sem.php"); ?>
			<p>
				<table id="table_modules">
					<?php echo $tablModule;	?>
					</tr>
				</table>
			</p>
			<?php
			}
			else
			{
				$req = "select * from modules where desuetude='0000-00-00' or desuetude>'".$dateCourante."' ORDER BY code;";
				$resNot = mysql_query($req);
			}
			if ($_SESSION['auto']=="a")
			{
			?>
			<h2>Ajouter un module pour ce semestre</h2>
			<form id="formulaire" name="formulaire" action="ajouter_session.php" method="post">
				<input type="hidden" name="nPeriode" value="<?php echo $periode["id"]; ?>" >
				<table>
					<tr><td>
						Choisir le module à ajouter :
					</td></tr>
					<tr><td>
						<select id="module" name="module">
						<?php
							$n=0;
							while($resteModule = mysql_fetch_array($resNot)){
								//echo $resteModule['code']."\n";
								$l[$n]['val']=$resteModule["id"];
								$l[$n]['aff']=$resteModule["intitule"];
								$n++;
							}
							echo affiche_options($l,"",0);
						?>
						</select>
					</td></tr>
					<tr><td>
						Donner un titre sécifique à ce module pour ce semestre :
					</td></tr>
					<tr><td>
						<?php echo affiche_ligne("titre","",false); ?>
					</td></tr>
					<tr><td>
					<input type="submit" value="ajouter">
				</table>
			</form>
			<?php
			}
			?>
		</div>
	</body>
</html>
