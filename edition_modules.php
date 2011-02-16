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

$id = ($_POST['id'])?$_POST['id']:$_GET['id'];
/*
sécurité : édition interdite aux étudiants
*/ 
if($_SESSION["auto"]=="e"){
	header("Location: etudiants.php");
	exit();
}
//
if(!$id or $id<0 or $id==$_POST["old_id"]){
	/*
	sécurité : édition réservée aux admins
	*/
	//if($_SESSION["auto"]!="a"){
	if($droits[$_SESSION["auto"]]["edit_modules_adv"]!=true){
		header("Location: sessions.php");
		exit();
	}
	//
	$cols = liste_colonnes("modules");
	for($nc=0;$nc<count($cols);$nc++){
		$nom = $cols['nom'];
		$ligne[$nom]=(empty($_POST[$nom]))?-1:$_POST[$nom];
	}
}
else 
{
	$requete = "SELECT * FROM modules WHERE modules.id = '$id';";
	$resultat = mysql_query($requete, $connexion);
	$ligne = mysql_fetch_array($resultat);
	/*
	sécurité : édition limitée aux enseignants du module et aux admins
	*/
	$valid = strlen(stripos($ligne["enseignants"],$_SESSION["username"]));
	//if(!$valid and $_SESSION["auto"]!="a"){
	if(!$valid and $droits[$_SESSION["auto"]]["edit_modules_adv"]!=true){
		header("Location: sessions.php");
		exit();
	}
	//
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<?php include("inc_css_thing.php");	?>
		<title>Cursus <?php revision();?> / Édition de module : <?php echo utf8_encode($ligne['intitule']) ?></title>
		<?php include("potajx/incpotajx.php");	?>
	</head>
<body>
	<div id="global">
		<?php
		include("barre_outils.php"); 	
		$plus_nav_semestre[0] = array("var"=>"id","val"=>$_GET["id"]);
		include("inc_nav_sem.php");
		?>
		<input id="semestre_courant" type="hidden" value="<?php echo $semestre_courant;?>"/>
		<input id="id" type="hidden" value="<?php echo $id;?>"/>
		<div id="contenu">
			<form id="formulaire" action="reg_module.php?nPeriode=<?php echo $semestre_courant;?>" method="post">
			<table style="text-align: left; width: 626px; height: 228px;" border="1" cellpadding="2" cellspacing="2">
				<tbody>
					<?php 
						$peut_ajouter=($droits[$_SESSION['auto']]['edit_modules_adv'])?1:0;
						//$peut_ajouter=($_SESSION['auto']=='a')?1:0;
						if($peut_ajouter)
						{
					?>
					<tr>
						<td style="width: 70px;">Module</td>
						<td colspan="7" rowspan="1">
							<select id="module">
								<option value="-1">Nouveau</option>
								<?php
									
									$req = "SELECT id,intitule,code,desuetude,credits FROM modules WHERE desuetude='0000-00-00' or desuetude>'".$periode["debut"]."' ORDER BY code ASC;";
									$modules = mysql_query($req);
									$inscrit = array();
									$actif = array();
									$desuet = array();
									while($module=mysql_fetch_array($modules))
									{
										$req="SELECT session.id FROM session,modules WHERE session.periode='$semestre_courant' AND session.module='".$module["id"]."'";
										$session=mysql_num_rows(mysql_query($req));
										if($session)
										{
											array_push($inscrit,$module);
										}else
										if($module["desuetude"]!="0000-00-00")
										{
											array_push($desuet,$module);
										}else{
											array_push($actif,$module);
										}
									}
									echo "<optgroup label=\"Modules inscrits à cette période\">";
									for($i=0;$i<count($inscrit);$i++)
									{
										$module=$inscrit[$i];
										echo "<option value=\"".$module["id"]."\" ";
										if($_GET["id"]==$module["id"]) echo " selected ";
										echo ">[".$module["code"]."/".$module["credits"]."cr.] ".utf8_encode($module["intitule"]);
										if($module["desuetude"]!="0000-00-00")echo " (clos depuis ".$module["desuetude"].")";
										echo "</option>";
									}
									echo "</optgroup><optgroup label=\"Modules actifs\">";
									for($i=0;$i<count($actif);$i++)
									{
										$module=$actif[$i];
										echo "<option value=\"".$module["id"]."\" ";
										if($_GET["id"]==$module["id"]) echo " selected ";
										echo ">[".$module["code"]."/".$module["credits"]."cr.] ".utf8_encode($module["intitule"]);
										if($module["desuetude"]!="0000-00-00")echo " (clos depuis ".$module["desuetude"].")";
										echo "</option>";
									}
									echo "</optgroup><optgroup label=\"Modules désuets\">";
									for($i=0;$i<count($desuet);$i++)
									{
										$module=$desuet[$i];
										echo "<option value=\"".$module["id"]."\" ";
										if($_GET["id"]==$module["id"]) echo " selected ";
										echo ">[".$module["code"]."/".$module["credits"]."cr.] ".utf8_encode($module["intitule"]);
										if($module["desuetude"]!="0000-00-00")echo " (clos depuis ".$module["desuetude"].")";
										echo "</option>";
									}
									echo "</optgroup>";
								?>
							</select> 
							<?php 
							//echo selecteurModules("edition_modules.php?nPeriode=$semestre_courant","modules","id","intitule","id",$connexion,$ligne['id'],0,$peut_ajouter, "code",$semestre_courant);
							?>
						</td>
					</tr>
					<?php } ?>
					<tr>
						<?php if($_GET["id"]!=-1){?>
						<td style="width: 70px;">Sessions</td>
						<td colspan="7" rowspan="1">
							<?php
								$req = "SELECT periodes.nom,periodes.annee,periodes.id,session.id as session_id FROM session,periodes WHERE session.module='$id' AND session.periode=periodes.id;";
								$res = mysql_query($req);
								//echo $req;
								$periodes = Array();
								$used = Array(1);
								while($periode = mysql_fetch_array($res))
								{
									if ($droits[$_SESSION['auto']]["ajouter_module"])
									{
										array_push($periodes,$periode["annee"].", ".$periode["nom"]." <a href=\"gestion_modules.php?session=".$periode["session_id"]."&nPeriode=".$periode["id"]."\">Voir</a>/<a href=\"javascript:supprimer_session(".$periode["session_id"].");\">Retirer</a>");
									} else {
										array_push($periodes,$periode["annee"].", ".$periode["nom"]." <a href=\"gestion_modules.php?session=".$periode["session_id"]."&nPeriode=".$periode["id"]."\">Voir</a>");
									}
									array_push($used,"periodes.id!='".$periode["id"]."'");
								}
								echo implode("<br/>",$periodes);
								//echo implode(" AND ",$used);
								$req = "SELECT periodes.id,periodes.annee,periodes.nom FROM periodes WHERE ".implode(" AND ",$used)." AND activite='14' ORDER BY periodes.annee DESC;";
								$res = mysql_query($req);
								if ($droits[$_SESSION['auto']]["ajouter_module"])
								{
							?>
							<br/>
							<select id="periode">
							<?php
								 while($periode=mysql_fetch_array($res))
								{
									echo "<option value=\"".$periode["id"]."\" ";
									echo ">".$periode["annee"].", ".$periode["nom"]."</option>";
								} 
							?>
							</select> <a href="javascript:inscrire_session();">inscrire</a>
							<?php
								}
								}
							?>
						</td>
					</tr>
				<tr>
					<td style="width: 70px;">Intitulé</td>
					<td colspan="5" rowspan="1">
					<input id="intitule" type="text" size="30" value="<?php echo htmlentities($ligne["intitule"]);?>"/>
					</td>
					<td>Code</td>
					<td>
				<?php 
					if($peut_ajouter){
					?>
					<input id="code" type="text" size="8" value="<?php echo htmlentities($ligne["code"]);?>"/>
					<?php
					}else{
						echo utf8_encode($ligne['code']);
					?>
					<input id="code" type="hidden" value="<?php echo htmlentities($ligne["code"]);?>"/>
					<?php
					}
				?>
			
			      </td>
			    </tr>
			    <tr>
			      <td colspan="8" rowspan="1";">
			      	<textarea id="description" rows="8" cols="100"><?php echo htmlentities($ligne["description"]);?></textarea>
			      </td>
			    </tr>
			    <tr>
			      <td style="width: 70px;">Jour</td>
			      <td style="width: 52px;">
					<select id="jour">
						<?php
						$jours= Array('lundi','mardi','mercredi','jeudi','vendredi','samedi','atypique');
						for ($n=0; $n<count($jours); $n++)
						{
							echo "<option value=\"".$jours[$n]."\" ";
							if($jours[$n]==$ligne["jour"]) echo " selected ";
							echo ">".$jours[$n]."</option>";
						}
						?>
					</select>
			      </td>
			      <td style="width: 70px;">Début</td>
			      <td style="width: 78px;">
			      	<select id="debut">
			      		<?php
			      			for ($n=8; $n<=18; $n++)
			      			{
			      				echo "<option value=\"".$n."\" ";
								if($n==$ligne["debut"]) echo " selected ";
								echo ">".$n." h.</option>";
			      			} 
			      		?>
			      	</select>
			      </td>
			      <td style="width: 54px;">Fin</td>
			      <td style="width: 83px;">
			      	<select id="fin">
			      		<?php
			      			for ($n=8; $n<=18; $n++)
			      			{
			      				echo "<option value=\"".$n."\" ";
								if($n==$ligne["fin"]) echo " selected ";
								echo ">".$n." h.</option>";
			      			} 
			      		?>
			      	</select>
			      </td>
			      <td style="width: 105px;">Nombre de séances</td>
			      <td style="width: 58px;">
			      	<select id="seances">
			      		<?php
			      		for ($n=1; $n<=10; $n++)
			      			{
			      				echo "<option value=\"".$n."\" ";
								if($n==$ligne["seances"]) echo " selected ";
								echo ">".$n."</option>";
			      			}  
			      		?>
			      	</select>
			      </td>
			    </tr>
			    <tr>
			      <td style="width: 70px;">Crédits</td>
			      <td style="width: 52px;">
			      	<select id="credits">
			      		<?php
			      		for ($n=1; $n<=30; $n++)
			      			{
			      				echo "<option value=\"".$n."\" ";
								if($n==$ligne["credits"]) echo " selected ";
								echo ">".$n."</option>";
			      			}  
			      		?>
			      	</select>
			      </td>
			      <td style="width: 70px;">Obligatoire </td>
			      <td style="width: 78px;">
			      	<select id="obligatoire">
			      		<option value="-1">aucun</option>
			      		<?php
			      		for ($n=1; $n<=10; $n++)
			      			{
			      				echo "<option value=\"".$n."\" ";
								if($n==$ligne["obligatoire"]) echo " selected ";
								echo ">Semestre ".$n."</option>";
			      			}  
			      		?>
			      	</select>
			      </td>
			      <td style="width: 54px;"></td>
			      <td style="width: 83px;"></td>
			      <td style="width: 105px;">Désuétude (aaaa-mm-jj)</td>
			      <td style="width: 58px;"><input id="desuetude" type="text" size="8" value="<?php echo htmlentities($ligne["desuetude"]);?>"/></td>
			    </tr>
			    <tr>
			      <td>Pré-requis</td>
			      <td colspan="5" rowspan="1" style="width: 78px;">
			      <input id="pre_requis" type="text" size="60" value="<?php echo htmlentities($ligne["pre_requis"]);?>"/>
			      </td>
			      <td style="width: 105px;">
					<?php echo selecteurObjets("","modules","ajout_pre_requis","code","code",$connexion,$ligne['code'],0,0,"code"); ?>
					</td>
			      <td style="width: 58px;"><a href="javascript:ajout_pre_requis();">Ajouter un pré requis</a></td>
			    </tr>
			    <tr>
			      <td style="width: 70px;">Enseignants</td>
			      <td colspan="5" rowspan="1" style="width: 52px;">
			      <input id="enseignants" type="text" size="60" value="<?php echo htmlentities($ligne["enseignants"]);?>"/>
			      </td>
			      <td style="width: 105px;"><?php echo selecteurObjets("","professeurs","ajout_prof","nom_complet","nom_complet",$connexion,"",0,0,"prenom"); ?>
					</td>
			      <td style="width: 58px;"><a href="javascript:ajout_prof();">Ajouter un enseignant</a></td>
			    </tr>
			    
			    
			    <tr>
			      <td colspan="2" rowspan="1" style="width: 70px;">
			      Mode d'évaluation
			      </td>
			      <td colspan="6" rowspan="1" style="width: 70px;">
			      	<textarea id="evaluation" rows="5" cols="80"><?php echo htmlentities($ligne["evaluation"]);?></textarea>
			      </td>
			    </tr>
			   
			  </tbody>
			</table>
			<fieldset>
				<?php
				 	
				    echo "<div class=\"selecteur_ecole\">Sites : ";
				    $req = "SELECT * FROM ecoles";
				    $ecoles = mysql_query($req);
				    while($ecole=mysql_fetch_array($ecoles))
				    {
				    	$id = $ecole["id"];
				    	?>
				    	<input id="ecole_<?php echo $ecole["id"]?>" type="checkbox" <?php echo strstr($ligne["ecole"],"-$id-")?"checked":"";?>/>
				    	<?php 
				    	echo $ecole["nom"];
				    }
				    /*
				    if($peut_ajouter){
				    echo selecteurObjets($src,"ecoles", "ecole","nom", "id", $connexion, $ligne["id_ecole"], 0, 0, "nom");}
				    else{
				    	?>
							<input type="hidden" name="ecole" value="<?php echo $ligne["id_ecole"]?>"/>
						<?php
				    	echo $ligne['nom_ecole'];
				    }
				    */
					echo "\n</div>";
				 
					?>
				<input type="hidden" name="old_id" value="<?php echo ($ligne["id"])?$ligne["id"]:-1; ?>"/>
				<ul style="display:inline-block;list-style:none;">
					<li><a href="javascript:submit();">Valider</a></li>
					<li><span id="ajx_loader" class="displaynone"></span></li>
				</ul>
			</fieldset>
			</form>
		</div>
	</div>
</body>
</html>