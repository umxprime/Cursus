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
if($_SESSION['auto']!='a'){$id=$_SESSION['userid'];}
//$semestre_courant=4;
include("inc_sem_courant.php");
//echo "id= ".$id;
//echo "old_id= ".$_POST['old_id'];
$message_rec= "Inscrire aux modules obligatoires";
$ok_edit=($_SESSION['auto']=='a')?1:0;

if($ok_edit==1){
	$req = "select etudiants.id as eid,etudiants.nom as enom,etudiants.prenom as prenom,niveaux.niveau as sem, ";
	$req .= "session.id as session_id, modules.code as code_module ";
	$req .= "from etudiants, session,evaluations, modules, niveaux ";
	$req .="where niveaux.etudiant = etudiants.id AND niveaux.periode ='".$semestre_courant."' ";
	$req .="AND niveaux.niveau < 11 AND session.semestre='".$semestre_courant."' AND evaluations.session=session.id ";
	$req .= "AND modules.id = session.module AND etudiants.id=evaluations.etudiant";
	$req .=" order by niveaux.niveau,etudiants.id";
	echo "requete : ".$req."<br />\n";
	$res = mysql_query($req);
	$n_et=-1;
	$old_et = 0;
	$old_sem=0;
	while($arr=mysql_fetch_array($res)){
		if($old_sem !=$arr['sem']){
			$n_et=-1;
			$old_sem=$arr['sem'];
		}
		if($old_et != $arr['eid']){
			//echo implode(", ",$semestres[$arr['sem']*1][$n_et]['modules']);
			$n_et++;
			$old_et=$arr['eid'];
			//echo "|".$arr['sem']."|".$n_et."|".$arr['prenom']." ".$arr['enom']."|<br />\n";
			$semestres[$arr['sem']*1][$n_et]['id']= $arr['eid'];
		$semestres[$arr['sem']*1][$n_et]['nom']=$arr['prenom']." ".$arr['enom'];
		$semestres[$arr['sem']*1][$n_et]['modules']=array();
		$nmodules=0;
		$semestres[$arr['sem']*1][$n_et]['sessions']=array();
		}
		
		$semestres[$arr['sem']*1][$n_et]['modules'][$nmodules]=$arr['code_module'];
		$nmodules++;
		$semestres[$arr['sem']*1][$n_et]['sessions'][$nmodules]=$session_id;
	}
	if($_POST["action"]==$message_rec){
		$req = "select code,obligatoire, session.id as session_id from modules, session where obligatoire >0 AND session.module=modules.id AND session.semestre='".$semestre_courant."';";
		$res = mysql_query($req);
		while($obl = mysql_fetch_array($res)){
			$sem_obl = $obl['obligatoire']*1;
			for($n_et=0; $n_et<count($semestres[$sem_obl]); $n_et++){
				$etu=$semestres[$sem_obl][$n_et];
				if (!in_array($obl['code'],$etu['modules'])){

					$req = "insert into evaluations (id, session, etudiant) values ('','".$obl['session_id']."', ".$etu['id'].")";
					$retour=mysql_query($req);
					echo mysql_error($retour);
					echo "module ajout&eacute; pour : ".$etu['nom']." ".$req." <br />\n";
					$semestres[$sem_obl][$n_et]['modules'][]=$obl['code'];
				}
				
			}
		}
	}
	?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<head>
<?php 
include("inc_css_thing.php");
?>
<title>Gestion des modules obligatoires</title>
</head>
<body>
<form method="post" action="gestion_obligatoires.php" name="passages">
<table style="text-align: left; width: 704px; height: 32px;" border="1"
	cellpadding="2" cellspacing="2">
	<tbody>
	<tr>
			<th>Nom de l'&eacute;tudiant</th>
			<th>Modules</th>
		</tr>
	<?php $compteur=1;
	for ($sem= 1; $sem<11; $sem++){
			for ($n_et=0; $n_et<count($semestres[$sem]); $n_et++ ){?>
		

		
		
		<tr>
		<td style="width: 120px;"><?php echo $compteur.$semestres[$sem][$n_et]['nom']; ?></td>
		
			<td style="width: 280px;"><?php echo implode(", ",$semestres[$sem][$n_et]['modules']); ?></td>

		</tr>
		<?php $compteur++;
	}
} ?>
	</tbody>
</table>
<br />
<input type="submit" name="action" value="
<?php echo $message_rec; ?>" />

</form>


</body>
</html>
<?php
}
?>
