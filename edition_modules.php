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
include("regles_utilisateurs.php");
include("inc_sem_courant.php");
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
	$requete = "SELECT modules.*, ecoles.nom as nom_ecole, ecoles.id as id_ecole FROM modules, ecoles WHERE modules.id = '".$id."' and ecoles.id = modules.ecole;";
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
	</head>
<body>
<form id="formulaire" action="reg_module.php?nPeriode=<?php echo $semestre_courant;?>" method="post">
<table style="text-align: left; width: 626px; height: 228px;"
 border="1" cellpadding="2" cellspacing="2">
  <tbody>
  <?php 
  $peut_ajouter=($droits[$_SESSION['auto']]['edit_modules_adv'])?1:0;
  //$peut_ajouter=($_SESSION['auto']=='a')?1:0;
  if($peut_ajouter){?>
    <tr>
      
      <td style="width: 70px;">Intitulé</td>
      <td colspan="7" rowspan="1"> 
    <?php 
	echo selecteurModules("edition_modules.php?nPeriode=$semestre_courant","modules","id","intitule","id",$connexion,$ligne['id'],0,$peut_ajouter, "code",$semestre_courant); 
	?>
	</td>
    </tr>
    <?php } ?>
    <tr>
      <td style="width: 70px;">Modifier ici</td>
      <td colspan="5" rowspan="1">
      <?php echo affiche_ligne("intitule",($ligne['intitule']),30); ?>
	</td>
      <td>Code</td>
      <td>
      <?php 
      if($peut_ajouter){
      echo affiche_ligne_courte("code",($ligne['code']));
      }else{echo utf8_encode($ligne['code']);}
      ?>

      </td>
    </tr>
    <tr>
      <td colspan="8" rowspan="1";">
      <?php echo affiche_champs("description",($ligne['description']),100,8); ?></td>
    </tr>
    <tr>
      <td style="width: 70px;">Jour</td>
      <td style="width: 52px;">
      <?php $liste_j= Array('lundi','mardi','mercredi','jeudi','vendredi','samedi','atypique');
      for ($nj=0; $nj<count($liste_j);$nj++){
      	$liste_jours[$nj]['aff']=$liste_j[$nj];
      	$liste_jours[$nj]['val']=$liste_j[$nj];
      }
      echo selecteur_objets("",0,"jour","jour",$connexion,$ligne['jour'],$liste_jours,0); ?>
      </td>
      <td style="width: 70px;">Début</td>
      <td style="width: 78px;">
      <?php echo selecteur_objets("",0,"debut","debut",$connexion,$ligne['debut'],liste_numero(8,10,1,""," h.&nbsp;"),0); ?>
      </td>
      <td style="width: 54px;">Fin</td>
      <td style="width: 83px;">
      <?php echo selecteur_objets("",0,"fin","fin",$connexion,$ligne['fin'],liste_numero(9,12,1,""," h.&nbsp;"),0); ?>
      </td>
      <td style="width: 105px;">Nonbre de séances</td>
      <td style="width: 58px;"><?php echo selecteur_objets("",0,"seances","seances",$connexion,$ligne['seances'],liste_numero(1,12,1,"",""),0); ?></td>
    </tr>
    <tr>
      <td style="width: 70px;">Crédits</td>
      <td style="width: 52px;">
      <?php echo selecteur_objets("",0,"credits","credits",$connexion,$ligne['credits'],liste_numero(1,20,1,"","&nbsp;"),0); ?>
      </td>
      <td style="width: 70px;">Obligatoire </td>
      <td style="width: 78px;">
      <?php 
      $vide[0]['aff']='aucun';
      $vide[0]['val']=-1;
      echo selecteur_objets("",0,"obligatoire","obligatoire",$connexion,$ligne['obligatoire'],array_merge($vide,liste_numero(1,10,1,"s_","")),0); ?>
      </td>
      <td style="width: 54px;"></td>
      <td style="width: 83px;"></td>
      <td style="width: 105px;">Désuétude (aaaa-mm-jj)</td>
      <td style="width: 58px;"><?php echo affiche_ligne_courte("desuetude",$ligne['desuetude']); ?></td>
    </tr>
    <tr>
      <td>Pré-requis</td>
      <td colspan="5" rowspan="1" style="width: 78px;">
      <?php echo affiche_ligne("pre_requis",$ligne['pre_requis'],60); ?>
      </td>
      <td style="width: 105px;">
		<?php echo selecteurObjets("","modules","ajout_pre_requis","code","code",$connexion,$ligne['code'],0,0,"code"); ?>
		</td>
      <td style="width: 58px;"><input type="submit" name="action" value="ajouter un pre-requis"></td>
    </tr>
    <tr>
      <td style="width: 70px;">Enseignants</td>
      <td colspan="5" rowspan="1" style="width: 52px;">
      <?php echo affiche_ligne("enseignants",($ligne['enseignants']),60); ?>
      </td>
      <td style="width: 105px;"><?php echo selecteurObjets("","professeurs","ajout_prof","nom_complet","nom_complet",$connexion,"",0,0,"nom"); ?>
		</td>
      <td style="width: 58px;"><input type="submit" name="action" value="ajouter un enseignant"></td>
    </tr>
    
    
    <tr>
      <td colspan="2" rowspan="1" style="width: 70px;">
      Mode d'évaluation
      </td>
      <td colspan="6" rowspan="1" style="width: 70px;">
       <?php echo affiche_champs("evaluation",($ligne['evaluation']),80,5); ?>
      </td>
    </tr>
   
  </tbody>
</table>
 <?php
 
    echo "<div class=\"selecteur_ecole\" >École : ";
    if($peut_ajouter){
    echo selecteurObjets($src,"ecoles", "ecole","nom", "id", $connexion, $ligne["id_ecole"], 0, 0, "nom");}
    else{
    	?>
			<input type="hidden" name="ecole" value="<?php echo $ligne["id_ecole"]?>">
		<?php
    	echo $ligne['nom_ecole'];
    }
	echo "\n</div>";
 
	?>
<input type="hidden" name="old_id" value="<?php echo ($ligne["id"])?$ligne["id"]:-1; ?>">
<input type="submit" name="action" value="valider les modifications">
</form>
</body>
</html>