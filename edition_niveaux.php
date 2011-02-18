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

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<?php 
			include("inc_css_thing.php");
		?>
		<title>Cursus <?php echo revision();?> / Édition niveaux</title>
		
	</head>
	<body>
		<?php
			$_LIMELIGHT_PATH = "com/umxprime/limelight/";
			include_once($_LIMELIGHT_PATH."core/limelight.php");
		?>
		<div id="global">
			<?php
			$outil="niveaux";
			include("barre_outils.php");
			include("inc_nav_sem.php");
			?>
			<input type="hidden" id="semestre_courant" value="<?php echo $semestre_courant;?>"/>
			
			<table class="center">
				<tr><td><span id="ajxLoader"></span></td></tr>
				<tr><td>
					École
					<?php
						$listeEcoles = new HtmlFieldSelect();
						$listeEcoles->setFieldId("ecoles");
						$req = "SELECT id,nom FROM ecoles WHERE 1 ";
						if(!$droits[$_SESSION["auto"]]["voir_tous_sites"]) $req.="AND id='".$_SESSION["ecole"]."' ";
						$req.= ";";
						$res = mysql_query($req);
						while($ecole = mysql_fetch_array($res))
						{
							$listeEcoles->appendFieldOption($ecole["id"],$ecole["nom"]);
						}
						$listeEcoles->renderField();
					?>
					
					Cycle
					<?php
						$listeUtilisateurs = new HtmlFieldSelect();
						$listeUtilisateurs->setFieldId("cycles");
						$listeUtilisateurs->renderField();
					?>
					
					Filtre
					<?php
						$champsFiltre = new HtmlFieldInputText();
						$champsFiltre->setFieldId("filtre");
						$champsFiltre->renderField(); 
					?>
					<a class="bouton" href="javascript:gEBI('filtre').clearValue();changeCycle();">Effacer filtre</a>
					<a class="bouton" href="javascript:changeCycle();">Appliquer</a>
				</td></tr>
				<tr>
					<td id="liste">
					</td>
				</tr>
			</table>
		</div>
	</body>
</html>