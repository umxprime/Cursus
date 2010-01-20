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
//echo $idd_session."|";
//on requiert les variables de connexion;
require("connect_info.php");
//puis la connexion standard;
//echo $idd_session."|";
require("connexion.php");
//echo $idd_session."|";
include("fonctions.php");
//echo $idd_session."|";
include("inc_sem_courant.php");
include("regles_utilisateurs.php");
(!$_GET['session'])?$idd_session = $_POST['session']:$idd_session = $_GET['session'];
if(!$idd_session){
	$idd_session=$_SESSION['lasession'];
}else{
	session_register('lasession');
	$_SESSION['lasession']=$idd_session;
}
$req = "SELECT * FROM session where id = '".$idd_session."';";
//$req = "SELECT * FROM session, periodes, modules, evaluations, "
$res = mysql_query($req);
$session = mysql_fetch_array($res);
$req2 = "SELECT * FROM periodes where id = '".$session['periode']."';";
$res2 = mysql_query($req2);
$semestre = mysql_fetch_array($res2);
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
	$req = "SELECT evaluations.*, etudiants.nom, etudiants.prenom, etudiants.mail, etudiants.id as id_etudiant ";
	$req .= "FROM evaluations, etudiants WHERE evaluations.session='".$idd_session."' and etudiants.id=evaluations.etudiant ";
	$req .= "ORDER BY etudiants.nom";
	$resEvals = mysql_query($req);
	echo mysql_error();
	$nres = mysql_num_rows($resEvals);
	//echo $req;
	if ($nres>0){
		//echo "nres :".$nres;
		$destsMail="";
		$tablEvals ="<table><tr>\n<th>Etudiant</th>";
		$tablEvals .= "\t<th>Annuler <br/>inscription</th>\n";
		for($i=1; $i<=10;$i++){
			$tablEvals .= "\t<th><a href=\"javascript:presence($i)\">Cours<br/>#$i</a></th>\n";
		}
		$tablEvals .= "\t<th>Session <br/>#1</th>\n";
		$tablEvals .= "\t<th>Rattrapage</th>\n";
		$tablEvals.= "</tr>";

		$neval = 1;
		while($eval = mysql_fetch_array($resEvals)){
			//			$req = "SELECT * FROM etudiants WHERE id='".$eval['etudiant']."';";
			//echo "requete : ".$req."\n";
			//			$res= mysql_query($req);
			//			$etudiant = mysql_fetch_array($res);
			$tablEvals .= "<tr>\n\t<td>";
			$tablEvals .= utf8_encode($eval['prenom']). " ".utf8_encode($eval['nom']);
			$tablEvals .= "\n\t</td>\n\t<td>";
			$destsMail .= $eval['prenom']." ".$eval['nom'];
			$destsMail .= "<".$eval["mail"].">";
			if($neval<$nres){$destsMail .=", ";}
			//desinscription de l'étudiant
			$tablEvals .= "<a href=\"desinscrire.php?eval=".$eval['id']."\">désinscrire</a></td>\n<td>";
			$sauf[] = $eval['id_etudiant'];
			for($d=1;$d<=10;$d++){
				$tablEvals .= "<input id=\"presence_$neval-$d\" name=\"presences".$eval['id']."[]\" value=\"".$d."\" type=\"checkbox\" ";
				if (strpos($eval['presences'], "".$d)){
					$tablEvals .= "checked=\"checked\"";
				}
				$tablEvals .= ">\n";
				$tablEvals .="\t</td>\n\t<td>\n";
				//echo $d."\n";
			}
			//$tablEvals .="</td>\n";
			$tablEvals .="<a href = \"edit_eval.php?eval=".$eval['id']."&nPeriode=$semestre_courant\">";
			//$tablEvals .="<a href = \"edit_eval.php?eval=".$eval['id']."\">";
			$tablEvals .= (empty($eval['note_1']) or $eval['note_1']=='-')?"Éditer":$eval['note_1'];
			$tablEvals .= "</a></td>\n";
			$tablEvals .="<td>";
			if(!empty($eval['note_1'])){
				if(strpos("__efEF",$eval['note_1'])){
					$tablEvals .="<a href = \"edit_eval.php?eval=".$eval['id']."&nPeriode=$semestre_courant\">";
					//$tablEvals .="<a href = \"edit_eval.php?eval=".$eval['id']."\">";
					$tablEvals.= (empty($eval['note_2']) or $eval['note_2']=='-')?"&eacute;diter":$eval['note_2'];
					$tablEvals .= "</a>";
				}
			}
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
	</head>
	<body>
		<div id="global">
		<?php
			include("barre_outils.php") ;
		?>
		<table class="center"><tr><td>
		<h2><?php echo utf8_encode($module['intitule'])." / <a href=\"sessions.php?nPeriode=".$session["periode"]."\">".$semestre['nom']."</a>"; ?></h2>
		<h2>Inscrire des étudiants à ce module</h2>
		<form id="formulaire" action="ajouter_etudiant.php" method="post">
		<p>
			Nom de l'etudiant :
			<select id="etudiant" name="etudiant">
				<?php
					$session = $_GET["session"];
					$req = "SELECT session.id as session_id, session.module as session_module, modules.id as module_id, modules.ecole as module_ecole, modules.obligatoire FROM session, modules WHERE session.id='$idd_session' AND session.module=modules.id;";
					//echo $req;
					$res = mysql_fetch_array(mysql_query($req));
					$ecole = $res["module_ecole"];
					//echo $ecole;
					echo liste_etudiants($sauf, $connexion, $semestre_courant, $ecole);
				?>
			</select>
			<input type="hidden" name="session" value="<?php echo $idd_session; ?>"/>
			<input type="submit" value="inscrire" />
		</p>
		<?php
		if ($res["obligatoire"]>0){
			?>
		<p>Ce module est obligatoire pour les étudiants en semestre <?php echo intval($res["obligatoire"]);?> :
		<a href="reg_module_obligatoire.php?id=<?php echo $res["session_id"]?>&nPeriode=<? echo $semestre_courant;?>">inscrire les étudiants</a>.</p>
			<?php }?>
		</form>
		
		<?php
			if($nres>0){
		?>
		<form id="fpresences" action="reg_presences.php" method="post">
			<?php
			echo $tablEvals;
			?>
			<fieldset style="border-style:none;">
					<input type="hidden" name="session" value="<?php echo $idd_session; ?>"/>
					<input type="submit" value="valider les présences"/>
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
		<script type="text/javascript">
			function presence(p)
			{
				var neval = document.getElementById("neval").value;
				for(var i=1;i<=neval;i++)
				{
					var value = document.getElementById("presence_"+i+"-"+p).checked;
					if (value)
					{
						document.getElementById("presence_"+i+"-"+p).checked=false;
					}
					else
					{
						document.getElementById("presence_"+i+"-"+p).checked=true;
					}
				}
			}
		</script>
		</td></tr></table>
		</div>
	</body>
</html>
