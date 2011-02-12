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
		<title>Cursus <?php echo revision();?> / Édition utilisateurs</title>
		<?php
			$_LIMELIGHT_PATH = "com/umxprime/limelight/";
			include_once($_LIMELIGHT_PATH."core/limelight.php");
		?>
	</head>
	<body>
		<div id="global">
			<?php
			$outil="utilisateurs";
			include("barre_outils.php");
			include("inc_nav_sem.php");
			?>
			<input type="hidden" id="semestre_courant" value="<?php echo $semestre_courant;?>"/>
			
			<table>
				<tr><td><span id="ajxLoader"></span></td></tr>
				<tr><td>
					Catégorie
					<?php
						$listeTypesUtilisateurs = new HtmlFieldSelect();
						$listeTypesUtilisateurs->setFieldId("categories");
						$listeTypesUtilisateurs->setFieldOptions(Array("etudiants","professeurs"),Array("Étudiants","Professeurs"));
						$listeTypesUtilisateurs->renderField();
					?>
					Filtre
					<?php
						$champsFiltre = new HtmlFieldInputText();
						$champsFiltre->setFieldId("filtre");
						$champsFiltre->renderField(); 
					?>
					<a class="bouton" href="javascript:gEBI('filtre').clearValue();nouvelleEntree();">Effacer filtre</a>
					<a class="bouton" href="javascript:nouvelleEntree();">Appliquer</a>
				</td></tr>
				<tr><td>
					
					<a class="bouton" href="javascript:nouvelleEntree();">Nouvel utilisateur</a>
					Nom
					<?php
						$listeUtilisateurs = new HtmlFieldSelect();
						$listeUtilisateurs->setFieldId("utilisateurs");
						$listeUtilisateurs->renderField();
					?>
					<a class="bouton" href="javascript:chargeUtilisateur(gVBI('utilisateurs'));">Recharger utilisateur</a>
					
					
					<!--<a href="javascript:supprimerUtilisateur()" onclick="this.blur()">Supprimer</a> -->
				</td></tr>
				<tr><td>
					Nom
					<?php
						$champsNomUtilisateur = new HtmlFieldInputText();
						$champsNomUtilisateur->setFieldId("nom");
						$champsNomUtilisateur->renderField();
					?>
					Prénom
					<?php
						$champsPrenomUtilisateur = new HtmlFieldInputText();
						$champsPrenomUtilisateur->setFieldId("prenom");
						$champsPrenomUtilisateur->renderField();
					?>
				</td></tr>
				<tr><td>
					Log
					<?php
						$champsLogUtilisateur = new HtmlFieldInputText();
						$champsLogUtilisateur->setFieldId("log");
						$champsLogUtilisateur->renderField();
					?>
					Type
					<?php
						$listeTypesLog = new HtmlFieldSelect();
						$listeTypesLog->setFieldId("logtype");
						$listeTypesLog->setFieldOptions(Array(0,1,2),Array("pnom","prenomnom","Personnalisé"));
						$listeTypesLog->renderField();
					?>
				</td></tr>
				<tr><td>
					Mot de passe
					<?php
						$champsMotDePasse = new HtmlFieldInputText();
						$champsMotDePasse->setFieldId("passw");
						$champsMotDePasse->renderField();
					?>
					<a class="bouton" href="javascript:gEBI('passw').genererMotDePasse();" onclick="this.blur();" >Générer un mot de passe</a>
				</td></tr>
				<tr><td>
					<table id="champsEtudiants">
						<tr><td>
							Niveau 
							<?php
								$listeNiveaux = new HtmlFieldSelect();
								$listeNiveaux->setFieldOptions(array(0,1,2,3,4,5,6,7,8,9,10,11),array("Aucun","1","2","3","4","5","6","7","8","9","10","Ancien"));
								$listeNiveaux->setFieldId("niveau");
								$listeNiveaux->renderField();
							?>
						</td></tr>
						<tr><td>
							Cycle 
							<?php
								$listeCycles = new HtmlFieldSelect();
								$listeCycles->setFieldId("cycle");
								$listeCycles->renderField();
							?>
						</td></tr>
						<tr><td>
							Crédits de base 
							<?php
								$champsCredits = new HtmlFieldInputText();
								$champsCredits->setFieldId("credits");
								$champsCredits->setFieldText("0");
								$champsCredits->renderField();
							?>
						</td></tr>
					</table>
					<table id="champsProfesseurs">
						<tr><td>
							Droits 
							<?php
								$listeAutos = new HtmlFieldSelect();
								$listeAutos->setFieldId("auto");
								$listeAutos->setFieldOptions(array("p","coord_memoire","coord_semestre","coord","admin","super"),array("Professeur","Suivi Mémoire","Coordonateur de semestre","Coordonateur pédagogique","Administrateur","Tous pouvoirs"));
								$listeAutos->renderField();
							?>
						</td></tr>
						<tr><td>
							École
							<?php
								$listeEcoles = new HtmlFieldSelect();
								$listeEcoles->setFieldId("ecole");
								$req = "SELECT id,nom FROM ecoles;";
								$res = mysql_query($req);
								while($ecole = mysql_fetch_array($res))
								{
									$listeEcoles->appendFieldOption($ecole["id"],$ecole["nom"]);
								}
								$listeEcoles->renderField();
							?>
						</td></tr>
					</table>
				</td></tr>
				<tr><td>
					<a class="bouton" id="valider" href="javascript:valider();" name="Valider">Valider</a>
				</td></tr><tr><td>
					
				</td></tr>
			</table>
		</div>
	</body>
</html>