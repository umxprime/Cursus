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

include("lesotho.php");
include("fonctions.php");
//on requiert les variables de connexion;
require("connect_info.php");
//puis la connexion standard;
require("connexion.php");
$outil="niveaux";
if(!$droits[$_SESSION['auto']]["edit_niveaux"]){$id=$_SESSION['userid'];}
include("inc_sem_courant.php");
include("regles_utilisateurs.php");
//echo "id= ".$id;
//echo "old_id= ".$_POST['old_id'];
$message_rec= "Valider les modifications";
$ok_edit=($droits[$_SESSION['auto']]["edit_niveaux"])?1:0;
//echo "ok_edit : ".$ok_edit;
(isset($_POST['cycle']))?($cycle=$_POST['cycle']):((isset($_GET['cycle']))?($cycle=$_GET['cycle']):($cycle=1));
$req = "SELECT * FROM cycles where id=".$cycle.";";
$res = mysql_query($req);
$arr_cycle = mysql_fetch_array($res);
if($ok_edit==1){
	$req = "select etudiants.id,etudiants.nom,etudiants.prenom, niveaux.niveau, niveaux.id as idniv from etudiants, niveaux";
	$req .=" where niveaux.periode='".$semestre_courant."' and niveaux.niveau < 11";
	$req .=" and niveaux.cycle=".$cycle;
	$req .=" and etudiants.id=niveaux.etudiant and etudiants.periode_sortie<1 ";
	$req .=" order by niveaux.niveau,etudiants.nom";
	//echo $req;
	$res = mysql_query($req);
	$err  = mysql_error();
	//echo $err;
	if (mysql_num_rows($res)<3){
		echo "<br />creation des niveaux...";
		$req2 = "insert into niveaux (periode, etudiant, niveau, cycle)";
		$req2 .=" select periodes.id, etudiants.id, niveaux.niveau, niveaux.cycle";
		$req2 .=" from periodes, etudiants, niveaux";
		$req2 .=" where periodes.id = '".$semestre_courant."' and etudiants.periode_sortie<1 and niveaux.cycle=$cycle and etudiants.id=niveaux.etudiant and niveaux.periode=".($semestre_courant-1).";";
		echo "<br />".$req2;
		$res2= mysql_query($req2);
		echo mysql_error();
		$res = mysql_query($req);
		//$tempreq = "SELECT evaluations.*, session.* FROM evaluations, session WHERE evaluations.etudiant=585 and evaluations.session=session.id and session.semestre=22";
	}
	$n_et = 0;
	while($arr=mysql_fetch_array($res)){
		$etudiants[$n_et]['nom']=$arr['prenom']." ".$arr['nom'];
		$etudiants[$n_et]['semestre']=$arr['niveau'];
		$etudiants[$n_et]['idniv']=$arr['idniv'];
		$etudiants[$n_et]['id']=$arr['id'];
		$etudiants[$n_et]['cycle']=$arr['cycle'];
		$n_et++;
	}
	//print_r($etudiants);

	if($_POST["action"]==$message_rec)
	{
		for($n_et = 0; $n_et<count($etudiants); $n_et++)
		{
			//echo $_POST["etudiant_".$n_et]."<br/>";
			$arrData = explode("_",$_POST["etudiant_".$n_et]);
			$idniv=$arrData[1];
			$newSem = $arrData[0];
			//echo $newSem;
			if ($newSem != $etudiants[$n_et]['semestre'])
			{
				$req = "update niveaux set niveau='".$newSem."' where id ='".$idniv."';";
				$res = mysql_query($req);
				//echo $req;
				echo mysql_error();
				if($today<=$periode['fin'] and $today >= $periode['debut']){
					$req = "update etudiants set semestre='".$newSem."' where id ='".$etudiants[$n_et]['id']."';";
					$res=mysql_query($req);
					echo mysql_error();
				}
				//echo "semestre chang&eacute; pour : ".$etudiants[$n_et]['nom']." <br />\n";
				$etudiants[$n_et]['semestre']=$_POST["etudiant_".$n_et];
			}
				
		}
	}
	?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
	<?php include("inc_css_thing.php"); echo "\n"; ?>
	<title>Cursus <?php revision();?> / Niveaux des étudiants pour le cycle : <?php echo utf8_encode($arr_cycle["nom"])?></title>
	<style type="text/css">
	<?php 
	include("cursusn.css"); 
	?>
	</style>
</head>
<body>
<div id="global">
<?php 
include("barre_outils.php") ; 
$plus_nav_semestre=array(array('var'=>'cycle', 'val'=>$cycle));
include("inc_nav_sem.php");
echo "<form method=\"post\" action=\"passages_niveaux.php\" id=\"formulaire\">";
if($droits[$_SESSION["auto"]]["edit_tous_niveaux"])
{
	echo selecteur_cycle($conn, $cycle,"cycle", "passages_niveaux.php",0);
} else
{
	echo selecteur_cycle($conn, $cycle,"cycle", "passages_niveaux.php",$_SESSION['ecole']);
}
echo "<input type=hidden name=\"nPeriode\" value=\"".$semestre_courant."\"/>";

echo "</form>";
?>

<form method="post" action="passages_niveaux.php" id="passages">
<table style="text-align: left; width: 704px; height: 32px;" border="1"
	cellpadding="2" cellspacing="2">
	<tbody>
		<tr>
			<th>Nom de l'étudiant</th>
			<?php for($i=$arr_cycle['semestre_debut'];$i<=$arr_cycle['semestre_fin'];$i++)
			echo "<th>S".$i."</th>";
			?>
			<th>Cycle</th>
		</tr>
		<?php for($n_et = 0; $n_et<count($etudiants); $n_et++){?>
		<tr>
			<td style="width: 272px;"><?php echo utf8_encode($etudiants[$n_et]['nom']); ?></td>
			<?php for($nsem = $arr_cycle['semestre_debut']; $nsem <=$arr_cycle['semestre_fin']; $nsem++){
				echo "<td><input name=\"etudiant_".$n_et."\" value=\"".$nsem."_".$etudiants[$n_et]['idniv']."\" ";
				if ($etudiants[$n_et]['semestre']==$nsem){
					echo "checked=\"checked\"";
				}
				echo " type=\"radio\" /></td>\n";
			}
			echo "<td><a href=\"exclure_etudiant.php?id_etudiant=".$etudiants[$n_et]['id']."&nPeriode=".$semestre_courant."&cycle=".$cycle."\">changer</a></td>\n";
		 } ?>
		 </tr>
	</tbody>
</table>
	<fieldset style="border-style: none;">
		<input type=hidden name="nPeriode" value="<?php echo $semestre_courant; ?>"/>";
		<input type=hidden name="cycle" value="<?php echo $cycle; ?>"/>";
		<input type="submit" name="action" value="<?php echo $message_rec; ?>" />
	</fieldset>
</form>

</div>
</body>
</html>
<?php
}
?>
